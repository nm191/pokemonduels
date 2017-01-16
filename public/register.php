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
                    <h1 class="display-3">Register an account</h1>
                    <div class="col-sm-8 offset-sm-2 register-container">
                        <form>
                            <div class="form-group">
                                <label for="txtEmail">Email address</label>
                                <input type="email" class="form-control" id="txtEmail" aria-describedby="emailHelp" placeholder="Enter email">
                                <small id="emailHelp" class="form-text text-muted">You'll use this email to login.</small>
                            </div>
                            <div class="form-group">
                                <label for="txtUsername">Username</label>
                                <input type="text" class="form-control" id="txtUsername" aria-describedby="usernameHelp" placeholder="Enter username">
                                <small id="usernameHelp" class="form-text text-muted">This is the name that will be shown in chat.</small>
                            </div>
                            <div class="form-group">
                                <label for="txtPassword">Password</label>
                                <input type="password" class="form-control" id="txtPassword" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <label for="txtPasswordConfirm">Password Confirm</label>
                                <input type="password" class="form-control" id="txtPasswordConfirm" placeholder="Password Confirm">
                            </div>
                            <input type="hidden" class="form-control" id="hidden_formname" value="register">
                            <button class="btn btn-outline-success btn-block btn-lg register">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once ('includes/footer.php');
?>

