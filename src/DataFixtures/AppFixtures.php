<?php

namespace App\DataFixtures;

use App\Entity\Participant;
use App\Entity\SecretSantaSession;
use App\Service\SecretSantaService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class AppFixtures extends Fixture
{
    private SecretSantaService $secretSantaService;

    public function __construct(SecretSantaService $secretSantaService)
    {
        $this->secretSantaService = $secretSantaService;
    }

    public function load(ObjectManager $manager): void
    {
        echo "\n🎅 --- Lancement des fixtures Secret Santa ---\n";

        // 1️⃣ Crée une session de test
        $session = (new SecretSantaSession())
            ->setCode('NOELTEST');

        // 2️⃣ Liste de participants
        $participantsData = [
            ['Papa', 'papa@mail.com'],
            ['Maman', 'maman@mail.com'],
            ['Camille', 'camille@mail.com'],
            ['Guillaume', 'guillaume@mail.com'],
            ['Baptiste', 'baptiste@mail.com'],
            ['Gauthier', 'rigauxgauthier@gmail.com'],
        ];

        $participants = [];

        // 3️⃣ Crée les participants avec tokens
        foreach ($participantsData as [$name, $email]) {
            $participant = (new Participant())
                ->setName($name)
                ->setSession($session)
                ->setToken(Uuid::v4()->toRfc4122());

            // 🔥 très important : ajoute le participant à la session
            $session->addParticipant($participant);

            $manager->persist($participant);
            $participants[] = $participant; // ✅ ajoute au tableau
        }

        $manager->persist($session);
        $manager->flush(); // ✅ flush avant le tirage pour obtenir les IDs

        // 4️⃣ Tirage Secret Santa
        $this->secretSantaService->draw($session);

        echo "✅ Session créée : NOELTEST\n";
        echo "🧑‍🎄 Participants : " . count($participants) . "\n\n";

        // 5️⃣ Affiche le tirage
        foreach ($participants as $p) {
            $receiver = $p->getReceiver() ? $p->getReceiver()->getName() : '❌ Aucun';
            echo sprintf("🎁 %s offre un cadeau à %s\n", $p->getName(), $receiver);
        }

        echo "\n🎄 --- Vérifications ---\n";

        $hasErrors = false;
        foreach ($participants as $p) {
            if ($p->getReceiver() === $p) {
                echo "⚠️  Erreur : {$p->getName()} s'est tiré lui-même !\n";
                $hasErrors = true;
            }

            $receiver = $p->getReceiver();
            if ($receiver && $receiver->getReceiver() === $p) {
                echo "⚠️  Réciprocité détectée entre {$p->getName()} et {$receiver->getName()} !\n";
                $hasErrors = true;
            }
        }

        if (!$hasErrors) {
            echo "✅ Aucun problème détecté : tirage valide !\n";
        }

        echo "\n🎅 --- Fixtures terminées avec succès ---\n";
    }
}
