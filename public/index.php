<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 16-1-2017
 * Time: 11:29
 */
    require_once ('includes/header.php');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-10 offset-sm-1">
            <div class="jumbotron jumbotron-fluid">
                <div class="container text-sm-center">
                    <h1 class="display-3">Pokemon Duels!</h1>
                    <p class="lead">To duel with your friends, login or register an account below!</p>
                    <hr class="my-4">
                    <p class="lead">
                        <a class="btn btn-outline-success btn-lg" href="login.php" role="button">Login</a>
                        <a class="btn btn-outline-primary btn-lg" href="register.php" role="button">Register</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    require_once ('includes/footer.php');
?>
