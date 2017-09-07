<?php

namespace AppBundle\Game;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GameContext implements GameContextInterface
{
    private $session;

    /**
     * GameContext constructor.
     *
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @inheritdoc
     */
    public function reset()
    {
        $this->session->set('hangman', []);
    }

    /**
     * @inheritdoc
     */
    public function newGame($word)
    {
        return new Game($word);
    }

    /**
     * @inheritdoc
     */
    public function loadGame()
    {
        $data = $this->session->get('hangman');

        if (!$data) {
            return false;
        }

        return new Game(
            $data['word'],
            $data['attempts'],
            $data['tried_letters'],
            $data['found_letters']
        );
    }

    /**
     * @inheritdoc
     */
    public function save(Game $game)
    {
        $this->session->set('hangman', $game->getContext());
    }
}
