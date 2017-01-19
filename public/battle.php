<?php
/**
 * Created by PhpStorm.
 * User: Nick van Meel
 * Date: 19-1-2017
 * Time: 11:55
 */

require_once ('includes/header.php');
$protect = new Protect();
$protect->loggedInOnly();
$pokemon = new Pokemon();
$battle = new Battle();
if(!isset($_GET['room_key'])){
    $user->redirect('lobby.php');
}
$room_key = $_GET['room_key'];

if(!isset($_SESSION['team'])){
    $_SESSION['team'] = array();
}

$team = $_SESSION['team'];
if(!isset($_SESSION['opposite_team'])){
    $opposite_team = json_decode($battle->getOppositeTeam($room_key));
    $_SESSION['opposite_team'] = $opposite_team;
}else{
    $opposite_team = $_SESSION['opposite_team'];
}
var_dump($_SESSION['player']);
?>
<div class="overlay" style="display: none;">
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="jumbotron jumbotron-fluid">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12 opposite_player <?= ($_SESSION['player'] == 'player_1' ? 'player_2' : 'player_1'); ?>">
                            <div class="battle_pokemon float-right clearfix">
                                <div class="progress float-right">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="<?= $pokemon->getHP($opposite_team[0])?>" aria-valuemin="0" aria-valuemax=" <?= $pokemon->getHP($opposite_team[0])?>"><span class="current_hp"><?= $pokemon->getHP($opposite_team[0])?></span>/<?= $pokemon->getHP($opposite_team[0])?></div>
                                </div>
                                <img src="<?= $opposite_team[0]->sprites->front_default?>" alt="" class="float-right">
                            </div>
                         </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 this_player <?= $_SESSION['player']; ?>">
                            <div class="battle_pokemon">
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="<?= $pokemon->getHP($team[0])?>" aria-valuemin="0" aria-valuemax=" <?= $pokemon->getHP($team[0])?>"><span class="current_hp"><?= $pokemon->getHP($team[0])?></span>/<?= $pokemon->getHP($team[0])?></div>
                                </div>
                                <img src="<?= $team[0]->sprites->back_default?>" alt="">
                                <?= $pokemon->getMoves($team[0]); ?>
                                <div class="battle_moves_text" style="display: none;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/battle.js"></script>
<?php
require_once ('includes/footer.php');
?>