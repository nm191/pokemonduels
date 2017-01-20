/**
 * Created by Nick on 16-1-2017.
 */
$(document).ready(function(){
    
    $('.btn_ready').on('click', function(){
        $('.overlay').html('<p class="lead"> <i class="fa fa-spinner fa-2x" aria-hidden="true"></i> Waiting for other player to get ready.</p>');
        $('.overlay').show('slow');
        var user_id = $(this).data('user_id');
        var room_key = $(this).data('room_key');
        $.ajax({
            method: 'POST',
            url: '../app/controllers/battleRoomController.php',
            data: { formname: 'setPlayerReady', user_id: user_id, room_key: room_key}
        }).done(function(data){
            console.log(data);
        });
    });

    $('#searchPokemon').on('change', function(){
        var pokemon = $('#searchPokemon').val().toLowerCase();
        $('.pokemon-result').html('<div class="alert alert-warning" role="alert"><i class="fa fa-spinner" aria-hidden="true"></i> <strong>Loading...</strong> Please wait a moment.</div>');
        PokemonService.getPokemon(pokemon).done(function(data){
            console.log(data);
            var jsonString = JSON.stringify(data);
           $.ajax({
               method: 'POST',
               url: '../app/controllers/battleRoomController.php',
               data: {pokemon: jsonString, formname: 'getPokemonOutput'}
           }).done(function(output){
               $('.overlay').hide('slow');
               $('.pokemon-result').hide('slow');
               $('.pokemon-result').html(output);
               $('.pokemon-result').show('slow');
           });
        });

    });
});
