<?php

/**
 * Created by PhpStorm.
 * User: Gebruiker
 * Date: 16-1-2017
 * Time: 21:12
 */
class Notifications
{
    private $db;

    public function __construct(){
        $this->db = Database::getInstance();
    }

    public function getStatus($room_key){
        $sql = 'SELECT status FROM notifications WHERE room_key = :room_key';
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':room_key', $room_key);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->status;
    }

    private function updateStatus($id, $status){
        $sql = 'UPDATE notifications SET status = :status WHERE id = :id';
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function denyChallenge($id){
        return $this->updateStatus($id, 'denied');
    }

    public function acceptChallenge($id){
        return $this->updateStatus($id, 'accepted');

    }

    public function sendChallenge($for_user_id){
        $room_key = sha1(microtime ());
        $sql = 'INSERT INTO notifications(for_user_id, from_user_id, timestamp, room_key) VALUES (:for_user_id, :from_user_id, CURRENT_TIMESTAMP, :room_key)';
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':for_user_id', $for_user_id);
        $stmt->bindParam(':from_user_id', $_SESSION['user_info']->id);
        $stmt->bindParam(':room_key', $room_key);
        if($stmt->execute()){
            return $room_key;
        }
    }


    private function getNotification($user_id){
        $sql = 'SELECT u.username, n.*
                FROM notifications n
                INNER JOIN users u
                ON n.from_user_id = u.id
                WHERE n.for_user_id = :user_id
                AND n.status = "no-answer"';
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getNotificationOutput($user_id){
        $notification = $this->getNotification($user_id);
        if(!$notification){ return false; }
        $return_ar[] = '<strong>Challenge!</strong> You have been challenged by <i>'.$notification->username.'</i> will you accept?<br>';
        $return_ar[] = '<button class="btn btn-success accept-challenge" data-id="'.$notification->id.'">Yes</button>';
        $return_ar[] = '<button class="btn btn-danger deny-challenge" data-id="'.$notification->id.'">No</button>';

        return implode(' ', $return_ar).$this->getJquery();
    }

    private function getJquery(){
        ob_start();
        ?>
        <script>
            $(document).ready(function(){
                $('.deny-challenge').on('click', function(){
                    var id = $(this).data('id');
                    var formname = 'denyChallenge';
                    $.ajax({
                        method: 'POST',
                        url: '../app/controllers/notificationController.php',
                        data: {id: id, formname: formname}
                    }).done(function(data){
                        $('.notification').hide('slow');
                    });
                });

                $('.accept-challenge').on('click', function(){
                    var id = $(this).data('id');
                    var formname = 'acceptChallenge';
                    $.ajax({
                        method: 'POST',
                        url: '../app/controllers/notificationController.php',
                        data: {id: id, formname: formname}
                    }).done(function(data){
                        $('.notification').hide('slow');
                    });
                });
            });
        </script>
    <?php
        return ob_get_clean();
    }

}