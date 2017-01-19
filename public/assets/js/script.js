/**
 * Created by Nick on 16-1-2017.
 */
$(document).ready(function(){
    
    $('.btn_ready').on('click', function(){
        $('.pick_team_container').hide('slow');
        $('.pick_team_container').html('<p class="lead"> Waiting for other player to get ready.</p>');
        $('.pick_team_container').show('slow');
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
               $('.pokemon-result').hide('slow');
               $('.pokemon-result').html(output);
               $('.pokemon-result').show('slow');
           });
        });

    });
});
