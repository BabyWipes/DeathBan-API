<?php

require './vendor/predis/predis/src/Autoloader.php';

Predis\Autoloader::register();


// 'a' is the kind of search to perform on the database, 'q' is the query
if(isset($_GET['a'], $_GET['q'])) {
    switch ($_GET['a']) {
        case 'team':
            show_team($_GET['q']);
            break;
        case 'player':
            show_player($_GET['q']);
            break;
        default :
             $error = array(
         "message" => "An error has occurred",
        "status" => "ERROR"
        );
        echo json_encode($error);
        break;
    }
    
} else {
    $error = array(
      "message" => "An error has occurred",
      "status" => "ERROR"
    );
    
    echo json_encode($error);
}
