<?php

namespace App\Service;

use App\Entity\SecretSantaSession;
use Doctrine\ORM\EntityManagerInterface;

class SecretSantaService
{
    public function __construct(private EntityManagerInterface $em) {}

    public function draw(SecretSantaSession $session): void
    {
        $participants = $session->getParticipants()->toArray();

        // sécurité
        if (count($participants) < 2) {
            throw new \RuntimeException('Il faut au moins 2 participants pour faire un tirage.');
        }

        $receivers = $participants;
        shuffle($receivers);

        // on recommence jusqu’à obtenir un tirage valide
        $attempts = 0;
        do {
            shuffle($receivers);
            $attempts++;
            if ($attempts > 1000) {
                throw new \RuntimeException('Impossible de trouver un tirage valide après 1000 tentatives.');
            }
        } while ($this->hasInvalidPairs($participants, $receivers));

        // Attribution
        foreach ($participants as $i => $giver) {
            $receiver = $receivers[$i];
            $giver->setReceiver($receiver);
            $this->em->persist($giver); // 👈 important
        }

        $session->setIsDrawn(true);
        $this->em->persist($session);
        $this->em->flush(); // 👈 c’est ce flush qui enregistre les receivers
    }

    private function hasInvalidPairs(array $givers, array $receivers): bool
    {
        foreach ($givers as $i => $giver) {
            $receiver = $receivers[$i];

            // pas soi-même
            if ($giver === $receiver) {
                return true;
            }

            // pas réciproque
            $giverIndex = array_search($giver, $receivers, true);
            if ($giverIndex !== false && $givers[$giverIndex] === $receiver) {
                return true;
            }
        }
        return false;
    }
}
