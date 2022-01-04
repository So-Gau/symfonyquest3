<?php

namespace App\DataFixtures;

use App\Entity\Season;
use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public const SEASONS = [
        [
            'number'=> '1',
            'year' => '2010',
            'description'=> 'Rick Grimes se réveille à l’hôpital alors que l’épidémie Zombie est déjà bien entamée. 
            Tout au long de la saison il va retrouver sa femme Lori, son fils Carl qui ont été protégés par son meilleur ami Shane 
            ainsi qu’un petit groupe. Ils vont rejoindre Atlanta pour trouver des infos sur l’épidémie dans un centre de recherche.',
            'program'=>'program_0',
        ],
        [
            'number'=> '2',
            'year' => '2012',
            'description'=> 'Cette saison suit les aventures de Rick Grimes, depuis leur rencontre avec une horde de rôdeurs sur une autoroute 
            et la disparition de Sophia Peletier jusqu à la fuite du groupe de la ferme des Greene envahie par les rôdeurs .',
            'program'=>'program_0'
        ],
        [
            'number'=> '1',
            'year' => '2021',
            'description'=> 'Les sœurs orphelines Vi et Powder causent des remous dans les rues souterraines de Zaun à la suite d un braquage dans le très huppé Piltover.
             Idéaliste, le chercheur Jayce tente de maîtriser la magie par la science malgré les avertissements de son mentor. Le criminel Silco teste une substance puissante.',
            'program'=>'program_2'
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::SEASONS as $key => $seasonData) {  
            $season = new Season();  
            $season->setNumber($seasonData['number']);
            $season->setYear($seasonData['year']); 
            $season->setDescription($seasonData['number']);  
        
            $season->setProgram($this->getReference($seasonData['program']));

         $manager->persist($season);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [
          ProgramFixtures::class,
        ];
    }
}