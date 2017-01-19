/**
 * Created by Nick on 16-1-2017.
 */
$(document).ready(function(){
    
    $('.btn_ready').on('click', function(){
        var user_id = $(this).data('user_id');
        $.ajax({
            method: 'POST',
            url: '../app/controllers/battleRoomController.php',
            data: { formname: 'setPlayerReady', user_id: user_id}
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
