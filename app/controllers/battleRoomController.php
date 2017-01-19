<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 17-1-2017
 * Time: 00:18
 */

require_once ('../init.php');
$formname = '';
if(isset($_POST['formname'])){
    $formname =  $_POST['formname'];
}

$battle = new Battle();
$pokemon = new Pokemon();

switch($formname){
    case 'checkBattleRoomStatus':
        if(!isset($_POST['room_key'])){die();}
        die($battle->getBattleRoomStatus($_POST['room_key']));
        break;
    case 'getPokemonOutput':
        if(!isset($_POST['pokemon'])){
            die();
        }
        $pokemon_obj = json_decode($_POST['pokemon']);
        die($pokemon->getPokemonOutput($pokemon_obj));
        break;
    case 'getTeam':
        if(!isset($_POST['pokemon'])){
            die();
        }
        if(!isset($_SESSION['team'])){
            $_SESSION['team'] = array();
        }
        if(count($_SESSION['team']) > 4){
            die('Je hebt al 5 pokemon gekozen!');
        }
        $_SESSION['team'][] = json_decode($_POST['pokemon']);

        die($pokemon->getTeam($_SESSION['team']));
        break;
    case 'removeFromTeam':
        if(!isset($_POST['key'])){
            die();
        }
        unset($_SESSION['team'][$_POST['key']]);

        die($pokemon->getTeam($_SESSION['team']));
        break;
    case 'setPlayerReady':
        if(!isset($_POST['user_id'])){
            die();
        }
        die('ready');
        break;
}