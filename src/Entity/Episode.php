<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A media episode (e.g. Tv, radio, video game) which can be part of a series or season.
 *
 * @see http://schema.org/Episode Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/Episode")
 */
class Episode
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
     * @var string headline of the article
     *
     * @ORM\Column(type="text")
     * @ApiProperty(iri="http://schema.org/headline")
     * @Assert\NotNull
     */
    private $headline;

    /**
     * @var int position of the episode within an ordered group of episodes
     *
     * @ORM\Column(type="integer")
     * @ApiProperty(iri="http://schema.org/episodeNumber")
     * @Assert\NotNull
     */
    private $episodeNumber;

    /**
     * @var string|null a license document that applies to this content, typically indicated by URL
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/license")
     * @Assert\Url
     */
    private $license;

    /**
     * @var AggregateRating|null the overall rating, based on a collection of reviews or ratings, of the item
     *
     * @ORM\OneToOne(targetEntity="App\Entity\AggregateRating")
     * @ApiProperty(iri="http://schema.org/aggregateRating")
     */
    private $aggregateRating;

    /**
     * @var TvSeason|null the season to which this episode belongs
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\TvSeason", inversedBy="episodes")
     * @ORM\JoinColumn(nullable=false)
     * @ApiProperty(iri="http://schema.org/partOfSeason")
     */
    private $partOfSeason;

    /**
     * @var TvSeries|null the series to which this episode or season belongs
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\TvSeries", inversedBy="episodes")
     * @ORM\JoinColumn(nullable=false)
     * @ApiProperty(iri="http://schema.org/partOfSeries")
     */
    private $partOfSeries;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setHeadline(string $headline): void
    {
        $this->headline = $headline;
    }

    public function getHeadline(): string
    {
        return $this->headline;
    }

    public function setEpisodeNumber(int $episodeNumber): void
    {
        $this->episodeNumber = $episodeNumber;
    }

    public function getEpisodeNumber(): int
    {
        return $this->episodeNumber;
    }

    public function setLicense(?string $license): void
    {
        $this->license = $license;
    }

    public function getLicense(): ?string
    {
        return $this->license;
    }

    public function setAggregateRating(?AggregateRating $aggregateRating): void
    {
        $this->aggregateRating = $aggregateRating;
    }

    public function getAggregateRating(): ?AggregateRating
    {
        return $this->aggregateRating;
    }

    public function setPartOfSeason(?TvSeason $partOfSeason): void
    {
        $this->partOfSeason = $partOfSeason;
    }

    public function getPartOfSeason(): ?TvSeason
    {
        return $this->partOfSeason;
    }

    public function setPartOfSeries(?TvSeries $partOfSeries): void
    {
        $this->partOfSeries = $partOfSeries;
    }

    public function getPartOfSeries(): ?TvSeries
    {
        return $this->partOfSeries;
    }
}
