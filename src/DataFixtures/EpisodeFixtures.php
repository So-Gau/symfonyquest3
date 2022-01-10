<?php

namespace App\DataFixtures;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public const EPISODES = [
        [
            'title'=> 'The Walking Dead',
            'number'=> '1',
            'synopsis'=>'Rick Grimes, adjoint au shérif, sombre dans le coma après avoir reçu une balle dans l abdomen.
             Lorsqu il se réveille à l hôpital, il découvre avec stupéfaction que des morts-vivants peuplent la ville. Aussitôt, 
             il se précipite chez lui et constate que sa femme Lori et son fils Carl ont disparu…',
            'season'=> 'season_1',
        ],
    ];

    private Slugify $slugify;

    public function __construct(Slugify $slugifyAsParameter)
    {
        $this->slugify = $slugifyAsParameter;
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::EPISODES as $key => $episodeData) {  
            $episode = new Episode();
            $episode->setTitle($episodeData['title']);
            $episode->setNumber($episodeData['number']);
            $episode->setSynopsis($episodeData['synopsis']);
            $episode->setSlug(
                $this->slugify->generate($episodeData['title'])
            );

            $episode->setSeason($this->getReference($episodeData['season']));
      
            $manager->persist($episode);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          SeasonFixtures::class,
        ];
    }
}
