<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 16-1-2017
 * Time: 14:10
 */

require_once ('../init.php');
$formname = '';
if(isset($_POST['hidden_formname'])){
    $formname = $_POST['hidden_formname'];
}

switch($formname){
    case 'register':

        //check if all fields are filled
        $required_ar = array('email', 'username', 'password', 'password_confirm');
        foreach($required_ar  as &$field_name){
           if(empty($POST[$field_name])){
               die('Not all fields all filled in');
           }
        }

        //check if email exists
        if($user->EmailExists($_POST['email'])){
            die('Email already exists');
        }

        //check if username exists
        if($user->UsernameExists($_POST['username'])){
            die('Username already exists');
        }

        //check if passwords are the same
        if($_POST['password'] != $_POST['password_confirm']){
            die('Passwords do not match');
        }

        //hash password
        $posted_values['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $posted_values['username'] = $_POST['username'];
        $posted_values['email'] = $_POST['email'];

        //insert in database
        if($user->registerUser($posted_values)){
            die('Account is created!');
        }
        break;
    case 'login':
        //check if all fields are filled
        $required_ar = array('email', 'password');
        foreach($required_ar  as $field_name){
            if(!array_key_exists($field_name, $_POST)){
                die('Not all fields all filled in');
            }
        }

        //check if email exists
        if(!$user->EmailExists($_POST['email'])){
            die('Email doesn\'t exists');
        }

        $posted_values['email'] = $_POST['email'];
        $posted_values['password'] = $_POST['password'];

        if($user->loginUser($posted_values)){
            die('Logged in!');
        }

        break;
}