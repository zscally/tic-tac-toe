# Tic Tac Toe, A simple Game.
This is a simple tic tac toe game.

## Demo
[tictactoe.2dq.us](http://tictactoe.2dq.us)

## Tic Tac Toe
Tic-tac-toe (also known as noughts and crosses or Xs and Os) is a paper-and-pencil game for two players, X and O, who take turns marking the spaces in a 3×3 grid. The player who succeeds in placing three of their marks in a horizontal, vertical, or diagonal row wins the game.

Source [Wiki](https://en.wikipedia.org/wiki/Tic-tac-toe)

## Requirements.

- A session to allow players to play multiple games.
- Both players can enter a nickname at the start of a session.
- First player to be chosen randomly at the start of each game.
- First player to be assigned X, and the second O.
- Each game to follow the standard rules of Tic-Tac-Toe.
- Each time a player wins a game, they are awarded a point.
- Each player’s points displayed alongside their nickname.
- If the browser is closed, we can reopen it and resume playing where we left off (including game state, player names, and scores).

## Installation
### Please make sure [Comoser](https://getcomposer.org/download/) and [npm](https://nodejs.org/en/) are installed.
 - sh build.sh (this will fetch and build all the require libs)
 - run "mysql tictactoe < schema.sql" granted you have mysql permissions.
 - modify bootstrap/config.php to meet your database settings.
 - setup your vhost

## Version
`v1.0`

## Usage
 - PHP 7.1+
 - MYSQL (or mariaDB)
 - HTTP webserver (apache is what I use)

## Contributing
  1. Star & Frok it!
  2. Create your feature branch: `git checkout -b <branch_name>`
  3. Commit your changes: `git commit -am 'some message'`
  4. Push to the branch: `git push origin <branch_name>`
  5. Submit a pull request.
  6. Review and submit comments on Pull Request.
