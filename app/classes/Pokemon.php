<?php

/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 17-1-2017
 * Time: 11:59
 */
class Pokemon
{

    public function getPokemonOutput($pokemon){
        $return_ar = array();
        $return_ar[] = '<h3 class="display-3">'.$pokemon->name.'</h3>';
        $return_ar[] = '<img src="'.$pokemon->sprites->front_default.'" id="pokemonImg">';
        $return_ar[] = '<div class="moves">';
        $return_ar[] = '<h3 class="display-4">Moves</h3>';
        $return_ar[] = '<ul class="list-group">';
        $return_ar[] = '<li class="list-group-item">'.$pokemon->moves[0]->move->name.'</li>';
        $return_ar[] = '<li class="list-group-item">'.$pokemon->moves[1]->move->name.'</li>';
        $return_ar[] = '<li class="list-group-item">'.$pokemon->moves[2]->move->name.'</li>';
        $return_ar[] = '<li class="list-group-item">'.$pokemon->moves[3]->move->name.'</li>';
        $return_ar[] = '</ul>';
        $return_ar[] = '</div>';
        $return_ar[] = '<button class="pick_pokemon btn btn-block btn-lg btn-outline-primary" data-pokemon_id="'.$pokemon->id.'">Pick this Pokemon</button>';
        return implode('', $return_ar).$this->getJquery();
    }

    public function getTeam($pokemon_ar){
        if(!$pokemon_ar){
            return '<p class="lead">You haven\'t selected any pokemon!</p>';
        }

        $return_ar = array();
        foreach($pokemon_ar as $key => $pokemon){
            $tmp_pokemon = array();

            $tmp_pokemon[] = '<div class="pokemon">';
            $tmp_pokemon[] = '<button class="btn btn-sm btn-outline-danger btn-block btn_remove" data-key="'.$key.'"><i class="fa fa-times" aria-hidden="true"></i> Remove</button>';
            $tmp_pokemon[] = '<img src="'.$pokemon->sprites->front_default.'" alt="" class="pokemon_img">';
            $tmp_pokemon[] = '<p class="lead text-sm-center">'.$pokemon->name.'</p>';
            $tmp_pokemon[] = '</div>';
            $return_ar[] = implode('', $tmp_pokemon);
        }

        return implode('', $return_ar).$this->getJquery();
    }

    private function getJquery(){
        ob_start();
        ?>
        <script>
            $(document).ready(function(){
                $('.btn_remove').on('click', function(){
                    var key = $(this).data('key');

                    $.ajax({
                        method: 'POST',
                        url: '../app/controllers/battleRoomController.php',
                        data: {formname: 'removeFromTeam', key: key}
                    }).done(function(data){
                        $('.pokemons').html(data);
                    });
                });

                $('.pick_pokemon').on('click', function(){
                    PokemonService.getPokemon($(this).data('pokemon_id')).done(function(data){
                        var jsonString = JSON.stringify(data);
                        if(PokemonService.team.length < 5){
                            $.ajax({
                                method: 'POST',
                                url: '../app/controllers/battleRoomController.php',
                                data: { formname: 'getTeam', pokemon: jsonString}
                            }).done(function(team_output){
                                $('.pokemons').html(team_output);
                            });
                        }else{
                            console.log('Already 5 pokemon in team');
                        }

                    });
                });
            });
        </script>
    <?php
        return ob_get_clean();
    }

}