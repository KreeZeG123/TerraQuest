<?php

namespace App\DataFixtures;

use App\Entity\Area;
use App\Entity\Badge;
use App\Entity\Challenge;
use App\Entity\Game;
use App\Entity\Glossary;
use App\Entity\Journey;
use App\Entity\OngoingChallenge;
use App\Entity\Quiz;
use App\Entity\Settings;
use App\Entity\Species;
use App\Entity\Statistics;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly SluggerInterface $slugger,
        private readonly UserPasswordHasherInterface $hasher
    )
    {

    }

    public function load(ObjectManager $manager): void
    {
        $settings = (new Settings())
            ->setLanguage("fr");


        $glossary = new Glossary();

        $badge =  (new Badge())
            ->setTitle("Badge 1")
            ->setImage('images/prof-removebg-reverse.png')
            ->setUnlockingCondition("stats.getWins() > 5")
            ->setLegend("Avoir au moins 5 victoire");

        $statistics = (new Statistics())
            ->setWins(8);

        $user = new User();
        $user->setEmail("test@test.fr")
            ->setPassword($this->hasher->hashPassword($user, "0000"))
            ->setUsername("Test")
            ->setSettings($settings)
            ->addBadge($badge)
            ->setGlossary($glossary)
            ->setStatistics($statistics);


        $speciesData = [
            [
                "LatinName" => "Dionaea muscipula",
                "CommonName" => "Dionée attrape-mouche",
                "Origin" => "Endémique des zones marécageuses de la Caroline du Nord et du Sud aux États-Unis, où elle pousse dans des sols sableux pauvres soumis à une forte humidité et ensoleillement.",
                "Characteristics" => "Pièges actifs en forme de mâchoire qui se referment sur les insectes.\nPossède des poils sensitifs déclenchant la fermeture du piège.\nSécrète des enzymes pour digérer les proies.",
                "Utility" => "Régule naturellement les populations de petits insectes volants dans son environnement.",
                "CultivationCondition" => "Substrat : Tourbe blonde pure ou mélangée à de la perlite.\nLumière : Soleil direct ou lumière vive.\nHumidité : Substrat toujours humide avec de l’eau déminéralisée.\nTempérature : Entre 15 et 30 °C, avec une période de dormance en hiver (moins de 10 °C).",
                "Images" => ['images/prof-removebg-reverse.png'],
                "latGPS" => '47.50005',
                "lngGPS" => '-0.56843'
            ],
            [
                "LatinName" => "Drosera",
                "CommonName" => "Rossolis",
                "Origin" => "Présentes sur les cinq continents, les Droseras se développent surtout dans les zones marécageuses ou sablonneuses pauvres en nutriments, comme en Europe (tourbières), Australie et Afrique du Sud.",
                "Characteristics" => "Feuilles couvertes de poils glanduleux sécrétant une substance collante.\nAttire et piège de petits insectes comme les moucherons.\nLes feuilles s'enroulent autour des proies pour faciliter la digestion.",
                "Utility" => "Dans certaines cultures, la Drosera rotundifolia est utilisée en phytothérapie pour ses propriétés antitussives (traitement des toux et maux de gorge).",
                "CultivationCondition" => "Substrat : Tourbe blonde mélangée à du sable ou de la perlite.\nLumière : Lumière intense ou soleil filtré.\nHumidité : Substrat humide avec de l’eau déminéralisée ou de pluie.\nTempérature : Entre 15 et 30 °C.",
                "Images" => ["images/prof-removebg-reverse.png"],
                "latGPS" => "47.50014",
                "lngGPS" => "-0.5684"
            ],
            [
                "LatinName" => "Sarracenia",
                "CommonName" => "Sarracénie",
                "Origin" => "Ces plantes sont originaires d'Amérique du Nord, où elles colonisent les tourbières acides et marécages ensoleillés, principalement dans le sud-est des États-Unis et le Canada pour certaines espèces.",
                "Characteristics" => "Feuilles modifiées en urnes ou tubes colorés pour attirer les insectes.\nLes proies glissent dans le tube et se noient dans un liquide digestif.\nCertaines espèces peuvent capturer des petits vertébrés (rare).",
                "Utility" => "Sarracenia purpurea est utilisée traditionnellement par certains peuples autochtones pour traiter des maladies respiratoires (usages médicinaux non validés scientifiquement).",
                "CultivationCondition" => "Substrat : Tourbe blonde pure ou mélangée à de la perlite ou du sable non calcaire.\nLumière : Exposition plein soleil.\nHumidité : Substrat très humide en été, légèrement moins en hiver.\nTempérature : Entre 20 et 30 °C en été, avec une dormance hivernale (0 à 10 °C).",
                "Images" => ["images/prof-removebg-reverse.png"],
                "latGPS" => "47.50013",
                "lngGPS" => "-0.56821"
            ],
            [
                "LatinName" => "Nepenthes",
                "CommonName" => "Népenthès",
                "Origin" => "Originaire des forêts tropicales humides d'Asie du Sud-Est, des Philippines, de Bornéo, de Sumatra et de Madagascar.",
                "Characteristics" => "Les Népenthès ont des feuilles modifiées en urnes suspendues qui capturent des insectes.\nLes urnes sont souvent remplies de liquide digestif et sont dotées de crochets ou de glandes pour piéger les proies.",
                "Utility" => "Certains peuples d'Asie utilisent traditionnellement le nectar des Népenthès à des fins médicinales, mais les usages sont principalement folkloriques et non validés scientifiquement.",
                "CultivationCondition" => "Substrat : Mélange de tourbe et de sable.\nLumière : Lumière vive mais indirecte.\nHumidité : Très haute, le substrat doit toujours être humide.\nTempérature : Préfère des températures chaudes et humides, entre 20 et 30 °C.",
                "Images" => ["images/prof-removebg-reverse.png"],
                "latGPS" => "47.50001",
                "lngGPS" => "-0.56828"
            ]
            /*
            [
                "LatinName" => "XX",
                "CommonName" => "XX",
                "Origin" => "XX",
                "Characteristics" => "XX",
                "Utility" => "XX",
                "CultivationCondition" => "XX",
                "Images" => ["XX"],
                "latGPS" => "XX",
                "lngGPS" => "XX"
            ],
            */
        ];

        $carnivorousSpecies = [];
        foreach ( $speciesData as $s) {
            $species = (new Species())
                ->setLatinName($s['LatinName'])
                ->setSlug($this->slugger->slug($s['LatinName']))
                ->setCommonName($s['CommonName'])
                ->setOrigin($s['Origin'])
                ->setCharacteristics($s['Characteristics'])
                ->setUtility($s['Utility'])
                ->setCultivationCondition($s["CultivationCondition"])
                ->setImages($s['Images'])
                ->setLatGPS($s["latGPS"])
                ->setLngGPS($s["lngGPS"]);

            $manager->persist($species);

            $carnivorousSpecies[] = $species;

            if ($s['LatinName'] === "Dionaea muscipula") {
                $dionae = $species;
            }

        }

        $quiz = (new Quiz())
            ->setSpecies($dionae)
            ->setQuestion("Quel mécanisme unique la Dionée utilise-t-elle pour attraper ses proies ?")
            ->setAnswers([
                "Une substance collante",
                "Des pièges en forme d'urnes",
                "Des mâchoires sensibles qui se referment rapidement",
                "Une toile gluante"
            ])
            ->setCorrectAnswer("Des mâchoires sensibles qui se referment rapidement");


        $challenge = (new Challenge())
            ->setType("text")
            ->setDescription("Ah, cette plante carnivore… Je me souviens qu’elle a un mécanisme vraiment fascinant. Malheureusement, mes notes sont incomplètes ! Explore cette zone et utilise ton scanner pour m’aider.")
            ->setImage("images/avatar-50x50.png")
            ->setSpeciesToGuess($dionae)
            ->setHints(
                [
                    "Elle se nourrit d’insectes comme les autres ici, mais son piège est unique. Il ne ressemble ni à un tube ni à une surface collante.",
                    "Elle utilise un mécanisme en deux parties qui se referme sur sa proie. Un piège redoutable !",
                    "Son piège ne se déclenche qu’après plusieurs mouvements. Elle ne se laisse pas berner facilement.",
                    "Je me souviens, elle est incroyablement rapide. Ses 'mâchoires' capturent ses proies en un éclair.",
                    "Ça y est, son nom me revient : Dionaea muscipula, ou plus simplement, la Dionée attrape-mouche !"
                ]
            );
        foreach ($carnivorousSpecies as $s) {
            $challenge->addSpecies($s);
        }

        $zoneData = [
            [
                'lat' => 47.500321,
                'lng' => -0.569403,
                'title' => "Instant d'Asie"
            ],
            [
                'lat' => 47.50008,
                'lng' => -0.56834,
                'title' => "Les plantes carnivores"
            ],
            [
                'lat' => 47.499954,
                'lng' => -0.567926,
                'title' => "A l'épreuve des extremes"
            ],
            [
                'lat' => 47.501177,
                'lng' => -0.569753,
                'title' => "Le jardin sans eau"
            ],
            [
                'lat' => 47.501504,
                'lng' => -0.568679,
                'title' => "Les racines de la vie"
            ],
            [
                'lat' => 47.501662,
                'lng' => -0.569141,
                'title' => "Bain de couleurs"
            ],
            [
                'lat' => 47.502219,
                'lng' => -0.569910,
                'title' => "La Roseraie",
            ],
            [
                'lat' => 47.501974,
                'lng' => -0.571391,
                'title' => "L'allée des grands-mères"
            ],
            [
                'lat' => 47.501762,
                'lng' => -0.572003,
                'title' => "Les Bayous de Louisiane"
            ],
            [
                'lat' => 47.500925,
                'lng' => -0.572340,
                'title' => "Le Trésor de la Pérouse"
            ],
            [
                'lat' => 47.500479,
                'lng' => -0.571537,
                'title' => "La serre au papillons"
            ]
        ];

        $parcoursAreas = [];
        foreach ($zoneData as $z) {
            $zone = (new Area())
                ->setTitle($z['title'])
                ->setSlug($this->slugger->slug($z['title']))
                ->setInfos("")
                ->setLatGPS($z['lat'])
                ->setLngGPS($z['lng'])
                ->setRadius(20);

            $parcoursAreas[] = $zone;

            if ($zone->getTitle() === "Les plantes carnivores") {
                $zoneCarnivore = $zone;
                $zone->addChallenge($challenge);
            }

            $manager->persist($zone);
        }

        $zone = (new Area())
            ->setTitle("Les plantes carnivores")
            ->setSlug($this->slugger->slug("Les plantes carnivores"))
            ->setInfos("")
            ->setLatGPS(47.500337)
            ->setLngGPS(-0.568607)
            ->setRadius(20)
            ->addChallenge($challenge);

        $parcours = (new Journey())
            ->setTitle("Le Mystère du Professeur Verdant")
            ->setInfos("Aider le Professeur Verdant à retrouver ses recherches sur la faune et la flore du parc !")
            ->setSlug($this->slugger->slug("Le Mystère du Professeur Verdant"));
        foreach ($parcoursAreas as $a) {
            $parcours->addArea($a);
        }

        $ongoingChallenge = (new OngoingChallenge())
            ->setChallenge($challenge)
            ->setLastHint(3)
            ->addScannedSpecies($dionae)
            ->setUpdatedAt(new \DateTimeImmutable());

        $partie = (new Game())
            ->setUser($user)
            ->setJourney($parcours)
            ->setCompletedAreas([])
            ->setOngoingChallenge($ongoingChallenge)
            ->setNumberOfAreas(11)
            ->setNumberOfAreasCompleted(1)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());

        // Persistance en cascades
        $manager->persist($settings);
        $manager->persist($glossary);
        $manager->persist($badge);
        $manager->persist($statistics);
        $manager->persist($user);
        $manager->persist($quiz);
        $manager->persist($challenge);
        $manager->persist($zone);
        $manager->persist($parcours);
        $manager->persist($ongoingChallenge);
        $manager->persist($partie);

        $manager->flush();
    }
}
