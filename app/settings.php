<?php
$settings = [];

//display error_clear_last
$settings['displayErrorDetails'] = true;

// Database
$settings['db']         = [];
$settings['db']['host'] = 'localhost';
$settings['db']['port'] = '8888';
$settings['db']['user'] = 'root';
$settings['db']['pass'] = 'root';
$settings['db']['name'] = 'pokedex';


// Database
$container['db'] = function($container)
{
    $db = $container['settings']['db'];
    
    $pdo = new PDO('mysql:host='.$db['host'].';dbname='.$db['name'].';port='.$db['port'], $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

    // affiche les erreurs de PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
};
?>