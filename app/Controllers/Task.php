<?php

namespace App\Controllers;

use App\Models\TaskModel;

class Task extends BaseController
{
    public function index()
    {
        $model = new TaskModel();
        $user = session()->get('user');
        if (!$user) {
            return $this->response->setJSON(['error' => 'User not logged in']);
        }
        
        $model->where('user_id', $user['id']);
        
        $search = $this->request->getGet('search');

        if ($search) {
            $model->like('title', $search);
        }

        $page = $this->request->getGet('page') ?? 1;
        $limit = 5;

        $data = $model->paginate($limit);
        
        return $this->response->setJSON([
            'tasks' => $data,
            'pager' => $model->pager->getDetails()
        ]);
    }

    public function create()
    {
        $user = session()->get('user');

        if (!$user) {
            return $this->response->setJSON(['error' => 'User not logged in']);
        }

        $title = $this->request->getPost('title');

        if (empty($title)) {
            return $this->response->setJSON([
                'error' => 'Title is mandatory'
            ]);
        }

        $model = new TaskModel();

        $data = [
            'user_id' => $user['id'],
            'title' => $title,
            'description' => $this->request->getPost('description'),
            'status' => $this->request->getPost('status') ?? 'pending'
        ];

        $model->save($data);

        return $this->response->setJSON(['message' => 'Task created']);
    }
    
    public function update($id)
    {
        $model = new TaskModel();

        $model->where('user_id', session()->get('user')['id'])->update($id, [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'status' => $this->request->getPost('status')
        ]);

        return $this->response->setJSON(['message' => 'Updated']);
    }

    public function delete($id)
    {
        $model = new TaskModel();
        $model->where('user_id', session()->get('user')['id'])
        ->delete($id);
        return $this->response->setJSON(['message' => 'Deleted']);
    }
}

?>