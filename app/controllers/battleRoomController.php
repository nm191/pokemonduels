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
        if(count($_SESSION['team']) > 1){
            die('Je hebt al 1 pokemon gekozen!');
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
        if(!isset($_POST['room_key'])){
            die();
        }
        $battle->setPlayerReady($_POST['room_key'], $_SESSION['player']);
        $battle->saveTeam($_POST['room_key']);
        die('ready');
        break;
    case 'selectedMove':
        if(!isset($_POST['room_key']) || !isset($_POST['move'])){
            die();
        }
        $move = json_decode($_POST['move']);
        $battle->setSelectedMove($_POST['room_key'], $move);
        $battle->setMoveChosenStatus($_POST['room_key'], $_SESSION['player']);
        break;
    case 'doMoves':
        if(!isset($_POST['room_key'])){
            die();
        }
        $selected_moves = $battle->getSelectedMoves($_POST['room_key']);

        foreach($selected_moves as $move){
            $field_name = 'move_done_by_'.$_SESSION['player'];

            if($move->move_done_by_player_1 == 'yes' && $move->move_done_by_player_2 == 'yes'){
                continue;

            }

            if($move->$field_name == 'no') {
                if ($move->player != $_SESSION['player']) {
                    $move_power = $pokemon->doMove($move, $_SESSION['team'][0], 'team');
                    die(json_encode(array($move->player, $move_power)));
                } else {
                    $move_power = $pokemon->doMove($move, $_SESSION['opposite_team'][0], 'opposite_team');
                    die(json_encode(array($move->player, $move_power)));
                }
            }
        }
        $battle->updateBattleRoom($_POST['room_key'], 'status', 'picking moves');
        die();
        break;
}