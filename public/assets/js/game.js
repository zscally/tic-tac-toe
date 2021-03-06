$(function(){
    var game_id = $('#gamesurface').attr('data-game-id');
    var current_moves = $('#gamesurface').attr('data-current-moves');
    var current_move = $('#gamesurface').attr('data-current-move');
    var player_one_id = $('.player_one').attr('data-player-id');
    var player_two_id = $('.player_two').attr('data-player-id');
    var current_game_status = $('#gamesurface').attr('data-game-status');
    var winner = $('#gamesurface').attr('data-game-winner');
    var player_one_name = $('#player_one_name').html();
    var player_two_name = $('#player_two_name').html();
    var board_locked = false;

    var players = {
        player_one: {
            player_name: player_one_name,
            player_id: player_one_id,
            player_value: 'X',
            current_move: false
        },
        player_two: {
            player_name: player_two_name,
            player_id: player_two_id,
            player_value: 'O',
            current_move: false
        }
    };

    checkGame(players, winner, current_game_status);
    setCurrentMove(players, current_move);
    setWhosTurn(players);
    applyCurrnetMoves(players, current_moves);
    
    $('#gamesurface').on('click', 'td', function(){
        if( $('#gamesurface').data('game-status') == 'Complete' )
            return;

        if( $('#gamesurface').data('board-lock') == true )
            return;

        var location = $(this).attr('id');
        if ($('#' + location).html().length <= 0) {
            var player = getCurrentPlayer(players);
            $('#' + location).html(player.player_value);
            setNextPlayer(players);
            saveMove(game_id, location, player.player_id, player.player_value, current_game_status, board_locked);
        }
    });

    function setWhosTurn(players) {
        $.each(players, function(player_id, player) {
            if( player.current_move == true ) {
                $('#current_move').html(player.player_value);
            }
        });
    }

    function checkGame(players, winner, current_game_status) {
        if( winner ) {
            player = getPlayerById(players, winner);
            new Noty({
                type: 'success',
                text: player.player_value + ' wins the game!!!',
                theme: 'bootstrap-v4'
            }).show();
        } else if( current_game_status == 'Complete') {
            new Noty({
                type: 'success',
                text: 'Game is a tie!!!',
                theme: 'bootstrap-v4'
            }).show();
        }

        if( current_game_status == 'Complete' )
            $('#newGameButton').show();
    }

    function applyCurrnetMoves(players, moves) {
        var total_moves = JSON.parse(moves).length;
        $.each(JSON.parse(moves), function(i, move) {
            $('#' + move.location).html(move.player_value);
            if (i === (total_moves - 1)) {
                var setmove = 'X';
                if(move.player_value == 'X') {
                    setmove = 'O';
                }
                $('#gamesurface').data('current_move', setmove);
                setNextPlayer(players);
            }
        });
    }

    function getPlayerById(players, player_id) {
        var selected_player = null;
        $.each(players, function(key, player) {
            if( player.player_id == player_id )
            {
                selected_player = player;
            }
        });
        return selected_player;
    }

    function getCurrentPlayer(players) {
        var current_player = null
        $.each(players, function(player_id, player) {
            if( player.current_move == true )
            {
                current_player = player;
            }
        });
        return current_player;
    }

    function saveMove(game_id, location, player_id, player_value, current_game_status, board_locked) {
        var post_data = {
            'game_id': game_id,
            'location': location,
            'player_id': player_id,
            'player_value': player_value
        };

        $('#gamesurface').data('board-lock', 'true');

        $.when( $.post( "/game/saveMove", post_data,  function(result) {
            if(result.status == 'error') {
                $('#' + location).html('');
                $.each(result.messages, function(index, message){
                    new Noty({
                        type: 'error',
                        text: message,
                        theme: 'bootstrap-v4',
                        timeout: 5000,
                        progressBar: true
                    }).show();
                });
            } else if(result.status == 'Complete') {
                $('#gamesurface').data('game-status','Complete');
                $('#newGameButton').show();

                if(result.winner == 'X') {
                    var score = $('#player_one_score').data('score');
                    $('#player_one_score').html(score + 1);
                } else if(result.winner == 'O') {
                    var score = $('#player_two_score').data('score');
                    $('#player_two_score').html(score + 1);
                } else {
                    var score = $('#tie_score').data('score');
                    $('#tie_score').html(score + 1);
                }

                var message = result.winner == 'tie' ? 'Game is a tie!!!' :
                result.winner + ' wins the game!!!'
                new Noty({
                    type: 'success',
                    text: message,
                    theme: 'bootstrap-v4'
                }).show();
            }
        })).done(function( a1, a2 ) {
            $('#gamesurface').data('board-lock', 'false');
        });
    }

    function setNextPlayer(players) {
        console.log(players);
        $.each(players, function(player_id, player) {
            if( player.current_move == true )
            {
                player.current_move = false;
            } else {
                player.current_move = true;
            }
        });
        setWhosTurn(players);
    }

    function setCurrentMove(players, current_move) {
        $.each(players, function(player_id, player) {
            if( current_move == player.player_id )
            {
                players[player_id].current_move = true;
            } else {
                players[player_id].current_move = false;
            }
        });
    }
});
