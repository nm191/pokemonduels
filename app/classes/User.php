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
        $timestamp = date('Y-m-d H:i:s');
        $sql = 'UPDATE users SET timestamp = :timestamp WHERE id = :id';
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $user_id);
        $stmt->bindParam(':timestamp', $timestamp);
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
            $tmp_item_ar[] = '<li class="list-group-item"><a data-toggle="collapse" href="#collapse'.$user->username.'" aria-expanded="true" aria-controls="collapse1">'.$user->username.'</a></li>';
            if($_SESSION['user_info']->id != $user->id){
                $tmp_item_ar[] = '<ul id="collapse'.$user->username.'" class="collapse">';
                $tmp_item_ar[] = '<li class="list-group-item challenge" data-id="'.$user->id.'">Challenge!</li>';
            }
            $tmp_item_ar[] = '</ul>';
            $return_ar[] = implode('', $tmp_item_ar);
        }
        $return_ar[] = '</ul>';

        return implode('', $return_ar);
    }

    public function logout($user_id){
        $sql = 'UPDATE users SET timestamp = NULL WHERE id = :id';
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        session_destroy();
        return $this->redirect('index.php');
    }
}