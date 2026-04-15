<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function register()
    {
        $model = new UserModel();
        if (empty($this->request->getPost('name'))) {
            return $this->response->setJSON(['error' => 'Name is required']);
        }

        if (empty($this->request->getPost('email'))) {
            return $this->response->setJSON(['error' => 'Email is required']);
        }

        if (empty($this->request->getPost('password'))) {
            return $this->response->setJSON(['error' => 'Password is required']);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'user'
        ];

        $model->save($data);

        return $this->response->setJSON(['message' => 'User registered']);
    }

    public function login()
    {
        $model = new UserModel();
        $user = $model->where('email', $this->request->getPost('email'))->first();

        if (!$user || !password_verify($this->request->getPost('password'), $user['password'])) {
            return $this->response->setJSON(['error' => 'Invalid credentials']);
        }

        session()->set('user', $user);

        return $this->response->setJSON(['message' => 'Login successful']);
    }

    public function logout()
    {
        session()->destroy();
        return $this->response->setJSON(['message' => 'Logged out']);
    }
}

?>