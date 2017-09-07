<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;
use AppBundle\Service\ContactService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $stopwatch = $this->get('debug.stopwatch');
        $stopwatch->start('DefaultController::indexAction - render');
        $response = $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
        $stopwatch->stop('DefaultController::indexAction - render');


        // replace this example code with whatever you need
        return $response;
    }

    /**
     * @Route("/fragment/somevariablecontent", name="fragment_somevariablecontent")
     */
    public function someVariableContentAction()
    {
        return $this->render('fragments/someVariableContent.html.twig');
    }

    /**
     * @Route("/hello/{name}", name="hello")
     * @Template
     */
    public function helloWorldAction($name)
    {
        return ['name' => $name];
    }

    /**
     * @Route("{_locale}/contact", name="contact")
     */
    public function contactAction(Request $request)
    {
        $isAjax = $request->isXmlHttpRequest();
        $form = $this->createForm(ContactType::class, [], ['attr' => ['id'=>'contact-form']]);
        // $form->add('next', SubmitType::class);
        $form->handleRequest($request);
        //if($form->get('next')->isClicked())
        $this->get('security.authentication_utils')->getLastUsername();
        if (!$isAjax && $form->isSubmitted() && $form->isValid()) {
            //$this->get('app.contact_service')->sendMail($form);

            $this->addFlash('notice', 'Your request has been successfully sent.');

            //return $this->redirectToRoute('game_home');
        }

        $template = 'default/contact.html.twig';
        if($isAjax) {
            return new JsonResponse($form->get('country')->getConfig()->getOption('chocies'));
        }

        return $this->render($template, [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(
     *     "/my-birthday/{month}/{day}",
     *     name = "birthday",
     *     defaults = { "month" = "01", "day" = "01" },
     *     requirements = {
     *         "month" = "(0[0-9])|(1[0-2])",
     *         "day" = "(0[1-9])|([1-2][0-9])|(3[0-1])",
     *     },
     *     methods = { "GET" },
     *     schemes = { "http" }
     * )
     * @Template
     */
    public function birthdayAction($month, $day)
    {
        $date = new \Datetime('2015'.'-'.$month.'-'.$day);

        return ['now' => $date];
    }
}
