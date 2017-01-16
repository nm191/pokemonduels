<?php

/**
 * Created by PhpStorm.
 * User: Gebruiker
 * Date: 16-1-2017
 * Time: 18:54
 */
class Chat
{
    private $db;

    public function __construct(){
        $this->db = Database::getInstance();
    }

    public function addLine($user_id, $text){
        $timestamp = date('Y-m-d H:i:s');
        $sql = 'INSERT INTO chat_lines (user_id, text, timestamp) VALUES(:user_id, :text, :timestamp)';
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':text', $text);
        $stmt->bindParam(':timestamp', $timestamp);
        return $stmt->execute();

    }

    private function getLineData(){
        $last_check = date('Y-m-d H:i:s', strtotime('-5 seconds'));
        $current_timestamp = date('Y-m-d H:i:s');

        $sql = 'SELECT cl.*, u.username
                FROM chat_lines cl
                INNER JOIN users u
                ON cl.user_id = u.id 
                WHERE cl.timestamp <= :current_timestamp
                AND cl.timestamp >= :last_check
                ORDER BY cl.timestamp';

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':current_timestamp', $current_timestamp);
        $stmt->bindParam(':last_check', $last_check);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getLines($user_id, $return_own){
        $lineData = $this->getLineData();
        if(empty($lineData)) {
            return;
        }

        $return_ar = array();
        foreach($lineData as $line){
            if($return_own == 'Nee' && $user_id == $line->user_id){ continue; }
            if($return_own == 'Ja' && $user_id != $line->user_id){ continue;}
            $tmp_item_ar = array();
            $tmp_item_ar[] = '<div class="chat-text-'.($line->user_id != $user_id ? 'other' : 'self float-right clearfix').'">';
            $tmp_item_ar[] = '<div class="posted-by-container '.($line->user_id != $user_id ? '' : 'text-right').'">';
            $tmp_item_ar[] = '<span class="posted-by">';
            $tmp_item_ar[] = '<a href="#">'.($line->user_id != $user_id ? $line->username : 'You').' </a>';
            $tmp_item_ar[] = '</span>';
            $tmp_item_ar[] = '<span class="time-posted">';
            $tmp_item_ar[] = '('.date('H:i', strtotime($line->timestamp)).')';
            $tmp_item_ar[] = '</span>';
            $tmp_item_ar[] = '</div>';
            $tmp_item_ar[] = '<p class="lead '.($line->user_id != $user_id ? '' : 'text-right').'">';
            $tmp_item_ar[] = $line->text;
            $tmp_item_ar[] = '</p>';
            $tmp_item_ar[] = '</div>';
            $return_ar[] = implode('', $tmp_item_ar);
        }
        return implode('', $return_ar);
    }



}