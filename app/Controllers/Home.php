<?php

namespace App\Controllers;
use CodeIgniter\Files\File;
use App\Models\AdminModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Home extends BaseController
{
    public function index(): string
    {
        // Load form helper so view helpers like set_value() are available
        helper('form');

        // $data = [];
        $data['main_content'] = 'home';
        return view('includes/template', $data);
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
                // Phone is optional but should be numeric and reasonably sized
                'phone' => 'permit_empty|numeric|min_length[10]|max_length[15]',
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
                    'phone' => $this->request->getVar('phone'),
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
                    // Success: set flash message and redirect to login page
                    $insertId = $model->getInsertID();
                    $newName = $newData['name'];
                    $newEmail = $newData['email'];

                    session()->setFlashdata('signup_success', 'Congratulations! Your account has been created. Please login.');
                    log_message('info', 'New user signed up: ' . $newEmail . ' (id=' . $insertId . ')');
                    return redirect()->to('/login?signup=1');
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

        /* Important Queries */
        // $model->save($newdata);

        // find first data
        // $data = $model->where('user_type', 'admin')->first();
        // find all data
        // $data = $model->where('user_type', 'admin')->findAll();

        // find all data
        // $data = $model->findAll();

        // find by id with specific details
        // $data = $model->whereIn('id', [1, 3])->get()->getResultArray();

        // find not including specific id
        // $data = $model->whereNotIn('id', [1])->get()->getResultArray();

        // Display specific data using like(Used for searching)
        // $data = $model->like('name', 'am')->get()->getResultArray();

        // Insert Data
        // $newArray = [
        //     'name' => 'Gautam Kumar',
        //     'email' => 'gautam@gmail.com',
        //     'phone' => '9876543210',
        //     'password' => 'gautam123',
        // ];

        // $model->insert($newArray);
        // echo $model->getInsertID();
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        return view('dashboard', $data);
    }

    public function editUser($id)
    {
        $model = new AdminModel();
        if (strtolower($this->request->getMethod()) === 'post') {
            // Log POST payload for debugging
            try {
                log_message('debug', 'editUser POST received: ' . json_encode($this->request->getPost()));
            } catch (\Exception $e) {
                log_message('error', 'editUser: failed to log POST: ' . $e->getMessage());
            }
            // Use names that match the form inputs
            $newData = [
                'name' => $this->request->getVar('name'),
                'email' => $this->request->getVar('email'),
                'phone' => $this->request->getVar('phone'),
            ];

            // Basic validation: ensure required fields are present
            $errors = [];
            if (empty($newData['name']))
                $errors[] = 'Name is required.';
            if (empty($newData['email']))
                $errors[] = 'Email is required.';

            if (count($errors) === 0) {
                try {
                    $updated = $model->update($id, $newData);
                    log_message('debug', 'editUser update result for id=' . $id . ': ' . var_export($updated, true));
                    if ($updated === false) {
                        // Model update failed (validation or DB); collect errors
                        $data['update_error'] = $model->errors();
                        log_message('error', 'editUser update errors: ' . json_encode($data['update_error']));
                    } else {
                        // Redirect back to dashboard with a flash message
                        session()->setFlashdata('flash_mesage', 'Updated Successfully');
                        // $data['flash_mesage'] = TRUE;
                        return redirect()->to('dashboard');
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Failed to update user ' . $id . ': ' . $e->getMessage());
                    $data['update_error'] = ['exception' => $e->getMessage()];
                }
            } else {
                $data['update_error'] = $errors;
            }
        }

        // Fetch the user by primary key (use find for clarity)
        $user = $model->find($id);
        if (empty($user)) {
            // If user not found, redirect to dashboard with log
            log_message('error', 'editUser: user not found id=' . $id);
            return redirect()->to('dashboard');
        }

        $data['userdata'] = $user;
        return view('editUser', $data);
    }

    public function deleteUser($id)
    {
        $model = new AdminModel();
        try {
            $deleted = $model->delete($id);
            log_message('info', 'deleteUser: deleted user id=' . $id . ', result=' . var_export($deleted, true));
        } catch (\Exception $e) {
            log_message('error', 'deleteUser: failed to delete user id=' . $id . ': ' . $e->getMessage());
        }
        return redirect()->to('dashboard');
    }

    public function upload($id)
    {
        // Simple image upload handler. Saves uploaded image to writable/uploads
        // and sets a flash message on success.
        $data = [];

        if (strtolower($this->request->getMethod()) === 'post') {
            $validationrules = [
                'userfile' => [
                    'label' => 'Image File',
                    'rules' => 'uploaded[userfile]|is_image[userfile]|mime_in[userfile,image/jpg,image/jpeg,image/png]'
                ]
            ];

            if (!$this->validate($validationrules)) {
                $data['validation'] = $this->validator;
            } else {
                $img = $this->request->getFile('userfile');

                if ($img && $img->isValid() && !$img->hasMoved()) {
                    // Ensure upload directory exists
                    $uploadPath = WRITEPATH . 'uploads';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }

                    // Move file with a randomized name to avoid collisions
                    $newName = $img->getRandomName();
                    try {
                        $img->move($uploadPath, $newName);

                        // Save record to DB (imageupload table)
                        try {
                            $userId = session()->get('user_id');
                            $imageModel = new \App\Models\ImageUploadModel();
                            $insertData = [
                                'fkuserid' => $userId ? (int) $userId : 0,
                                'image' => $newName,
                            ];
                            $insertId = $imageModel->insert($insertData);
                            if ($insertId === false) {
                                // Model insert failed
                                $errors = $imageModel->errors();
                                log_message('error', 'upload: DB insert failed: ' . json_encode($errors));
                                $data['upload_error'] = 'Uploaded but failed to record in database.';
                            } else {
                                session()->setFlashdata('Flash_message', 'Image uploaded successfully');
                                // Redirect back to form (PRG pattern)
                                return redirect()->to('/upload/upload_form');
                            }
                        } catch (\Exception $e) {
                            log_message('error', 'upload: exception saving DB record: ' . $e->getMessage());
                            $data['upload_error'] = 'Uploaded but failed to record in database.';
                        }
                    } catch (\Exception $e) {
                        log_message('error', 'upload: failed to move uploaded file: ' . $e->getMessage());
                        $data['upload_error'] = 'Failed to save uploaded file.';
                    }
                } else {
                    // Capture file-level errors
                    $data['upload_error'] = $img ? $img->getErrorString() : 'No file uploaded.';
                }
            }
        }

        return view('upload_form', $data);
    }

    public function exportuserdata()
    {
        $data = [];
        $model = new AdminModel();
        $fileName = 'employees.xlsx';
        $spreadsheet = new Spreadsheet();

        $employees = $model->findAll();
        $sheet = $spreadsheet->getActiveSheet();

        // Prepare Excel content
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Phone');

        $rows = 2;
        foreach ($employees as $val) {
            $sheet->setCellValue("A" . $rows, $val['id']);
            $sheet->setCellValue("B" . $rows, $val['name']);
            $sheet->setCellValue("C" . $rows, $val['email']);
            $sheet->setCellValue("D" . $rows, $val['phone']);
            $rows++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($fileName);
        header("content-type : application/vnd.ms-excel");
        header('content-disposition : attachment; filename="' . $fileName . '"');
        header('Expires:0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length:' . filesize($fileName));
        flush();
        readfile($fileName);
        exit;
    }
    public function logout()
    {
        session()->destroy();
        log_message('info', 'User logged out');
        return redirect()->to('/login');
    }
}
