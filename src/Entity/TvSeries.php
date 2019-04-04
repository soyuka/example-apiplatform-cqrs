<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CreativeWorkSeries dedicated to Tv broadcast and associated online delivery.
 *
 * @see http://schema.org/TvSeries Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/TvSeries")
 */
class TvSeries
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
     * @var int the number of episodes in this season or series
     *
     * @ORM\Column(type="integer")
     * @ApiProperty(iri="http://schema.org/numberOfEpisodes")
     * @Assert\NotNull
     */
    private $numberOfEpisode;

    /**
     * @var int the number of seasons in this series
     *
     * @ORM\Column(type="integer")
     * @ApiProperty(iri="http://schema.org/numberOfSeasons")
     * @Assert\NotNull
     */
    private $numberOfSeason;

    /**
     * @var Collection<TvSeason>|null a season in a media series
     *
     * @ORM\OneToMany(targetEntity="App\Entity\TvSeason", mappedBy="partOfSeries")
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     * @ApiProperty(iri="http://schema.org/seasons")
     */
    private $seasons;

    /**
     * @var Collection<Episode>|null an episode of a Tv/radio series or season
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Episode", mappedBy="partOfSeries")
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     * @ApiProperty(iri="http://schema.org/episodes")
     */
    private $episodes;

    public function __construct()
    {
        $this->seasons = new ArrayCollection();
        $this->episodes = new ArrayCollection();
    }

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

    public function setNumberOfEpisode(int $numberOfEpisode): void
    {
        $this->numberOfEpisode = $numberOfEpisode;
    }

    public function getNumberOfEpisode(): int
    {
        return $this->numberOfEpisode;
    }

    public function setNumberOfSeason(int $numberOfSeason): void
    {
        $this->numberOfSeason = $numberOfSeason;
    }

    public function getNumberOfSeason(): int
    {
        return $this->numberOfSeason;
    }

    public function addSeason(TvSeason $season): void
    {
        $this->seasons[] = $season;
    }

    public function removeSeason(TvSeason $season): void
    {
        $this->seasons->removeElement($season);
    }

    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addEpisode(Episode $episode): void
    {
        $this->episodes[] = $episode;
    }

    public function removeEpisode(Episode $episode): void
    {
        $this->episodes->removeElement($episode);
    }

    public function getEpisodes(): Collection
    {
        return $this->episodes;
    }
}
