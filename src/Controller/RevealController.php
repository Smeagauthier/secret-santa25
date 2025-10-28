<?php

namespace App\Controller;

use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RevealController extends AbstractController
{
    #[Route('/reveal/{token}', name: 'app_reveal_token')]
    public function reveal(string $token, ParticipantRepository $participantRepo): Response
    {
        // 🎯 On retrouve le participant grâce à son token unique
        $giver = $participantRepo->findOneBy(['token' => $token]);

        if (!$giver) {
            throw $this->createNotFoundException('Lien invalide ou participant introuvable.');
        }

        // 🎁 Le "receiver" est la personne que le participant doit gâter
        $receiver = $giver->getReceiver();

        if (!$receiver) {
            throw $this->createNotFoundException('Le tirage n’a pas encore été effectué pour cette session.');
        }

        // 🎅 On passe tout au template
        return $this->render('reveal/show.html.twig', [
            'giver' => $giver,
            'receiver' => $receiver,
            'allParticipants' => $giver->getSession()->getParticipants(),
        ]);
    }
}
