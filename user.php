<?php

require_once "database.php";

class User extends Database
{
    public $full_name = "";
    public $email = "";
    public $password = "";
    public $phone = "";
    public $role = "customer";

    // Register new user
    public function register()
    {
        $sql = "INSERT INTO users(full_name, email, password, phone, role) 
                VALUES(:full_name, :email, :password, :phone, :role)";
        
        $query = $this->connect()->prepare($sql);
        
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        
        $query->bindParam(":full_name", $this->full_name);
        $query->bindParam(":email", $this->email);
        $query->bindParam(":password", $hashedPassword);
        $query->bindParam(":phone", $this->phone);
        $query->bindParam(":role", $this->role);

        return $query->execute();
    }

    // Check if email already exists
    public function emailExists($email)
    {
        $sql = "SELECT COUNT(*) as total FROM users WHERE email = :email";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":email", $email);
        
        if ($query->execute()) {
            $result = $query->fetch();
            return $result['total'] > 0;
        }
        return false;
    }

    // Login user
    public function login($email, $password)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":email", $email);
        
        if ($query->execute()) {
            $user = $query->fetch(PDO::FETCH_ASSOC);
            if ($user && password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }

    // Get user by ID
    public function getUserById($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":id", $id);
        
        if ($query->execute()) {
            return $query->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }

    // Get all users
    public function getAllUsers()
    {
        $sql = "SELECT id, full_name, email, phone, role, created_at FROM users ORDER BY created_at DESC";
        $query = $this->connect()->prepare($sql);
        
        if ($query->execute()) {
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }
}