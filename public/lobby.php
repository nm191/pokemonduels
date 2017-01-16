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
    <div class="alert alert-info notification" role="alert" style="display:none;">
<!--        <strong>Challenge!</strong> You have been challenged by <i>%placeholder%</i> will you accept?<br>-->
<!--        <button class="btn btn-success accept-challenge">Yes</button>-->
<!--        <button class="btn btn-danger deny-challenge">No</button>-->
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="jumbotron jumbotron-fluid">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12 text-sm-center">
                            <h1 class="display-3">Pokemon Duels!</h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <h2 class="display-4">Online Users</h2>
                            <?= $user->getOnlineUsersList(); ?>
                        </div>
                        <div class="col-sm-9">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" href="lobby.php">Lobby</a>
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?= $_SESSION['user_info']->username; ?></a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#">Profile</a>
                                        <a class="dropdown-item" href="#">Friends</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="logout.php">Logout</a>
                                    </div>
                                </li>
                            </ul>
                            <h3>Chat</h3>
                            <div class="chat-container clearfix">
<!--                                <div class="chat-text-other">-->
<!--                                    <div class="posted-by-container">-->
<!--                                        <span class="posted-by">-->
<!--                                        <a href="#">Eindbaas</a>-->
<!--                                    </span>-->
<!--                                        <span class="time-posted">-->
<!--                                        (18:07)-->
<!--                                    </span>-->
<!--                                    </div>-->
<!--                                    <p class="lead">-->
<!--                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deleniti, dignissimos earum eligendi eum fuga harum impedit iure magni molestiae mollitia, non officiis quisquam quod recusandae sed sunt tempora tenetur! Dolor?-->
<!--                                    </p>-->
<!--                                </div>-->
<!--                                <div class="chat-text-other">-->
<!--                                    <div class="posted-by-container">-->
<!--                                        <span class="posted-by">-->
<!--                                        <a href="#">Eindbaas</a>-->
<!--                                    </span>-->
<!--                                        <span class="time-posted">-->
<!--                                        (18:07)-->
<!--                                    </span>-->
<!--                                    </div>-->
<!--                                    <p class="lead">-->
<!--                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deleniti, dignissimos earum eligendi eum fuga harum impedit iure magni molestiae mollitia, non officiis quisquam quod recusandae sed sunt tempora tenetur! Dolor?-->
<!--                                    </p>-->
<!--                                </div>-->
<!--                                <div class="chat-text-other">-->
<!--                                    <div class="posted-by-container">-->
<!--                                        <span class="posted-by">-->
<!--                                        <a href="#">Eindbaas</a>-->
<!--                                    </span>-->
<!--                                        <span class="time-posted">-->
<!--                                        (18:07)-->
<!--                                    </span>-->
<!--                                    </div>-->
<!--                                    <p class="lead">-->
<!--                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deleniti, dignissimos earum eligendi eum fuga harum impedit iure magni molestiae mollitia, non officiis quisquam quod recusandae sed sunt tempora tenetur! Dolor?-->
<!--                                    </p>-->
<!--                                </div>-->
<!--                                <div class="chat-text-self float-right clearfix">-->
<!--                                    <div class="posted-by-container text-right">-->
<!--                                        <span class="posted-by">-->
<!--                                        <a href="#">You</a>-->
<!--                                    </span>-->
<!--                                        <span class="time-posted">-->
<!--                                        (18:07)-->
<!--                                    </span>-->
<!--                                    </div>-->
<!---->
<!--                                    <p class="lead">-->
<!--                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deleniti, dignissimos earum eligendi eum fuga harum impedit iure magni molestiae mollitia, non officiis quisquam quod recusandae sed sunt tempora tenetur! Dolor?-->
<!--                                    </p>-->
<!--                                </div>-->
                            </div>
                            <div class="text-input-container">
                                <div class="input-group">
                                    <textarea class="form-control" id="text" rows="5"></textarea>
                                    <input type="hidden" id="user_id" value="<?= $_SESSION['user_info']->id; ?>">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary send" type="button">Send</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once ('includes/footer.php');
?>
