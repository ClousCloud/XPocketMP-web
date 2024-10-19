<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class User
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $is_admin;

    public function __construct($name = '', $email = '', $password = '', $is_admin = 0)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->is_admin = $is_admin;
    }

    public function save()
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO users (name, email, password, is_admin) VALUES (:name, :email, :password, :is_admin)");
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':is_admin', $this->is_admin);
        $stmt->execute();

        $this->id = $db->lastInsertId(); // Set the user ID after insert
    }

    public static function findByEmail($email)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchObject(__CLASS__);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }

    public function setAdmin($is_admin)
    {
        $this->is_admin = $is_admin;
    }
}