/**
 * Created by Nick on 16-1-2017.
 */
$(document).ready(function(){
    $('.register').on('click', function(e){
        e.preventDefault();
        var username         = $('#txtUsername').val();
        var email            = $('#txtEmail').val();
        var password         = $('#txtPassword').val();
        var password_confirm = $('#txtPasswordConfirm').val();
        var hidden_formname  = $('#hidden_formname').val();

        console.log(username);
        $.ajax({
            method: 'POST',
            url: '../app/controllers/authController.php',
            data: { email: email,
                    username: username,
                    password: password,
                    password_confirm: password_confirm,
                    hidden_formname: hidden_formname
            }
        }).done(function(data){
            $('.register-container').html(data);
        });
    });

    $('.login').on('click', function(e){
        e.preventDefault();
        var email            = $('#txtEmail').val();
        var password         = $('#txtPassword').val();
        var hidden_formname  = $('#hidden_formname').val();

        $.ajax({
            method: 'POST',
            url: '../app/controllers/authController.php',
            data: { email: email,
                    password: password,
                    hidden_formname: hidden_formname
            }
        }).done(function(data){
            if(data){
                window.location.href = 'lobby.php';
            }
            console.log(data);
        });

    });
});