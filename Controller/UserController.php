<?php

namespace Controller;

use Model\User;
use Exception;

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // REGISTRO DE USUÁRIO
    public function registerUser($user_fullname, $email, $password)
    {
        try {
            if (empty($user_fullname) or empty($email) or empty($password)) {
                return false;
            }

            // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            return $this->userModel->registerUser($user_fullname, $email, $password);
        } catch (Exception $error) {
            echo "Erro ao cadastrar usuário: " . $error->getMessage();
            return false;
        }
    }

    // E-MAIL JÁ CADASTRADO?
    public function checkUserByEmail($email) {
        return $this->userModel->getUserByEmail($email);
    }

    // LOGIN DE USUÁRIO
    public function login($email, $password)
    {
        $user = $this->userModel->getUserByEmail($email);

        /**
         * $user = [
         *    "id" => 1,
         *    "user_fullname" => "Teste",
         *    "email" => "teste@example.com",
         *    "password" => "$2y$10$19ujCfISbUFtFqPRJx9PN.G8fGcqNCkWTnitJpMOdJZ0x6TYL6EzC",
         *    ...
         * ]
         */
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['user_fullname'] = $user['user_fullname'];
            $_SESSION['email'] = $user['email'];
            
            return true;
        }
        return false;
    }

    // USUÁRIO LOGADO?
    public function isLoggedIn(){
        return isset($_SESSION['id']);
    }

    // RESGATAR DADOS DO USUÁRIO
    public function getUserData($id, $user_fullname, $email) {
        $id = $_SESSION['id'];

        return $this->userModel->getUserInfo($id, $user_fullname, $email);
    }
}

?>