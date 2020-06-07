<?php

namespace App\Serializer\Normalizer;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Doctrine\ORM\EntityManagerInterface;

class UserNormalizer implements NormalizerInterface
{
    /** @var NormalizerInterface */
    private $normalizer;

    /** @var ObjectManager */
    private $em;

    public function __construct(ObjectNormalizer $normalizer, EntityManagerInterface $em)
    {
        $this->normalizer = $normalizer;
        $this->em = $em;
    }

    public function normalize($user, string $format = null, array $context = []): array
    {
        $raw = $this->normalizer->normalize($user, $format, $context);

        $groups = $context['groups'] ?? [];
        if (in_array('user:statistic', $groups)) {
            $raw['userId'] = $raw['id'];
            $raw['roundsCount'] = $this->countparticipatedRounds($raw['bids']);
            $raw['avgSpinsPerRound'] = $this->getAvgSpinsPerRound($raw['spins']);

            unset($raw['id']);
            unset($raw['bids']);
            unset($raw['spins']);
        }

        return $raw;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof User;
    }

    private function countParticipatedRounds(array $bidsData)
    {
        $rounds = [];
        foreach ($bidsData as $bid) {
            $rounds[] = $bid['spin']['round']['id'];
        }

        return count(array_unique($rounds));
    }

    private function getAvgSpinsPerRound(array $spinsData)
    {
        if (empty($spinsData)) {
            return 0;
        }

        $rounds = [];
        foreach ($spinsData as $spin) {
            $rounds[] = $spin['round']['id'];
        }

        return count($spinsData) / count(array_unique($rounds));
    }
}
