<?php

namespace App\DataFixtures;

use App\Entity\Program;
use App\Service\Slugify;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public const PROGRAMS = [
        [
            'title'=> 'The Walking Dead',
            'poster'=> 'https://images-na.ssl-images-amazon.com/images/I/81zqK4i3H0S.jpg',
            'summary' => 'Rick Grimes se réveille à l’hôpital alors que l’épidémie Zombie est déjà bien entamée.',
            'year' => '2010',
            'category'=>'category_4', //ou juste '4' les deux marches
            'actors'=>[''],
        ],
        [
            'title'=> 'You',
            'poster'=> 'https://media.gqmagazine.fr/photos/616d49b5d4bd52e104c66b87/master/pass/you.jpeg',
            'summary' => 'Adaptation du roman "Parfaite" de Caroline Kepnes, You met en scène Penn Badgley, ancien de Gossip Girl, dans la peau de Joe Goldberg, un banal libraire de New York. Mais le jeune homme se révèle effrayant et dangereux lorsqu il rencontre Guinevere Beck dont il tombe amoureux.',
            'year' => '2019',
            'category'=> 'category_4',
            'actors'=>[''],
        ],
        [
            'title'=> 'Arcane',
            'poster'=> 'https://i0.wp.com/animotaku.fr/wp-content/uploads/2021/09/anime-arcane-date-sortie.jpeg',
            'summary' => 'Arcane se déroule dans le monde de Runeterra, univers fictif dans lequel prennent place les différents jeux League of Legends, et plus précisément à Piltover et Zaun, deux villes que tout oppose, situées au même endroit.',
            'year' => '2021',
            'category'=>'category_2',
            'actors'=>[''],
        ],
        [
            'title'=> 'Black Mirror',
            'poster'=> 'https://www.presse-citron.net/app/uploads/2019/06/bm-e1559767649562.png',
            'summary' => 'Les épisodes sont liés par le thème commun de la mise en œuvre technologie dystopique. Le titre « Black Mirror » fait référence aux écrans omniprésents qui nous renvoient notre reflet. Sous un angle noir et souvent satirique, la série envisage un futur proche, voire immédiat. Elle interroge les conséquences inattendues que pourraient avoir les nouvelles technologies, et comment ces dernières influent sur la nature humaine de ses utilisateurs et inversement2.',
            'year' => '2011',
            'category'=>'category_5',
            'actors'=>[''],
        ],   
        [
            'title'=> 'The Witcher',
            'poster'=> 'https://fr.web.img6.acsta.net/pictures/19/12/12/12/13/2421997.jpg',
            'summary' =>'Inspiré d une série littéraire fantastique à succès, The Witcher est un récit épique sur la famille et le destin. Geralt de Riv, un chasseur de monstres solitaire, se bat pour trouver sa place dans un monde où les humains sont souvent plus vicieux que les bêtes.',
            'year' => '2020',
            'category'=>'category_3',
            'actors'=>[''],
        ],      
    ];

    private Slugify $slugify;

    public function __construct(Slugify $slugifyAsParameter)
    {
        $this->slugify = $slugifyAsParameter;
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::PROGRAMS as $key => $programData) {  
            $program = new Program();
            $program->setTitle($programData['title']);
            $program->setPoster($programData['poster']);
            $program->setSummary($programData['summary']);
            $program->setYear($programData['year']);
            $program->setOwner($programData['owner']);
            $program->setSlug(
                $this->slugify->generate($programData['title'])
            );
            $this->addReference('program_' . $key, $program); 
            
            $program->setCategory($this->getReference($programData['category']));
      
            $manager->persist($program);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          ActorFixtures::class,
          CategoryFixtures::class,
          UserFixtures::class,
        ];
    }
}
