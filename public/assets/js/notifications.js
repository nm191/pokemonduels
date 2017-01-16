/**
 * Created by Gebruiker on 16-1-2017.
 */
$(document).ready(function(){

    $('.challenge').on('click', function(){
        var user_id = $(this).data('id');
        var formname = 'sendChallenge';
        $.ajax({
            method: 'POST',
            url: '../app/controllers/notificationController.php',
            data: {user_id: user_id, formname: formname}
        }).done(function(data){
            window.location.href = 'waiting_room.php?room_key='+data;
        });
    });

    function checkForNotification(){
        var user_id = $('#user_id').val();
        var formname = 'checkNotifications';
        return $.ajax({
            method: 'POST',
            url: '../app/controllers/notificationController.php',
            data: {user_id: user_id, formname: formname}
        }).done(function(data){
            if(data){
                $('.notification').html(data);
                $('.notification').show('slow');
            }
        });
    }

    setInterval(function(){
        if($('.notification').display != 'block'){
            checkForNotification();
        }


    }, 5000);
});
