/**
 * Created by Nick van Meel on 19-1-2017.
 */

$(document).ready(function(){
    $('.btn_move').on('click', function(){
        $('.battle_moves').hide('slow');
        $('.battle_moves_text').html('<p class="lead"> Waiting for other player to select a move</p>');
        $('.battle_moves_text').show('slow');
        var url = $(this).data('url');
        PokemonService.getMove(url).done(function(data){
            var room_key = getParameterByName('room_key');
            var move = JSON.stringify(data);
            $.ajax({
                url:'../app/controllers/battleRoomController.php',
                method: 'POST',
                data: {room_key: room_key, move: move, formname: 'selectedMove'}
            }).done(function(data){
                console.log(data);
            });
        });

    });


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

    function checkBattleRoomStatus(room_key){
        var formname = 'checkBattleRoomStatus';
        return $.ajax({
            method: 'POST',
            url: '../app/controllers/battleRoomController.php',
            data: {room_key: room_key, formname: formname}
        }).done(function(status){
            if(status == 'do moves'){
               $.ajax({
                   method: 'POST',
                   url: '../app/controllers/battleRoomController.php',
                   data: { room_key: room_key, formname: 'doMoves'}
               }).done(function(data){
                   if(data){
                       data = $.parseJSON(data);
                       var player = $('.'+data[0]);
                       var this_player = false;
                       if(player.hasClass('this_player')){
                           var bar = $('.this_player .battle_pokemon .progress .progress-bar');
                           var hp = $('.this_player .battle_pokemon .progress .progress-bar .current_hp');
                           this_player = true;
                       }else{
                           var bar = $('.opposite_player .battle_pokemon .progress .progress-bar');
                           var hp = $('.opposite_player .battle_pokemon .progress .progress-bar .current_hp');
                       }
                       var val_now = bar.attr('aria-valuenow')-data[1];
                       if(val_now <= 0){
                           if(this_player){
                               $('.overlay').html('<div class="container text-sm-center"><h1 class="display-1"> You died!</h1><p class="lead">Better luck next time!</p><a class="btn btn-outline-primary" href="lobby.php">Go back to lobby</a></div>');
                           }else{
                               $('.overlay').html('<div class="container text-sm-center"><h1 class="display-1"> You Won!</h1><p class="lead">Well done!</p><a class="btn btn-outline-primary" href="lobby.php">Go back to lobby</a></div>');
                           }
                           $.ajax({
                               
                           });
                           $('.overlay').show('slow');
                       }
                       var val_max = bar.attr('aria-valuemax');
                       var width  = val_now / (val_max / 100);
                       bar.width(width+'%');
                       bar.attr('aria-valuenow', val_now);
                       hp.html(val_now);
                   }

               });
            }
            if(status == 'picking moves'){
                var battle_moves = $('.battle_moves');
                if(battle_moves.is(":visible")){

                }else{
                    battle_moves.show('slow');
                    $('.battle_moves_text').hide('slow');
                }

            }
        });
    }

    setInterval(function(){
        var room_key = getParameterByName('room_key');
        checkBattleRoomStatus(room_key);
    }, 5000);

});