<?php

/**
 * Created by PhpStorm.
 * User: Gebruiker
 * Date: 16-1-2017
 * Time: 23:18
 */
class Battle
{
    private $db;

    public function __construct(){
        $this->db = Database::getInstance();
    }

    public function joinBattleRoom($user_id, $room_key){
        if($this->alreadyJoinedBattleRoom($user_id, $room_key)){
            return false;
        }elseif ($this->battleRoomExists($room_key)){
            $this->updateBattleRoom($room_key, 'player_id_2', $user_id);
            $this->updateBattleRoom($room_key, 'status', 'picking teams');
            return true;
        }

        return $this->createBattleRoom($room_key, $user_id);
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

    private function updateBattleRoom($room_key, $field_name, $field_value){
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