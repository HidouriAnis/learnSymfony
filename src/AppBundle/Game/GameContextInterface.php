<?php

namespace AppBundle\Game;

interface GameContextInterface
{
    /**
     * Resets the current game context.
     */
    public function reset();

    /**
     * Creates a new Game instance.
     *
     * @param string $word The word to be guessed
     *
     * @return Game
     */
    public function newGame($word);

    /**
     * Loads an existing game.
     *
     * @return Game
     */
    public function loadGame();

    /**
     * Saves the provided game.
     *
     * @param Game $game The game to save
     */
    public function save(Game $game);
}
