<?php session_start(); ?>
<?php include("includes/tictactoe.class.php"); ?>
<?php $ttt = new Tictactoe(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Tic Tac Toe</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <!-- Optional theme -->
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
        <link href="style.css" rel="stylesheet" type="text/css">
        <!--Lets Include CDN Jquery-->
        <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
        <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
        <!--The Tic Tac Toe Script -->
        <script src="js/tictactoe.js" type="text/javascript"></script>
        <script src="js/ttt.js" type="text/javascript"></script>
    </head>
    <body>
        <section id="game">
            <h4>TicTacToe - By Jaime Bernal</h4>
            <?php 
                $cpu_goes_first = FALSE;
                echo $ttt->gameStart($cpu_goes_first);
            ?>
        </section>
        <!-- Latest compiled and minified JavaScript -->
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    </body>
</html>
