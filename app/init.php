<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 16-1-2017
 * Time: 11:29
 */
ob_start();

session_start();

spl_autoload_register( function($class){
    if(file_exists(__DIR__.'/classes/'.$class.'.php')){
        require_once (__DIR__.'/classes/'.$class.'.php');
    }
});

$user = new User();
