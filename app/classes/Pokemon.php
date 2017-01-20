<?php

/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 17-1-2017
 * Time: 11:59
 */
class Pokemon
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getMoves($pokemon){
        $return_ar = $button_ar = array();
        foreach($pokemon->moves as $moves){
            if(count($button_ar) > 3) {
                continue;
            }
            $button_ar[] = '<button class="btn btn-primary btn_move" data-url="'.$moves->move->url.'">'.$moves->move->name.'</button>';
        }
        $return_ar[] = '<div class="battle_moves">';
        $return_ar[] = implode('', $button_ar);
        $return_ar[] = '</div>';
        return implode('', $return_ar);
    }

    public function getHP($pokemon){
        if(!$pokemon){
            return false;
        }

        foreach($pokemon->stats as $stat){
            if($stat->stat->name == 'hp'){
                return $stat->base_stat*10;

            }
        }
        return false;
    }

    private function setHP($pokemon, $hp, $team){
        foreach($pokemon->stats as $stat){
            if($stat->stat->name == 'hp'){
                $stat->base_stat = $hp;
                $_SESSION[$team][0] = $pokemon;
                return $_SESSION[$team][0]->stats['5']->base_stat;
            }
        }
    }

    public function getSpeed($stats){
        if(!$stats){
            return false;
        }
        foreach($stats as $stat){
            if($stat->stat->name == 'speed'){
                return $stat->base_stat;
            }
        }
        return false;
    }

    public function doMove($move, $pokemon, $team){
        $hp  = ($this->getHP($pokemon) - $move->power) / 5 ;
        $field_name = 'move_done_by_'.$_SESSION['player'];
        if($team == 'team'){
            $this->setHP($pokemon, $hp, $team);
            $this->setMoveDone($move, $field_name, 'yes');
        }
        $this->setHP($pokemon, $hp, $team);
        $this->setMoveDone($move, $field_name, 'yes');
        return $move->power;
    }

    private function setMoveDone($move, $field_name, $field_value){
        $sql = 'UPDATE selected_moves SET '.$field_name.' = :field_value WHERE id = :move_id';
        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindParam(":field_value", $field_value);
        $stmt->bindParam(":move_id", $move->id);
        return $stmt->execute();
    }

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
                    $('.overlay').html('<p class="lead"><i class="fa fa-spinner fa-2x" aria-hidden="true"></i> Selecting Pokemon</p>');
                    $('.overlay').show('slow');
                    PokemonService.getPokemon($(this).data('pokemon_id')).done(function(data){
                        var jsonString = JSON.stringify(data);
                        if(PokemonService.team.length < 5){
                            $.ajax({
                                method: 'POST',
                                url: '../app/controllers/battleRoomController.php',
                                data: { formname: 'getTeam', pokemon: jsonString}
                            }).done(function(team_output){
                                $('.overlay').hide('slow');
                                $('.pokemons').html(team_output);
                            });
                        }else{
                            console.log('Already 1 pokemon in team');
                        }

                    });
                });
            });
        </script>
    <?php
        return ob_get_clean();
    }

}