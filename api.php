<?php

include './vendor/predis/predis/src/Autoloader.php';

Predis\Autoloader::register();


// 'a' is the kind of search to perform on the database, 'q' is the query

$predis = new Predis\Client();

$predis->connect();

$api = new API($predis);

if(isset($_GET['a'], $_GET['q'])) {
    switch ($_GET['a']) {
        case 'team':
            $api->respondTeam($_GET['q']);
            break;
        case 'player':
            $api->respondPlayer($_GET['q']);
            break;
        default :
             $error = array(
        "cause" => "An error has occurred",
        "status" => "ERROR"
        );
        echo json_encode($error);
        break;
    }
    
} else {
    $error = array(
      "cause" => "An error has occurred",
      "status" => "ERROR"
    );
    
    echo json_encode($error);
}


class API {
   
    private static $predis = null;
    
    function __construct($predis) {
        $this->predis = $predis;
    }
    
    public function respondPlayer($player) {
        if($this->predis->hexists("players", $player) == false) {
            $error = array("cause" => "Player does not exist!", "status" => "ERROR");
            echo json_encode($error);
            return;
        } else {
           $response = array("gold" => $this->predis->hget("players", $player));
           echo json_encode($response);
           return;
        }
    }
    
    public function respondTeam($team) {
       if($this->predis->exists("team_" . $team) == false) {
           $error = array("cause" => "Team does not exist!", "status" => "ERROR");
           echo json_encode($error);
       } else {
           $response = array(
               "leader" => $this->predis->hget("team_" . $team, "leader"),
               "members" => empty($this->predis->hget("team_" . $team, "members")) ? [] : $this->predis->hmget("team_" . $team, "members"),
               "managers" => empty($this->predis->hget("team_" . $team, "managers")) ? [] : $this->predis->hmget("team_" . $team, "managers")
           );
           echo json_encode($response);
           
       }
    }
}
