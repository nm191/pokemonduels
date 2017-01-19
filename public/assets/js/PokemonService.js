/**
 * Created by Nick on 16-1-2017.
 */
var PokemonService = {
    url: 'http://pokeapi.co/api/v2/',
    team: [],

    getPokemon: function(pokemon){
        return $.ajax({
            url: PokemonService.url + 'pokemon/' + pokemon
        });
    }
};
