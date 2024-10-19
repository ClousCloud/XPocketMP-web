<?php

namespace App\Middleware;

use App\Models\User;
use App\Config\Database;

class UserAuth
{
    protected $pdo;

    public function __construct()
    {
        $this->pdo = Database::getPDO();
    }

    public function register($name, $email, $password, $isAdmin = false)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare('INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$name, $email, $hashedPassword, $isAdmin]);
    }

    public function login($email, $password)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);

        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['is_admin'] = $user['is_admin'];
            return true;
        }

        return false;
    }

    public function logout()
    {
        session_destroy();
    }

    public function isAdmin()
    {
        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
    }
}