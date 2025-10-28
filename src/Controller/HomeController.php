<?php

namespace App\Controller;

use App\Repository\ParticipantRepository;
use App\Repository\SecretSantaSessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(SecretSantaSessionRepository $sessionRepo): Response
    {
        // On récupère la dernière session (pour le test)
        $session = $sessionRepo->findOneBy([], ['id' => 'DESC']);

        if (!$session) {
            return $this->render('home/no_session.html.twig');
        }

        return $this->render('home/index.html.twig', [
            'participants' => $session->getParticipants(),
            'session' => $session,
        ]);
    }
}
