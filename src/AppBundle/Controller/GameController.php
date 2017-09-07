<?php

namespace AppBundle\Controller;

use AppBundle\Game\GameRunner;
use AppBundle\Game\WordList;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Game\GameContext;
use AppBundle\Game\Loader\XmlFileLoader;
use AppBundle\Game\Loader\TextFileLoader;
use Symfony\Component\EventDispatcher\GenericEvent;
/**
 * @Route(
 *     "{_locale}/game",
 *     requirements={ "_locale" = "fr|en" }
 * )
 */
class GameController extends Controller
{
    /**
     * @Route("/", name="game_home")
     */
    public function homeAction()
    {
        $eventDispatcher = $this->get('event_dispatcher');
        $event = new GenericEvent('tedt'); $event->setArgument('author', 'hhamon');
        $eventDispatcher->dispatch('custom.event.identifier', $event);
        return $this->render('game/home.html.twig', [
            'game' => $this->getGameRunner()->loadGame()
        ]);
    }

    /**
     * @Route("/won", name="game_won")
     */
    public function wonAction()
    {
        return $this->render('game/won.html.twig', [
            'game' => $this->getGameRunner()->resetGameOnSuccess()
        ]);
    }

    /**
     * @Route("/failed", name="game_failed")
     */
    public function failedAction()
    {
        return $this->render('game/failed.html.twig', [
            'game' => $this->getGameRunner()->resetGameOnFailure()
        ]);
    }

    /**
     * @Route("/reset", name="game_reset")
     */
    public function resetAction()
    {
        $this->getGameRunner()->resetGame();

        return $this->redirectToRoute('game_home');
    }

    /**
     * This action plays a letter.
     *
     * @Route("/play/{letter}", name="game_play_letter", requirements={
     *   "letter"="[A-Z]"
     * })
     * @Method("GET")
     */
    public function playLetterAction($letter)
    {
        $game = $this->getGameRunner()->playLetter($letter);

        if (!$game->isOver()) {
            return $this->redirectToRoute('game_home');
        }

        return $this->redirectToRoute($game->isWon() ? 'game_won' : 'game_failed');
    }

    /**
     * This action plays a word.
     *
     * @Route("/play", name="game_play_word", condition="request.request.has('word')")
     * @Method("POST")
     */
    public function playWordAction(Request $request)
    {
        $game = $this->getGameRunner()->playWord($request->request->get('word'));

        return $this->redirectToRoute($game->isWon() ? 'game_won' : 'game_failed');
    }

    public function testimonialsAction()
    {
        return $this->render('game/testimonials.html.twig', [
            'testimonials' => [
                'John Doe' => 'I love this game, so addictive!',
                'Martin Durand' => 'Best web application ever',
                'Paul Smith' => 'Awesomeness!',
            ],
        ]);
    }

    private function getGameRunner()
    {
        return $this->get('app.game_runner');
    }
}
