<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

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
}
