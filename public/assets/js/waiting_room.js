/**
 * Created by Gebruiker on 16-1-2017.
 */
$(document).ready(function(){

    function checkBattleRoomStatus(room_key){
        var formname = 'checkBattleRoomStatus';
        return $.ajax({
            method: 'POST',
            url: '../app/controllers/battleRoomController.php',
            data: {room_key: room_key, formname: formname}
        }).done(function(status){
            if(status == 'picking teams'){
                $('.lead').toggle('slow');
                $('.lead').html('Both players are ready... Preparing team picking...');
                $('.lead').toggle('slow');
                setTimeout(function(){
                    window.location.href = 'team_picking.php?room_key='+room_key;
                }, 3000);
            }
        });
    }


    function checkNotificationStatus(room_key){
        var formname = 'checkNotificationStatus';
        return $.ajax({
            method: 'POST',
            url: '../app/controllers/notificationController.php',
            data: {room_key: room_key, formname: formname}
        }).done(function(status){
            if(status == 'denied'){
                $('.lead').toggle('slow');
                $('.lead').html('The other user has declined your challenge! You will be redirected to the lobby..');
                $('.lead').toggle('slow');
                setTimeout(function(){
                    window.location.href = 'lobby.php';
                }, 3000);
            }
        });
    }

    function getParameterByName(name, url) {
        if (!url) {
            url = window.location.href;
        }
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    setInterval(function(){
        var room_key = getParameterByName('room_key');
        checkNotificationStatus(room_key);
        checkBattleRoomStatus(room_key);
    }, 5000);
});