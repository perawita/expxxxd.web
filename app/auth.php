<?php
require_once 'app/crudUser.php';

class Auth {
    private $file = 'app/storage/users.json';
    private $dataUsers;

    public function __construct() {
        $this->dataUsers = new CrudUser();
    }

    public function login($email, $password) {
        $users = $this->dataUsers->getUsers();
        foreach ($users as $user) {
            if (trim(strtolower($user['email'])) === trim(strtolower($email))&& password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'saldo' => $user['saldo']
                ];
                return true;
            }
        }
        return false;
    }
}

?>
