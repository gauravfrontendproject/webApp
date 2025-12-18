<?php

namespace App\Controllers;
use CodeIgniter\Files\File;
use App\Models\AdminModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Admin extends BaseController
{
    public function index(): string
    {
        // echo "Welcome to Admin Panel";
        // Load form helper so view helpers like set_value() are available
        // helper('form');

        $data = [];

        return view('admin/login', $data);
    }

    public function dashboard(): string
    {
        $data = [];
        $data['main_content'] = 'admin/dashboard';
        return view('admin/includes/template', $data);
    }

    public function signin()
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

                // If user submitted neither field, show a clear message
                $emailPost = $this->request->getVar('email');
                $passwordPost = $this->request->getVar('password');
                if (empty($emailPost) && empty($passwordPost)) {
                    $data['login_error'] = 'Please enter the details';
                } else {
                    $data['validation'] = $this->validator;
                }
            } else {
                $model = new AdminModel();
                $email = $this->request->getVar('email');
                $password = $this->request->getVar('password');

                $user = $model->where('email', $email)->first();

                if (!$user) {
                    $data['login_error'] = 'Password or email is wrong';
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
                        $data['login_error'] = 'Password or email is wrong';
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
                                'redirect' => site_url('/'),
                            ]);
                        }

                        return redirect()->to(base_url());
                    }
                }
            }
        }

        return view('login', $data);
    }


}
