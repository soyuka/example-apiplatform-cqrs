<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\Rating;
use App\Entity\AggregateRating;
use App\Repository\AggregateRatingRepository;
use ApiPlatform\Core\Serializer\ItemNormalizer;
use Doctrine\Common\Persistence\ManagerRegistry;
use \RuntimeException;
use ApiPlatform\Core\Validator\ValidatorInterface;

final class RatingDataTransformer implements DataTransformerInterface
{
    private $manager;
    private $validator;

    public function __construct(ManagerRegistry $managerRegistry, ValidatorInterface $validator)
    {
        $this->manager = $managerRegistry->getManagerForClass(AggregateRating::class);
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = [])
    {
        if (null === $entity = $context[ItemNormalizer::OBJECT_TO_POPULATE] ?? null) {
            throw new RuntimeException("Entity is not defined");
        }

        $this->validator->validate($data);

        if (null === $aggregateRating = $entity->getAggregateRating()) {
            $aggregateRating = new AggregateRating();
            $aggregateRating->setRatingCount($data->note);
            $aggregateRating->setReviewCount(1);
        } else {
            $aggregateRating->setRatingCount($aggregateRating->getRatingCount() + $data->note);
            $aggregateRating->setReviewCount($aggregateRating->getReviewCount() + 1);
        }

        $this->manager->persist($aggregateRating);

        $entity->setAggregateRating($aggregateRating);

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if (!\is_array($data)) {
          return false;
        }

        return Rating::class === ($context['input']['class'] ?? null);
    }
}
