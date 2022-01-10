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
            'title'=> 'Passée Décomposé',
            'number'=> '1',
            'synopsis'=>'Rick Grimes, adjoint au shérif, sombre dans le coma après avoir reçu une balle dans l abdomen.
             Lorsqu il se réveille à l hôpital, il découvre avec stupéfaction que des morts-vivants peuplent la ville. Aussitôt, 
             il se précipite chez lui et constate que sa femme Lori et son fils Carl ont disparu…',
            'season'=> 'season_0',
        ],
        [
            'title'=> 'Tripes',
            'number'=> '2',
            'synopsis'=>'Rick parvient à s échapper du tank grâce à l aide de Glenn, dont il avait entendu la voix à la radio.
            Rick et Glenn se réunissent avec les compagnons de Glenn, un autre groupe de survivants dont Andrea, T-dog, Merle, Morales, Jacqui, 
            venus pour se ravitailler au supermarché. Cependant, l arrivée mouvementée de Rick les met tous en péril, 
            l attention des zombies ayant été attirée sur leur cachette. Assiégé par les zombies, le groupe parvient brièvement à communiquer par radio avec le groupe de Shane et Lori,
            mais ceux-ci décident qu ils ne peuvent les aider, la présence de Rick ne leur ayant pas encore été communiquée.,',
            'season'=> 'season_0',
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
