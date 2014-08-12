<?php

/**
 * Tic Tac Toe - Code Sample 
 * 
 * @author Jaime Bernal <jaime@iamjaime.com>
 * @version 1.0
 */
class Tictactoe {

    /**
     *
     * @var int the number of players in this game.
     */
    public $players;

    Const AI = "cpu";
    Const HUMAN = "player";

    /**
     *
     * @var int the tic-tac-toe board slots
     */
    protected $board = 9;

    /**
     *
     * @var array the game settings/game options. 
     */
    public $rules = array(
        'p1' => self::AI, //default value (player 1 will be the computer :D )
        'p2' => self::HUMAN,  //default value (player 2 will always be a human unless specified otherwise)
        'cpu' => true //default computer goes first!
    );

    /**
     * 
     * @param array $settings an array of the game types.
     */
    function __construct($settings = NULL) {
        if ($settings !== NULL) {
            $this->rules = $this->settings($settings);
        }
    }

    /**
     * 
     * @param int $code The Error Code 
     * @param string $method The Method That Triggered The Error.
     * @param string $msg The Error Message ( generally a description of what went wrong. )
     * @return array The error
     */
    public function error($code, $method, $msg) {

        $error['code'] = $code; //Error CODE
        $error['method'] = $method; //The method that triggered the Error.
        $error['msg'] = $msg; //The Error Message

        return $error;
    }

    /**
     * 
     * @param array $settings an array of game settings/game options
     * @return array
     */
    protected function settings($settings) {

        if (!is_array($settings)) {
            return $this->error(400, __FUNCTION__, 'The Settings parameter must be an array!');
        }

        $this->rules = $settings;

        return $settings;
    }

    /**
     * 
     * @param BOOL $cpu does the cpu go first?
     * @return string the board HTML content
     */
    protected function makeBoard($cpu = FALSE) {

        $make_move = array(); //default

        if ($cpu) {
            $count = 1;
            $make_move = $this->makeMove($count);
        }

        $str = '<div class="board">';
        for ($x = 0; $x < $this->board; $x++):
            $str .= '<div class="board_cell" data-target="' . $x . '">';
            if ($x == $make_move['slot'] && $x != 0) {
                $str .= '<strong>' . $make_move['move'] . '</strong>';
            }
            $str .= '</div>';
        endfor;
        $str .= '</div>';

        return $str;
    }

    /**
     * 
     * @param int $count the move count
     * @param int $slot the board position
     * @return array the move and the slot that it belongs in.
     */
    public function makeMove($count, $slot = NULL) {
        //move handler...
        //lets make the random slot...
        if ($slot == NULL) {
            do {
                $rand = mt_rand(0, 8);
            } while ($this->isSlotTaken($rand));

            //after we find the slot that is available lets set it...
            $slot = $rand; //now we proceed :)
        }
        if ($count % 2 == 0) {
            $move = "O";
            $_SESSION['board'][$count]['value'] = $move;
            $_SESSION['board'][$count]['position'] = (int) $slot;
        } else {
            $move = "X";
            $_SESSION['board'][$count]['value'] = $move;
            $_SESSION['board'][$count]['position'] = (int) $slot;
        }

        return array('move' => $move, 'slot' => (int) $slot);
    }

    /**
     * 
     * @param int $slot the tic-tac-toe slot that we want to check
     * @return boolean if this slot is taken or not.
     */
    public function isSlotTaken($slot) {
        foreach ($_SESSION['board'] as $board) {
            if ($board['position'] == $slot) {
                return true; //the slot is already used!
            }
        }
        return false;
    }

    /**
     * 
     * @return string the board
     */
    public function gameStart() {
        //lets kill the previous session....
        $this->gameOver();
        $cpu = $this->rules['cpu'];
        return $this->makeBoard($cpu);
    }

    /**
     * 
     * @return array the current board positions
     */
    public function checkBoard() {
        $data_o = array();
        $data_x = array();

        foreach ($_SESSION['board'] as $board) {
            //lets get the value...
            if ($board['value'] == "X") {
                $data_x[] = $board['position'];
            }

            if ($board['value'] == "O") {
                $data_o[] = $board['position'];
            }
        }

        $winner = $this->isWinner($data_x, $data_o);

        return $winner;
    }

    /**
     * 
     * @return array a multi-dimentional array of the winning combinations
     */
    protected function winningCombinations() {
        //0,1,2
        //3,4,5
        //6,7,8
        //x-axis
        $combo[] = array(0, 1, 2);
        $combo[] = array(3, 4, 5);
        $combo[] = array(6, 7, 8);

        //diagnol
        $combo[] = array(6, 4, 2);
        $combo[] = array(8, 4, 0);

        //y-axis
        $combo[] = array(0, 3, 6);
        $combo[] = array(1, 4, 7);
        $combo[] = array(2, 5, 8);

        return $combo;
    }

    /**
     * 
     * @param array $data_x an array of all the X positions on the board.
     * @param array $data_o an array of all the O positions on the board.
     * @return boolean do we have a draw?
     */
    private function isDraw($data_x, $data_o) {

        $data = array_merge($data_x, $data_o);
        if (count($data) >= $this->board) {
            return true;
        }
        return false;
    }

    /**
     * 
     * @param array $data_x an array of all the X positions on the board.
     * @param array $data_o an array of all the O positions on the board.
     * @return array an array with the winning player and board positions.
     */
    private function isWinner($data_x, $data_o) {

        $combinations = $this->winningCombinations();

        foreach ($combinations as $combo) {
            if (count(array_intersect($combo, $data_x)) == count($combo)) {
                //we have a winner.... ( player X )
                $this->gameOver(); //reset the session!
                $output = array('winner' => "X", 'combo' => $combo);

                return $output;
            }
            if (count(array_intersect($combo, $data_o)) == count($combo)) {
                //we have a winner.... ( player O )
                $this->gameOver(); //reset the session!
                $output = array('winner' => "O", 'combo' => $combo);

                return $output;
            }
        }

        //lets check if we have a draw....
        if ($this->isDraw($data_x, $data_o)) {
            $this->gameOver();
            $output = array('draw' => true);
            return $output;
        }

        //testing...
        //$data = array_merge($data_x, $data_o);

        return false;
    }

    /**
     * 
     * @return void destroys the session
     */
    public function gameOver() {
        session_unset($_SESSION['board']);
        session_destroy($_SESSION['board']);
        return;
    }

    /**
     * 
     * @return boolean do we have player vs player?
     */
    public function playerVsPlayer() {
        if ($this->rules['p1'] === self::HUMAN && $this->rules['p2'] === self::HUMAN) {
            return true;
        }
        return false;
    }

    /**
     * 
     * @return boolean do we have player vs cpu?
     */
    public function playerVsCpu() {
        if ($this->rules['p1'] === self::HUMAN && $this->rules['p2'] === self::AI) {
            return true;
        }
        return false;
    }

    /**
     * 
     * @return boolean do we have cpu vs player?
     */
    public function cpuVsPlayer() {
        if ($this->rules['p1'] === self::AI && $this->rules['p2'] === self::HUMAN) {
            return true;
        }
        return false;
    }

}
