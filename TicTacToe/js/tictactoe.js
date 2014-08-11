//on document ready :)
$(document).ready(function() {

    var count = 0;

    $(".board_cell").live("click", function() {
        
        count++;

        //lets get the target that was clicked on...
        var cell = $(this).attr("data-target");

        //now lets do some ajax :)
        $.ajax({
            url: "/tictactoe/move.php",
            dataType: "json",
            data: {
                box: cell,
                thecount: count
            },
            success: function(info) {
                if (info)
                {
                    //console.log(info.board);
                    console.log(info);
                    
                    //lets set the new count...
                    count = info.count;
                    
                    //show the move on the board.
                    if(info.moves.cpu_move){
                        $(".board_cell[data-target=" + info.moves.cpu_move.slot + "]").html("<strong>" + info.moves.cpu_move.move + "</strong>");
                    }
                    if(info.moves.user_move){
                        $(".board_cell[data-target=" + info.moves.user_move.slot + "]").html("<strong>" + info.moves.user_move.move + "</strong>");
                    }
                    //lets make a nice winning effect :)
                    if (info.board.combo) {
                        var combo_count = info.board.combo.length;
                        for (var i = 0; i < combo_count; i++) {
                            $(".board_cell[data-target=" + info.board.combo[i] + "]").addClass("winner");
                        }
                        //lets make a reset effect :)
                        if(info.rules.p1 === "cpu")
                        {
                            $(".board").tictactoe(2000);
                        } 
                        else{
                            count = 0; //reset the count
                            $(this).clearBoard(2000);
                        }
                       
                    }
                    //we have a draw!
                    if (info.board.draw) {
                        //lets make a draw effect then reset the game...
                        $(".board_cell").addClass("draw");
                        //now lets reset the game....
                        $(".board").gameDraw(2000);
                    }

                }
            }
        });

    });

});