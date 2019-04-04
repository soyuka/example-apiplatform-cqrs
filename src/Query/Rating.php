<?php

declare(strict_types=1);

namespace App\Query;

use App\Entity\Episode;
use App\Entity\TvSeason;
use App\Entity\TvSeries;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiProperty;

final class Rating {
    /**
     * @var int
     * @Assert\Range(
     *      min = 0,
     *      max = 5,
     *      minMessage = "Note must be positive",
     *      maxMessage = "Note must be below {{ limit }}"
     * )
     */
    public $note;

    /**
     * The entity we're rating
     * @var Episode|TvSeason|TvSeries
     * @ApiProperty(writable=false)
     */
    public $entity;
}
