<?php
/**
 * Created by PhpStorm.
 * User: Gebruiker
 * Date: 16-1-2017
 * Time: 23:07
 */
require_once ('includes/header.php');
$protect = new Protect();
$protect->loggedInOnly();

if(!isset($_GET['room_key'])){
    $user->redirect('lobby.php');
}
$room_key = $_GET['room_key'];
$battle = new Battle();
$player = $battle->joinBattleRoom($_SESSION['user_info']->id, $room_key);

if(!isset($_SESSION['player'])){
    $_SESSION['player'] = '';
}

$_SESSION['player'] = $player;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="jumbotron jumbotron-fluid">
                <div class="container-fluid">
                   <div class="row">
                        <div class="col-sm-12">
                            <h1 class="display-3">Waiting Room</h1>
                            <p class="lead">Waiting for other player! DO NOT CLOSE THIS WINDOW!</p>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/waiting_room.js"></script>
<?php
require_once ('includes/footer.php');
?>
