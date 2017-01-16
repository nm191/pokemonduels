<?php
/**
 * Created by PhpStorm.
 * User: Gebruiker
 * Date: 16-1-2017
 * Time: 21:14
 */
require_once ('../init.php');
$formname = '';
if(isset($_POST['formname'])){
    $formname =  $_POST['formname'];
}
$notifications = new Notifications();

switch($formname){
    case 'checkNotifications':
        if(!isset($_POST['user_id'])){
            die(false);
        }
        die($notifications->getNotificationOutput($_POST['user_id']));
        break;
    case 'denyChallenge':
        if(!isset($_POST['id'])){
            die(false);
        }
        die($notifications->denyChallenge($_POST['id']));
        break;
    case 'acceptChallenge':
        if(!isset($_POST['id'])){
            die(false);
        }
        die($notifications->acceptChallenge($_POST['id']));
        break;
    case 'sendChallenge':
        if(!isset($_POST['user_id'])){die(false);}
        $room_key = $notifications->sendChallenge($_POST['user_id']);

        die($room_key);
        break;
    case 'checkNotificationStatus':
        if(!isset($_POST['room_key'])){
            return false;
        }

        die($notifications->getStatus($_POST['room_key']));
        break;
}
