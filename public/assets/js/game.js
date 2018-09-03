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
    applyCurrnetMoves(current_moves);
    setCurrentMove(players, current_move);

    $('#gamesurface').on('click', 'td', function(){
        if( current_game_status == 'Complete' )
            return;

        var location = $(this).attr('id');
        if ($('#' + location).html().length <= 0) {
            var player = getCurrentPlayer(players);
            $('#' + location).html(player.player_value);
            setNextPlayer(players);
            saveMove(game_id, location, player.player_id, player.player_value, current_game_status);
        }
    });
});

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
}

function applyCurrnetMoves(moves) {
    $.each(JSON.parse(moves), function(i, move) {
        $('#' + move.location).html(move.player_value);
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

function saveMove(game_id, location, player_id, player_value, current_game_status) {
    var post_data = {
        'game_id': game_id,
        'location': location,
        'player_id': player_id,
        'player_value': player_value
    };

    $.post( "/game/saveMove", post_data,  function(result) {
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
            current_game_status = 'Complete';
            var message = result.winner == 'tie' ? 'Game is a tie!!!' :
            result.winner + ' wins the game!!!'
            new Noty({
                type: 'success',
                text: message,
                theme: 'bootstrap-v4'
            }).show();
        }
    });
}

function setNextPlayer(players) {
    $.each(players, function(player_id, player) {
        if( player.current_move == true )
        {
            player.current_move = false;
        } else {
            player.current_move = true;
        }
    });
}

function setCurrentMove(players, current_move) {
    $.each(players, function(player_id, player) {
        if( current_move == player.player_id )
        {
            players[player_id].current_move = true;
        }
    });
}
