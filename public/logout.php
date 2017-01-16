<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 16-1-2017
 * Time: 15:49
 */

require_once ('../app/init.php');

$user->logOut($_SESSION['user_info']->id);

