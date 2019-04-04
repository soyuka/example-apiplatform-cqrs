<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The average rating based on multiple ratings or reviews.
 *
 * @see http://schema.org/AggregateRating Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/AggregateRating")
 */
class AggregateRating
{
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var int the count of total number of ratings
     *
     * @ORM\Column(type="integer")
     * @ApiProperty(iri="http://schema.org/ratingCount")
     * @Assert\NotNull
     */
    private $ratingCount;

    /**
     * @var int the count of total number of reviews
     *
     * @ORM\Column(type="integer")
     * @ApiProperty(iri="http://schema.org/reviewCount")
     * @Assert\NotNull
     */
    private $reviewCount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setRatingCount(int $ratingCount): void
    {
        $this->ratingCount = $ratingCount;
    }

    public function getRatingCount(): int
    {
        return $this->ratingCount;
    }

    public function setReviewCount(int $reviewCount): void
    {
        $this->reviewCount = $reviewCount;
    }

    public function getReviewCount(): int
    {
        return $this->reviewCount;
    }
}
