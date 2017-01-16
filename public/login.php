<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 16-1-2017
 * Time: 13:56
 */
require_once ('includes/header.php');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-10 offset-sm-1">
            <div class="jumbotron jumbotron-fluid">
                <div class="container text-sm-center">
                    <h1 class="display-3">Login</h1>
                    <div class="col-sm-8 offset-sm-2">
                        <form>
                            <div class="form-group">
                                <label for="txtEmail">Email address</label>
                                <input type="email" class="form-control" id="txtEmail" placeholder="Enter email">
                            </div>
                            <div class="form-group">
                                <label for="txtPassword">Password</label>
                                <input type="password" class="form-control" id="txtPassword" placeholder="Password">
                            </div>
                            <input type="hidden" class="form-control" id="hidden_formname" value="login">
                            <button class="btn btn-outline-success btn-block btn-lg login">Login</button>
                        </form>
                        <hr class="my-4">
                        <p class="lead">No account? <a href="register.php">Click here</a> to register.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once ('includes/footer.php');
?>

