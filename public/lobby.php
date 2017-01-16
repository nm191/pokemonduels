<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 16-1-2017
 * Time: 11:29
 */
require_once ('includes/header.php');
$protect = new Protect();
$protect->loggedInOnly();
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-10 offset-sm-1">
            <div class="jumbotron jumbotron-fluid">
                <div class="container text-sm-center">
                    <ul class="nav nav-pills nav-fill">
                        <li class="nav-item">
                            <a class="nav-link active" href="lobby.php">Lobby</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="team.php">My Team</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                    <h1 class="display-3">Pokemon Duels Lobby!</h1>
                    <p class="lead">Lobby</p>
                    <?= $user->getOnlineUsersList(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once ('includes/footer.php');
?>
