<?php
namespace Controllers;

use Models\User;

require_once __DIR__ . '/../Models/User.php';

class UserController
{
    private User $userModel;

    public function __construct($connection)
    {
        $this->userModel = new User($connection);
    }

    public function showUsers(): array
    {
        return $this->userModel->getAllUsers();
    }

    public function getUserById($id): false|array|null
    {
        return $this->userModel->getUserById($id);
    }

    public function updateUser($data): void
    {
        $this->userModel->updateUser($data['id'], $data['name'], $data['surname'], $data['username'], $data['identity'], $data['email'], $data['active'], $data['password']);
    }
}