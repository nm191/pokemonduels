<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 17-1-2017
 * Time: 09:41
 */

require_once ('includes/header.php');
$protect = new Protect();
$protect->loggedInOnly();
$pokemon = new Pokemon();
if(!isset($_GET['room_key'])){
    $user->redirect('lobby.php');
}
$room_key = $_GET['room_key'];

if(!isset($_SESSION['team'])){
    $_SESSION['team'] = array();
}

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="jumbotron jumbotron-fluid">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12 pick_team_container">
                            <h1 class="display-3">Pick your team!</h1>
                            <p class="lead">Choose your team by searching pokemon.</p>
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchPokemon" placeholder="example: Bulbasaur">
                            </div>
                            <div class="pokemon-result">

                            </div>
                            <div class="your-team-container">
                                <h3 class="display-3">Your Team</h3>
                                <div class="pokemons">
                                    <?= $pokemon->getTeam($_SESSION['team']); ?>
                                </div>
                            </div>
                            <button class="btn btn-block btn-outline-success btn-lg btn_ready" data-room_key="<?= $room_key; ?>" >READY TO RUMBLEEE!</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/picking_teams.js"></script>
<?php
require_once ('includes/footer.php');
?>
