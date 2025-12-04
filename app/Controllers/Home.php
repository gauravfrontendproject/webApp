<?php

namespace App\Controllers;

use App\Models\AdminModel;

class Home extends BaseController
{
    public function index(): string
    {
        // Load form helper so view helpers like set_value() are available
        helper('form');

        $data = [];
        return view('login', $data);
    }

    public function login()
    {
        $data = [];
        helper('form');

        if (strtolower($this->request->getMethod()) === 'post') {
            // Log incoming POST for debugging (avoid logging passwords)
            try {
                $rawPost = $this->request->getPost();
                $logPayload = [
                    'email' => isset($rawPost['email']) ? $rawPost['email'] : null,
                ];
                log_message('debug', 'Login POST received: ' . json_encode($logPayload));
            } catch (\Exception $e) {
                log_message('error', 'Failed to log login POST: ' . $e->getMessage());
            }
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required'
            ];

            if (!$this->validate($rules)) {
                // On AJAX requests return JSON with validation errors
                if ($this->request->isAJAX()) {
                    return $this->response->setStatusCode(422)
                        ->setJSON([
                            'status' => 'error',
                            'errors' => $this->validator->getErrors(),
                        ]);
                }

                $data['validation'] = $this->validator;
            } else {
                $model = new AdminModel();
                $email = $this->request->getVar('email');
                $password = $this->request->getVar('password');

                $user = $model->where('email', $email)->first();

                if (!$user) {
                    $data['login_error'] = 'Email or password incorrect';
                    log_message('error', 'Login failed: email not found for ' . $email);

                    if ($this->request->isAJAX()) {
                        return $this->response->setStatusCode(401)
                            ->setJSON(['status' => 'error', 'message' => $data['login_error']]);
                    }
                } else {
                    // Support legacy plaintext passwords: if stored password is not a recognized hash,
                    // compare directly and re-hash on successful match.
                    $stored = isset($user['password']) ? $user['password'] : '';
                    $isHashed = (strpos($stored, '$2') === 0 || strpos($stored, '$argon') === 0);

                    $passwordMatches = false;
                    if ($isHashed) {
                        $passwordMatches = password_verify($password, $stored);
                    } else {
                        // plaintext comparison (legacy). Use hash_equals to avoid timing issues.
                        $passwordMatches = hash_equals((string) $stored, (string) $password);
                    }

                    if (!$passwordMatches) {
                        $data['login_error'] = 'Email or password incorrect';
                        log_message('error', 'Login failed: invalid password for ' . $email);

                        if ($this->request->isAJAX()) {
                            return $this->response->setStatusCode(401)
                                ->setJSON(['status' => 'error', 'message' => $data['login_error']]);
                        }
                    } else {
                        // If password was plaintext in DB, re-hash it now for security
                        if (!$isHashed) {
                            try {
                                $model->update($user['id'], ['password' => password_hash($password, PASSWORD_DEFAULT)]);
                                log_message('info', 'Re-hashed legacy plaintext password for user ' . $email);
                            } catch (\Exception $e) {
                                log_message('error', 'Failed to re-hash password for ' . $email . ': ' . $e->getMessage());
                            }
                        }

                        // success: set session and redirect to dashboard
                        $session = session();
                        $session->set([
                            'isLoggedIn' => true,
                            'user_id' => $user['id'],
                            'user_name' => $user['name'],
                            'user_email' => $user['email'],
                        ]);

                        log_message('info', 'User logged in: ' . $email);

                        if ($this->request->isAJAX()) {
                            return $this->response->setJSON([
                                'status' => 'ok',
                                'redirect' => site_url('/dashboard'),
                            ]);
                        }

                        return redirect()->to('/dashboard');
                    }
                }
            }
        }

