<?php

/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 16-1-2017
 * Time: 15:46
 */
class Protect
{
    private $user;

    public function __construct(){
        $this->user = new User;
    }

    public function loggedInOnly(){
        if(!isset($_SESSION['user_info'])){
            $this->user->redirect('index.php');
        }
    }
}