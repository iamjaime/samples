(function($) {

    $.fn.tictactoe = function(timeout) {

        $.ajax({
            url: "/tictactoe/move.php",
            dataType: "json",
            data: {
                resetBoard: 1
            },
            success: function(data) {
                //console.log(data);
                setTimeout(function() {
                    $(".board").html(data.board);
                }, timeout);
            }
        });
    };

    $.fn.clearBoard = function(timeout) {

        setTimeout(function() {
            //reset after 2 seconds
            $(".board_cell").removeClass("winner");
            $(".board_cell").html("");
        }, timeout);

    };

    $.fn.gameDraw = function(timeout) {

        setTimeout(function() {
            //reset after 2 seconds
            $(".board_cell").removeClass("draw");
            count = 0; //reset the count
            $(".board_cell").html("");
        }, timeout);

    };

}(jQuery));