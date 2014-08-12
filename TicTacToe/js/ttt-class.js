"use strict";

function Tictactoe(settings, boardClass, boardCellClass, timeout, ajaxURL, ajaxLoaderClass) {
    this.settings = settings || {
        p1: "player",
        p2: "player",
        cpu : false //does cpu go first?
    };
    this.boardClass = boardClass || ".board"; //the board class
    this.boardCellClass = boardCellClass || ".board_cell"; //the board cell class
    this.timeout = timeout || 2000; //default time for fading out the board
    this.ajaxURL = ajaxURL || "/tictactoe/move.php"; //the ajax URL that we will use
    this.ajaxLoaderClass = ajaxLoaderClass || 'loading-bg'; //the ajax loader. (used before we send request)
   
}

Tictactoe.prototype.newGame = function() {

var ajaxURL = this.ajaxURL;
var boardCellClass = this.boardCellClass;
var ajaxLoaderClass = this.ajaxLoaderClass;
var settings = this.settings;
var count = 0;
var that = this;
    $(boardCellClass).live("click", function() {
        
        var cell = $(this).attr("data-target");
        count++;
        
        $.ajax({
            url: ajaxURL,
            dataType: "json",
            data: {
                box: cell,
                thecount: count,
                settings: settings
            },
            beforeSend: function() {
                console.log("beforeSend success!");
                $(boardCellClass + "[data-target=" + cell + "]").addClass(ajaxLoaderClass);
            },
            success: function(info) {
                
                $(boardCellClass + "[data-target=" + cell + "]").removeClass(ajaxLoaderClass);

                //lets set the new count...
                count = info.count;

                //show the move on the board.
                if (info.moves.cpu_move) {
                    $(boardCellClass + "[data-target=" + info.moves.cpu_move.slot + "]").html("<strong>" + info.moves.cpu_move.move + "</strong>");
                }
                if (info.moves.user_move) {
                    $(boardCellClass + "[data-target=" + info.moves.user_move.slot + "]").html("<strong>" + info.moves.user_move.move + "</strong>");
                }
                //lets make a nice winning effect :)
                if (info.board.combo) {
                    var combo_count = info.board.combo.length;
                    for (var i = 0; i < combo_count; i++) {
                        $(boardCellClass + "[data-target=" + info.board.combo[i] + "]").addClass("winner");
                    }
                    //lets make a reset effect :)
                    count = 0;
                    that.clearGame();
                }
                 
                //if we have a draw!
                if (info.board.draw) {
                    $(boardCellClass).addClass("draw");
                    count = 0;
                    that.clearGame();
                }
            }
        });

    });

};

Tictactoe.prototype.clearGame = function() {

var boardCellClass = this.boardCellClass;
var timeout = this.timeout;
var cpu = this.settings.cpu;

    if (cpu !== false) {
        this.resetGame();
    } else {
        setTimeout(function() {
            //reset after 2 seconds
            $(boardCellClass).removeClass("winner");
            $(boardCellClass).removeClass("draw");
            $(boardCellClass).html("");
        }, timeout);
    }
};

Tictactoe.prototype.resetGame = function() {
var ajaxURL = this.ajaxURL;
var boardClass = this.boardClass;
var timeout = this.timeout;

    $.ajax({
        url: ajaxURL,
        dataType: "json",
        data: {
            resetBoard: 1
        },
        success: function(data) {
            //console.log(data);
            setTimeout(function() {
                $(boardClass).html(data.board);
            }, timeout);
        }
    });
};