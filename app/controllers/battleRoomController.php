<?php
/**
 * Created by PhpStorm.
 * User: Gebruiker
 * Date: 17-1-2017
 * Time: 00:18
 */

require_once ('../init.php');
$formname = '';
if(isset($_POST['formname'])){
    $formname =  $_POST['formname'];
}

$battle = new Battle();

switch($formname){
    case 'checkBattleRoomStatus':
        if(!isset($_POST['room_key'])){die();}
        die($battle->getBattleRoomStatus($_POST['room_key']));
        break;
}