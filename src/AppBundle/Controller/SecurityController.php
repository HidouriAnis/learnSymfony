<?php

namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @Route(
     *     "{_locale}/login",
     *     requirements={ "_locale" = "fr|en" },
     *     name="app_login"
     * )
     */
    public function loginAction()
    {
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/{_locale}/register", name="register")
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', 'You have been successfully added to the big family of the hangman game!');

            return $this->redirectToRoute('game_home');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/users", name="users")
     */
    public function listUsersAction()
    {
        return $this->render('security/listUsers.html.twig', [
            'users' => $this->getDoctrine()->getRepository('AppBundle:User')->findAll(),
        ]);
    }
}
