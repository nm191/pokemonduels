<?php
/**
 * Created by PhpStorm.
 * User: Gebruiker
 * Date: 16-1-2017
 * Time: 19:03
 */

require_once ('../init.php');
$chat = new Chat();



$formname = '';
if(isset($_POST['formname'])){
    $formname = $_POST['formname'];
}

switch($formname){
    case "addLine":
        if(empty($_POST['user_id']) || empty($_POST['text'])){
            die('No text/user_id given');
        }

        if($chat->addLine($_POST['user_id'], $_POST['text'])){
            die('Added line');
        }
        break;
    case "getLines":
        if(empty($_POST['user_id'])){
            die('No text/user_id given');
        }
        die($chat->getLines($_POST['user_id'], $_POST['returnOwn']));
        break;
}


