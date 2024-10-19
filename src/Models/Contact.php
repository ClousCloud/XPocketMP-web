<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Contact
{
    public $name;
    public $email;
    public $message;

    public function save()
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO contacts (name, email, message) VALUES (:name, :email, :message)");
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':message', $this->message);
        $stmt->execute();
    }

    public static function all()
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM contacts");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}