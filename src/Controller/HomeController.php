<?php

namespace App\Controller;

use App\Form\MessageType;
use Symfony\UX\Turbo\TurboBundle;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, HubInterface $hub): Response
    {
        $form = $this->createForm(MessageType::class);
        $emptyForm = clone $form;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $update = new Update(
                'chat',
                $this->renderView('home/message.stream.html.twig', ['message' => $form->getData()])
            );
    
            $hub->publish($update);

            // if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
            //     $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            //     return $this->render('home/message.stream.html.twig', ['message' => $form->getData()]);
            // }
            
            $form = $emptyForm;
        }
      

        return $this->render('home/index.html.twig', [
            'form' => $form,
        ]);
    }
}
