<?php

/**
 * Created by PhpStorm.
 * User: Nick van Meel
 * Date: 16-1-2017
 * Time: 23:18
 */
class Battle
{
    private $db;
    private $pokemon;

    public function __construct(){
        $this->db = Database::getInstance();
        $this->pokemon = new Pokemon();
    }

    public function doMoves($room_key){
        $selected_moves = $this->getSelectedMoves($room_key);

        foreach($selected_moves as $move){
            $field_name = 'move_done_by_'.$_SESSION['player'];
            if($move->$field_name == 'no') {
                if ($move->player != $_SESSION['player']) {
                    $this->pokemon->doMove($move, $_SESSION['team'][0], 'team');
                } else {
                    $this->pokemon->doMove($move, $_SESSION['opposite_team'][0], 'opposite_team');
                }
            }

            if($move->move_done_by_player_1 == 'yes' && $move->move_done_by_player_2 == 'yes'){
                $this->updateBattleRoom($room_key, 'status', 'picking moves');
            }
        }
    }

    public function getSelectedMoves($room_key){
        $sql = 'SELECT * FROM selected_moves WHERE room_key = :room_key';
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':room_key', $room_key);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function setMoveChosenStatus($room_key, $player){
        $status = $this->getBattleRoomStatus($room_key);
        if($status == 'player_1 has selected a move' || $status == 'player_2 has selected a move'){
            $new_status = 'do moves';
        }else{
            $new_status = $player.' has selected a move';
        }

        return $this->updateBattleRoom($room_key, 'status', $new_status);
    }

    private function moveEntryExists($room_key, $player){
        $sql = 'SELECT COUNT(*) as count FROM selected_moves WHERE player = :player AND room_key = :room_key';
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':player', $player);
        $stmt->bindParam(':room_key', $room_key);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->count;
    }

    public function setSelectedMove($room_key, $move){
        $speed = 100;
        if(!$this->moveEntryExists($room_key, $_SESSION['player'])){
            $sql = 'INSERT INTO selected_moves (room_key, player, move_name, speed, power) VALUES(:room_key, :player, :move_name, :speed, :power)';
        }
        else{
            $sql = 'UPDATE selected_moves SET move_name = :move_name, speed = :speed, power = :power, move_done_by_player_1 = "no" , move_done_by_player_2 = "no"  WHERE room_key = :room_key AND player = :player';
        }
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':room_key', $room_key);
        $stmt->bindParam(':player', $_SESSION['player']);
        $stmt->bindParam(':move_name', $move->name);
        $stmt->bindParam(':speed', $speed);
        $stmt->bindParam(':power', $move->power);
        return $stmt->execute();
    }

    public function saveTeam($room_key){
        $team = json_encode($_SESSION['team']);

        $sql = 'INSERT INTO battle_teams (user_id, room_key, team) VALUES(:user_id, :room_key, :team)';
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $_SESSION['user_info']->id);
        $stmt->bindParam(':room_key', $room_key);
        $stmt->bindParam(':team', $team);
        return $stmt->execute();
    }

    public function getOppositeTeam($room_key){
        $sql = 'SELECT team FROM battle_teams WHERE room_key = :room_key AND user_id != :user_id';
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':room_key', $room_key);
        $stmt->bindParam('user_id', $_SESSION['user_info']->id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->team;
    }

    public function setPlayerReady($room_key, $player){
        if(!$this->battleRoomExists($room_key)){
           return false;
        }
        $status = $this->getBattleRoomStatus($room_key);
        if($status == 'player_1 is ready' || $status == 'player_2 is ready'){
            $new_status = 'in battle';
        }
        else{
            $new_status = $player.' is ready';
        }
        return $this->updateBattleRoom($room_key, 'status', $new_status);
    }

    public function joinBattleRoom($user_id, $room_key){
        if($this->alreadyJoinedBattleRoom($user_id, $room_key)){
            return false;
        }elseif ($this->battleRoomExists($room_key)){
            $this->updateBattleRoom($room_key, 'player_id_2', $user_id);
            $this->updateBattleRoom($room_key, 'status', 'picking teams');
            return 'player_2';
        }

        $this->createBattleRoom($room_key, $user_id);
        return 'player_1';
    }

    private function alreadyJoinedBattleRoom($user_id, $room_key){
        $sql = 'SELECT COUNT(*) as count FROM battle_rooms WHERE room_key = :room_key AND (player_id_1 = :user_id OR player_id_2 = :user_id)';
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':room_key', $room_key);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->count;
    }

    private function battleRoomExists($room_key){
        $sql = 'SELECT COUNT(*) as count FROM battle_rooms WHERE room_key = :room_key';
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':room_key', $room_key);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->count;
    }

    public function updateBattleRoom($room_key, $field_name, $field_value){
        $sql = 'UPDATE battle_rooms SET '.$field_name.' = :'.$field_name.' WHERE room_key = :room_key';
        $stmt = $this->db->pdo->prepare($sql);
        $bind = ':'.$field_name;
        $stmt->bindParam(':room_key', $room_key);
        $stmt->bindParam($bind, $field_value);
        return $stmt->execute();
    }

    private function createBattleRoom($room_key, $player_id_1){
        $sql = 'INSERT INTO battle_rooms(room_key, player_id_1) VALUES (:room_key, :player_id_1)';
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':room_key', $room_key);
        $stmt->bindParam(':player_id_1', $player_id_1);
        return $stmt->execute();
    }

    public function getBattleRoomStatus($room_key){
        $sql = 'SELECT status FROM battle_rooms WHERE room_key = :room_key';
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':room_key', $room_key);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->status;
    }

}