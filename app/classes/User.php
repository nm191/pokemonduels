<?php

/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 16-1-2017
 * Time: 13:51
 */
class User
{
    private $db;
    public function __construct(){
        $this->db = Database::getInstance();
    }

    public function EmailExists($email){
        $sql = 'SELECT COUNT(*) AS count FROM users WHERE email = :email';
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->count;
    }

    public function UsernameExists($username){
        $sql = 'SELECT COUNT(*) AS count FROM users WHERE username = :username';
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->count;
    }

    public function registerUser($posted_values = array()){
        if(!$posted_values){ return false; }

        $sql = 'INSERT INTO users(email, username, password) VALUES(:email, :username, :password)';
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':email', $posted_values['email']);
        $stmt->bindParam(':username', $posted_values['username']);
        $stmt->bindParam(':password', $posted_values['password']);

        return $stmt->execute();
    }

    public function loginUser($posted_values = array()){
        if(!$posted_values){ return false; }

        $sql = 'SELECT * FROM users WHERE email = :email';
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':email', $posted_values['email']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        if(!password_verify($posted_values['password'], $result->password)){
            die(false);
        }

        if($this->updateTimeStamp($result->id)){
            $_SESSION['user_info'] = $result;
        }

        die(true);
    }

    public function redirect($path){
        header('Location: '.$path);
        die();
    }

    public function updateTimeStamp($user_id){
        $sql = 'UPDATE users SET timestamp = CURRENT_TIMESTAMP WHERE id = :id';
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $user_id);
        return $stmt->execute();
    }

    private function getOnlineUsers(){
        $timestamp = date('Y-m-d H:i:s', strtotime('-1 hour'));
        $sql = 'SELECT id, username, email FROM users WHERE timestamp >= :timestamp';
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':timestamp', $timestamp);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getOnlineUsersList(){
        $online_users_ar = $this->getOnlineUsers();

        $return_ar = array();
        $return_ar[] = '<ul class="list-group">';
        foreach($online_users_ar as $user){
            $tmp_item_ar = array();
            $tmp_item_ar[] = ' <li class="list-group-item">'.$user->username.'</li>';
            $return_ar[] = implode('', $tmp_item_ar);
        }
        $return_ar[] = '</ul>';

        return implode('', $return_ar);
    }
}