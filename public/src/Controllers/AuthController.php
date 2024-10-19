<?php

namespace App\Controllers;

use App\Models\User;

class AuthController
{
    public function showLoginForm()
    {
        include __DIR__ . '/../views/login.php';
    }

    public function login()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = User::findByEmail($email);

        if ($user && password_verify($password, $user->getPassword())) {
            // Set session
            $_SESSION['user'] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'is_admin' => $user->isAdmin(),
            ];

            // Redirect to home or admin page based on role
            if ($user->isAdmin()) {
                header('Location: /admin');
            } else {
                header('Location: /home');
            }
            exit;
        } else {
            // Login failed, redirect back to login page with error
            header('Location: /login?error=invalid_credentials');
            exit;
        }
    }

    public function showRegisterForm()
    {
        include __DIR__ . '/../views/register.php';
    }

    public function register()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $user = new User($name, $email, $password);
        $user->save();

        // Automatically log in the user after registration
        $_SESSION['user'] = [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'is_admin' => $user->isAdmin(),
        ];

        header('Location: /home');
        exit;
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}