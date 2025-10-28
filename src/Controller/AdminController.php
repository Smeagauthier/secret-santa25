<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\SecretSantaSession;
use App\Service\SecretSantaService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Uuid;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(Request $request, EntityManagerInterface $em, SecretSantaService $santa): Response
    {
        if ($request->isMethod('POST')) {
            $lines = array_filter(array_map('trim', explode("\n", (string) $request->get('participants'))));

            if (count($lines) < 2) {
                $this->addFlash('danger', 'Ajoute au moins 2 participants 🎅');
                return $this->redirectToRoute('app_admin');
            }

            // Crée la session
            $session = (new SecretSantaSession())->setCode('NOEL' . random_int(100, 999));
            $em->persist($session);

            // Crée les participants
            foreach ($lines as $name) {
                $p = (new Participant())
                    ->setName($name)
                    ->setSession($session)
                    ->setToken(Uuid::v4()->toRfc4122());
                $session->addParticipant($p);
                $em->persist($p);
            }

            $em->flush();
            $santa->draw($session);

            $links = [];
            foreach ($session->getParticipants() as $p) {
                $url = $this->generateUrl(
                    'app_reveal_token',
                    ['token' => $p->getToken()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );
                $links[] = ['name' => $p->getName(), 'url' => $url];
            }

            return $this->render('admin/done.html.twig', [
                'session' => $session,
                'links' => $links,
            ]);
        }

        return $this->render('admin/index.html.twig');
    }
}
