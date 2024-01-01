<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(Request $request, EntityManagerInterface $entityManager, MessageRepository $messageRepository): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $emptyForm = clone $form;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message);
            $entityManager->flush();
            $form = $emptyForm;
        }
      

        return $this->render('home/index.html.twig', [
            'form' => $form,
            'messages' => $messageRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }
}
