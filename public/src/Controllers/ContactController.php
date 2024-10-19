<?php

namespace App\Controllers;

use App\Models\Contact;

class ContactController
{
    public function submitContactForm()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $contact = new Contact();
            $contact->name = $_POST['name'];
            $contact->email = $_POST['email'];
            $contact->message = $_POST['message'];
            $contact->save();

            echo "Thank you for your message!";
        } else {
            include __DIR__ . '/../views/contact.php';
        }
    }
}