        return view('login', $data);
    }

    public function signup()
    {
        $data = [];
        helper('form');

        if (strtolower($this->request->getMethod()) === 'post') {
            // Log incoming POST for debugging (avoid passwords)
            try {
                $rawPost = $this->request->getPost();
                $logPayload = [
                    'email' => isset($rawPost['email']) ? $rawPost['email'] : null,
                    'firstname' => isset($rawPost['firstname']) ? $rawPost['firstname'] : null,
                    'lastname' => isset($rawPost['lastname']) ? $rawPost['lastname'] : null,
                ];
                log_message('debug', 'Signup POST received: ' . json_encode($logPayload));
            } catch (\Exception $e) {
                log_message('error', 'Failed to log signup POST: ' . $e->getMessage());
            }
            // validation rules: use lowercase names that match your form's name attributes
            $rules = [
                'firstname' => 'required|min_length[3]|max_length[20]',
                'lastname' => 'required|min_length[3]|max_length[20]',
                'email' => 'required|min_length[8]|max_length[50]|valid_email|is_unique[admin.email]',
                'password' => 'required|min_length[8]|max_length[255]',
                'confirmPassword' => 'required|matches[password]',
            ];

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;

                // Log validation errors and POST payload for debugging
                try {
                    $post = $this->request->getPost();
                    log_message('error', 'Signup validation failed. POST: ' . json_encode($post));
                    log_message('error', 'Validation errors: ' . json_encode($this->validator->getErrors()));
                } catch (\Exception $e) {
                    log_message('error', 'Error logging validation debug info: ' . $e->getMessage());
                }
            } else {
                $model = new AdminModel();

                // Map firstname + lastname into the DB `name` column which the model/migration expect
                $first = $this->request->getVar('firstname');
                $last = $this->request->getVar('lastname');

                $newData = [
                    'name' => trim($first . ' ' . $last),
                    'email' => $this->request->getVar('email'),
                    'password' => $this->request->getVar('password'),
                ];

                $saved = $model->save($newData);

                if ($saved === false) {
                    // Collect model validation/errors and DB errors for debugging
                    $errors = $model->errors();
                    $db = \Config\Database::connect();
                    $dberr = $db->error();

                    // Log errors to writable/logs
                    log_message('error', 'AdminModel save failed: ' . json_encode($errors));
                    if (!empty($dberr['message'])) {
                        log_message('error', 'DB error: ' . json_encode($dberr));
                    }

                    // Pass errors to view so developer can see them in the page when testing
                    $data['save_error'] = [
                        'model_errors' => $errors,
                        'db_error' => $dberr,
                    ];
                    // Log POST and DB error details
                    try {
                        $post = $this->request->getPost();
                        log_message('error', 'Signup save failed. POST: ' . json_encode($post));
                        log_message('error', 'Model errors: ' . json_encode($errors));
                        log_message('error', 'DB error: ' . json_encode($dberr));
                    } catch (\Exception $e) {
                        log_message('error', 'Error logging save debug info: ' . $e->getMessage());
                    }
                } else {
                    // Set session and redirect to dashboard so user is logged in immediately
                    $insertId = $model->getInsertID();
                    $newName = $newData['name'];
                    $newEmail = $newData['email'];

                    $session = session();
                    $session->set([
                        'isLoggedIn' => true,
                        'user_id' => $insertId,
                        'user_name' => $newName,
                        'user_email' => $newEmail,
                    ]);

                    log_message('info', 'New user signed up: ' . $newEmail);
                    return redirect()->to('/dashboard');
                }
            }
        }

        return view('signup', $data);
    }

    public function dashboard()
    {
        $model = new AdminModel();
        $data['usersdata'] = $model->findAll();
        // print_r($data);
        return view('dashboard', $data);
    }

    public function editUser($id)
    {
        // echo $id;
        $model = new AdminModel();
        $data['userdata'] = $model->where('id', $id)->first();
        // print_r($data);
        return view('editUser');
    }

    public function logout()
    {
        session()->destroy();
        log_message('info', 'User logged out');
        return redirect()->to('/login');
    }
}
