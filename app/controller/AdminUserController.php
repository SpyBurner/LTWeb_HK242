<?php
namespace controller;

use core\Logger;
use model\UserModel;
use service\UserService;

class AdminUserController extends BaseController {
    public function index() {
        $this->requireAuth(true);
        $data = [];

        $edit_id = $this->get('edit');
        $edit_user_entry = null;
        if ($edit_id != null) {
            $result = UserService::findById($edit_id);
            if (!$result['success']) {
                Logger::log('Failed to fetch user: ' . $result['message']);
                $this->redirectWithMessage('/admin/users', [
                    'error' => $result['message']
                ]);
            }
            $edit_user_entry = $result['data'];
        }

        if ($edit_user_entry != null)
            $data['edit_user_entry'] = $edit_user_entry;

        $result = UserService::findAll();
        if (!$result['success']) {
            Logger::log('Failed to fetch users: ' . $result['message']);
            $this->redirectWithMessage('/admin/', [
                'error' => $result['message']
            ]);
        }

        $data['user_list'] = $result['data'];

        $this->render('admin/user', $data);
    }

    public function addUser() {
        $this->requireAuth(true);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $this->post('username');
            $email = $this->post('email');
            $password = $this->post('password');
            $isadmin = $this->post('isadmin') ?? 0;

            if (empty($username) || empty($email) || empty($password)) {
                $this->redirectWithMessage('/admin/users', [
                    'error' => 'Please fill in all fields'
                ]);
            }

            $model = new UserModel(null, $username, $email, $password, null, $isadmin);

            $result = UserService::save($model);
            if (!$result['success']) {
                $this->redirectWithMessage('/admin/users', [
                    'error' => $result['message']
                ]);
            }

            $this->redirectWithMessage('/admin/users', [
                'success' => 'User added successfully'
            ]);
        }

        $this->redirectWithMessage('/admin/users', []);
    }

    public function editUser() {
        $this->requireAuth(true);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $this->post('id');
            $username = $this->post('username');
            $email = $this->post('email');
            $isadmin = $this->post('isadmin') ?? 0;

            if (empty($id) || empty($username) || empty($email)) {
                $this->redirectWithMessage('/admin/users', [
                    'error' => 'Please fill in all fields'
                ]);
            }

            $existing = UserService::findById($id);
            if (!$existing['success']) {
                $this->redirectWithMessage('/admin/users', [
                    'error' => $existing['message']
                ]);
            }

            $model = $existing['data'];

            assert($model instanceof UserModel);

            $model->setUsername($username);
            $model->setEmail($email);
            $model->setIsadmin($isadmin);

            $result = UserService::save($model);
            if (!$result['success']) {
                $this->redirectWithMessage('/admin/users', [
                    'error' => $result['message']
                ]);
            }

            $this->redirectWithMessage('/admin/users', [
                'success' => 'User updated successfully'
            ]);
        }

        $this->redirectWithMessage('/admin/users', []);
    }

    public function deleteUser() {
        $this->requireAuth(true);

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $id = $this->get('id');

            if (empty($id)) {
                $this->redirectWithMessage('/admin/users', [
                    'error' => 'Missing user ID'
                ]);
            }

            $result = UserService::deleteById($id);
            if (!$result['success']) {
                $this->redirectWithMessage('/admin/users', [
                    'error' => $result['message']
                ]);
            }

            $this->redirectWithMessage('/admin/users', [
                'success' => 'User deleted successfully'
            ]);
        }

        $this->redirectWithMessage('/admin/users', [
            'error' => 'User deletion fall-through'
        ]);
    }

    public function resetPassword() {
        $this->requireAuth(true); // Only admins

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $this->post('id');
            $newPassword = $this->post('new_password');

            if (empty($userId) || empty($newPassword)) {
                $this->redirectWithMessage('/admin/users', ['error' => 'Missing fields']);
            }

            // Hash the new password
            $hashed = password_hash($newPassword, PASSWORD_DEFAULT);

            $result = UserService::updatePassword($userId, $hashed);
            if (!$result['success']) {
                $this->redirectWithMessage('/admin/users', ['error' => $result['message']]);
            }

            $this->redirectWithMessage('/admin/users', ['success' => 'Password reset successfully']);
        }

        $this->redirectWithMessage('/admin/users', ['error' => 'Invalid request']);
    }

}
