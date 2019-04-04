<?php

namespace App\Command;

use App\Query\Rating;
use App\Entity\AggregateRating;
use App\Repository\AggregateRatingRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class Rate implements MessageHandlerInterface
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
    public function __invoke(Rating $rating)
    {
        $this->validator->validate($rating);

        if (null === $aggregateRating = $rating->entity->getAggregateRating()) {
            $aggregateRating = new AggregateRating();
            $aggregateRating->setRatingCount($rating->note);
            $aggregateRating->setReviewCount(1);
        } else {
            $aggregateRating->setRatingCount($aggregateRating->getRatingCount() + $rating->note);
            $aggregateRating->setReviewCount($aggregateRating->getReviewCount() + 1);
        }

        $rating->entity->setAggregateRating($aggregateRating);
        $this->manager->persist($aggregateRating);
        $this->manager->persist($rating->entity);
        $this->manager->flush();

        return $rating->entity;
    }
}
