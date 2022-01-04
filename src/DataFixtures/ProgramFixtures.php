<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public const PROGRAMS = [
        [
            'title'=> 'The Walking Dead',
            'poster'=> 'https://pixabay.com/fr/photos/zombi-d%c3%a9c%c3%a8s-morte-jour-des-morts-1801470/',
            'summary' => 'Rick Grimes se réveille à l’hôpital alors que l’épidémie Zombie est déjà bien entamée.',
            'year' => '2010',
            'category'=>'horreur',
            'actors'=>[''],
        ],
        [
            'title'=> 'You',
            'poster'=> 'https://resize-elle.ladmedia.fr/r/625,,forcex/crop/625,804,center-middle,forcex,ffffff/img/var/plain_site/storage/images/loisirs/series/you-saison-2-cette-theorie-folle-sur-la-fin-de-la-saison-va-t-elle-vous-convaincre-3829729/92315263-1-fre-FR/You-Saison-2-cette-theorie-folle-sur-la-fin-de-la-saison-va-t-elle-vous-convaincre.jpg',
            'summary' => 'Adaptation du roman "Parfaite" de Caroline Kepnes, You met en scène Penn Badgley, ancien de Gossip Girl, dans la peau de Joe Goldberg, un banal libraire de New York. Mais le jeune homme se révèle effrayant et dangereux lorsqu il rencontre Guinevere Beck dont il tombe amoureux.',
            'year' => '2019',
            'category'=>'horreur',
            'actors'=>[''],
        ],
        [
            'title'=> 'Arcane',
            'poster'=> 'https://i0.wp.com/animotaku.fr/wp-content/uploads/2021/09/anime-arcane-date-sortie.jpeg',
            'summary' => 'Arcane se déroule dans le monde de Runeterra, univers fictif dans lequel prennent place les différents jeux League of Legends, et plus précisément à Piltover et Zaun, deux villes que tout oppose, situées au même endroit.',
            'year' => '2021',
            'category'=>'animation',
            'actors'=>[''],
        ],
        [
            'title'=> 'Black Mirror',
            'poster'=> 'https://occ-0-2794-2219.1.nflxso.net/dnm/api/v6/E8vDc_W8CLv7-yMQu8KMEC7Rrr8/AAAABYgfO2scOfdVA47hPlnpON0BBAFEnfL8759JzW6p2sifIP8iY2SlzoOg4FAdQEct6zZAJes3UcR4sL-eGEFR0vs3WmopYweJFOutT7PoDGivVDeSdUlALFy138Xs48H5.jpg?r=cbc',
            'summary' => 'Les épisodes sont liés par le thème commun de la mise en œuvre technologie dystopique. Le titre « Black Mirror » fait référence aux écrans omniprésents qui nous renvoient notre reflet. Sous un angle noir et souvent satirique, la série envisage un futur proche, voire immédiat. Elle interroge les conséquences inattendues que pourraient avoir les nouvelles technologies, et comment ces dernières influent sur la nature humaine de ses utilisateurs et inversement2.',
            'year' => '2011',
            'category'=>'science fiction',
            'actors'=>[''],
        ],   
        [
            'title'=> 'The Witcher',
            'poster'=> 'https://fr.web.img6.acsta.net/pictures/19/12/12/12/13/2421997.jpg',
            'summary' =>'Inspiré d une série littéraire fantastique à succès, The Witcher est un récit épique sur la famille et le destin. Geralt de Riv, un chasseur de monstres solitaire, se bat pour trouver sa place dans un monde où les humains sont souvent plus vicieux que les bêtes.',
            'year' => '2020',
            'category'=>'Fantastique',
            'actors'=>[''],
        ],      
];
    public function load(ObjectManager $manager): void
    {
        //foreach (static::PROGRAMS as $key => $program) {  
            $program = new Program();
            $program->SetTitle('title');
            $program->SetPoster('poster');
            $program->SetSummary('summary');
            $program->SetYear('0');
            $program->SetCategory($this->getReference('category_0'));
   
      
        $manager->persist($program);
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          ActorFixtures::class,
          CategoryFixtures::class,
        ];
    }
}
