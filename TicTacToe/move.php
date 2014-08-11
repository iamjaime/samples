<?php
//lets create a session....
session_start();
//init the class....
include 'includes/tictactoe.class.php';
/*
 * Settings can be the following....
 * 1.) player vs player
 * 2.) player vs cpu
 * 3.) cpu vs player
 */
$settings = array(
    'p1' => "player",
    'p2' => "player"
);

$ttt = new Tictactoe($settings);

//Lets get the HTTP REQUEST TYPE and handle it....
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    //lets filter the box value....(trust no one :D )
    $box = filter_input(INPUT_GET, 'box', FILTER_SANITIZE_NUMBER_INT);
    $count = filter_input(INPUT_GET, 'thecount', FILTER_SANITIZE_NUMBER_INT);
    $reset = filter_input(INPUT_GET, "resetBoard", FILTER_SANITIZE_NUMBER_INT);

    if($reset){
        $data = json_encode(array(
            'board' => $ttt->gameStart(true),
            'session' => $_SESSION,
            'count' => $count
                ));
        echo $data;
        exit();
    }
    
    
    if ($ttt->isSlotTaken($box)) {
        //the slot is already taken!
        exit();
    }

    //if CPU goes first.....
    if ($ttt->cpuVsPlayer()) {
        if ($count == 1) {
            $count +=1;
        }
        //lets make the move....
        $users_move = $ttt->makeMove($count, $box);
        $count +=1;
        
        $cpu_move = $ttt->makeMove($count);
    }

    //if player goes first...
    if ($ttt->playerVsCpu()) {
        //lets make the move....
        $users_move = $ttt->makeMove($count, $box);

        if ($count != 9) {
            $count += 1;
            $cpu_move = $ttt->makeMove($count);
        }
    }

    //if player vs player
    if ($ttt->playerVsPlayer()) {
        //lets make the move....
        $users_move = $ttt->makeMove($count, $box);
    }
    
        
    echo json_encode(array(
        'board' => $ttt->checkBoard(), //incase we have winner or draw
        'count' => $count, //the new count (incase we have cpu vs human or human vs cpu )
        'moves' => array('user_move' => $users_move, 'cpu_move' => $cpu_move),
        'rules' => $ttt->rules
    ));
}