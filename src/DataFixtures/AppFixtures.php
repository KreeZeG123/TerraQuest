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
        $speciesData = [
            [
                "LatinName" => "Dionaea muscipula",
                "CommonName" => "Dionée attrape-mouche",
                "Origin" => "Endémique des zones marécageuses de la Caroline du Nord et du Sud aux États-Unis, où elle pousse dans des sols sableux pauvres soumis à une forte humidité et ensoleillement.",
                "Characteristics" => "Pièges actifs en forme de mâchoire qui se referment sur les insectes.\nPossède des poils sensitifs déclenchant la fermeture du piège.\nSécrète des enzymes pour digérer les proies.",
                "Utility" => "Régule naturellement les populations de petits insectes volants dans son environnement.",
                "CultivationCondition" => "Substrat : Tourbe blonde pure ou mélangée à de la perlite.\nLumière : Soleil direct ou lumière vive.\nHumidité : Substrat toujours humide avec de l’eau déminéralisée.\nTempérature : Entre 15 et 30 °C, avec une période de dormance en hiver (moins de 10 °C).",
                "Images" => ['images/dionaea_1.jpg', 'images/dionaea_2.jpg', 'images/dionaea_3.jpg', ],
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
                "Images" => ["images/drosera_1.jpg", "images/drosera_2.jpg", "images/drosera_3.jpg", ],
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
                "Images" => ["images/sarracenia_1.jpg", "images/sarracenia_2.jpg", "images/sarracenia_3.jpg", ],
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
                "Images" => ["images/nepenthes_1.jpg", "images/nepenthes_2.jpg", "images/nepenthes_3.jpg", ],
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

        $aridSpeciesData = [
            [
                "LatinName" => "Echinocactus grusonii",
                "CommonName" => "Coussin de Belle-Mère",
                "Origin" => "Originaire des zones arides du Mexique, en particulier de l'État de Durango.",
                "Characteristics" => "Cactus globulaire avec de fortes épines jaunes disposées en rayons réguliers.\nIl peut atteindre jusqu'à 1 mètre de diamètre dans des conditions optimales.\nPlante décorative, souvent cultivée en pot dans les régions plus fraîches.",
                "Utility" => "Bien que peu utilisée pour des fins alimentaires ou médicinales, son aspect esthétique la rend populaire en jardinage et dans la décoration intérieure.",
                "CultivationCondition" => "Substrat : Sol bien drainé, composé de sable et de terreau pour cactus.\nLumière : Ensoleillement direct, préfère des climats chauds et secs.\nHumidité : Faible, nécessite un environnement sec avec peu d'arrosage.\nTempérature : Entre 20 et 30 °C, sensible au gel.",
                "Images" => ["images/echinocactus_1.jpg", "images/echinocactus_2.jpg", "images/echinocactus_3.jpg" ],
                "latGPS" => "47.50009",
                "lngGPS" => "-0.56779"
            ],
            [
                "LatinName" => "Agave",
                "CommonName" => "Agave",
                "Origin" => "Originaire des régions arides et semi-arides du Mexique, de l'Amérique centrale et du Sud-Ouest des États-Unis.",
                "Characteristics" => "Plante succulente à grandes feuilles charnues, souvent disposées en rosette.\nLes feuilles sont souvent bordées d'épines et peuvent être recouvertes de cire blanche.\nProduit une grande tige florale lorsque la plante atteint sa maturité, souvent après de nombreuses années.",
                "Utility" => "L'Agave est également utilisé dans la fabrication de nectar, de sirop d'agave et pour des usages médicinaux traditionnels.",
                "CultivationCondition" => "Substrat : Sol bien drainé, légèrement sableux ou caillouteux.\nLumière : Ensoleillement direct, préfère les climats chauds et secs.\nHumidité : Faible, bien que résistant à des périodes de sécheresse prolongées.\nTempérature : Entre 18 et 30 °C, résiste à la chaleur mais pas au gel.",
                "Images" => ["images/agave_1.jpg", "images/agave_2.jpg", "images/agave_3.jpg", ],
                "latGPS" => "47.50001",
                "lngGPS" => "-0.56775"
            ],
            [
                "LatinName" => "Opuntia ficus-indica",
                "CommonName" => "Figuier de Barbarie",
                "Origin" => "Originaire des régions arides du Mexique et des Amériques, maintenant largement cultivé dans les zones subtropicales et méditerranéennes.",
                "Characteristics" => "Plante succulente avec des tiges aplaties, appelées \"cladodes\", qui ressemblent à des raquettes.\nProduit des fruits comestibles appelés \"figues de Barbarie\", qui sont généralement sucrés et riches en vitamines.\nLes tiges sont recouvertes de petites épines, mais certaines variétés sont sans épines.",
                "Utility" => "Le cactus est utilisé pour fabriquer des produits cosmétiques et médicinaux grâce à ses propriétés hydratantes et anti-inflammatoires.",
                "CultivationCondition" => "Substrat : Sol bien drainé, sableux, et légèrement acide à neutre.\nLumière : Exposition en plein soleil.\nHumidité : Faible à modérée, tolère bien les sécheresses.\n\nTempérature : Préfère les températures chaudes, entre 20 et 30 °C, mais résiste à de courtes périodes de froid (jusqu'à -5°C).\nValider comme réponse",
                "Images" => ["images/opuntia_1.jpg", "images/opuntia_2.jpg", "images/opuntia_3.jpg"],
                "latGPS" => "47.49992",
                "lngGPS" => "-0.5677"
            ],
        ];

        $aridSpecies = [];
        foreach ( $aridSpeciesData as $s) {
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

            $aridSpecies[] = $species;

            if ($s['LatinName'] === "Echinocactus grusonii") {
                $echinocactus  = $species;
            }

        }

        $settings = (new Settings())
            ->setLanguage("fr");


        $glossary = (new Glossary())
            ->addUnlockedSpecies($dionae)
            ->addUnlockedSpecies($echinocactus);

        $badge =  (new Badge())
            ->setTitle("Badge 1")
            ->setImage('images/prof-removebg-reverse.png')
            ->setUnlockingCondition("win > 5")
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

        $quiz1 = (new Quiz())
            ->setSpecies($dionae)
            ->setQuestion("Quel mécanisme unique la Dionée utilise-t-elle pour attraper ses proies ?")
            ->setAnswers([
                "Une substance collante",
                "Des pièges en forme d'urnes",
                "Des mâchoires sensibles qui se referment rapidement",
                "Une toile gluante"
            ])
            ->setCorrectAnswer("Des mâchoires sensibles qui se referment rapidement");


        $challenge1 = (new Challenge())
            ->setType("text")
            ->setDescription("Ah, cette plante carnivore… Je me souviens qu’elle a un mécanisme vraiment fascinant. Malheureusement, mes notes sont incomplètes ! Explore cette zone et utilise ton scanner pour m’aider.")
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
            $challenge1->addSpecies($s);
        }

        $challenge2 = (new Challenge())
            ->setType("text")
            ->setDescription("Ah, les lieux extrêmes… des endroits fascinant, mais je me souviens d’une plante en particulier. Elle a une forme unique et est très piquante. J’ai besoin de ton aide pour la retrouver !")
            ->setSpeciesToGuess($echinocactus)
            ->setHints(
                [
                    "Cette plante est parfaitement adaptée à la sécheresse grâce à sa capacité à stocker de l’eau. Cherche une espèce qui semble bien préparée pour les environnements les plus secs.",
                    "Je me souviens que cette plante a des épines ! Elles remplacent ses feuilles et l’aident à limiter la perte d’eau. Examine les plantes épineuses de la zone.",
                    "Cette plante pousse lentement et peut vivre des dizaines d’années. Elle est souvent utilisée dans des jardins secs pour sa robustesse et son apparence fascinante. Continue de chercher parmi les plantes qui paraissent immobiles et résilientes.",
                    "Elle a une forme arrondie très distincte, comme un coussin. Ses épines dorées la rendent aussi belle qu’intouchable. As-tu remarqué une plante qui ressemble à une sphère dans le Jardin sans Eau ?",
                    "Je me souviens ! La plante que nous cherchons se nomme l’Echinocactus grusonii, aussi surnommé le Coussin de Belle-Mère.",
                ]
            );
        foreach ($aridSpecies as $s) {
            $challenge2->addSpecies($s);
        }

        $quiz2 = (new Quiz())
            ->setSpecies($echinocactus)
            ->setQuestion("Quelles sont les caractéristiques physiques du Coussin de Belle-Mère ?")
            ->setAnswers([
                "Globulaire, avec de courtes épines jaunes disposées en rayons",
                "En forme de raquette avec de petites fleurs roses",
                "Pyramidal, avec des épines longues et droites",
                "En forme de colonne, recouvert de poils soyeux",
            ])
            ->setCorrectAnswer("Globulaire, avec de courtes épines jaunes disposées en rayons");

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
                $zone->addChallenge($challenge1);
            }

            if ($zone->getTitle() === "A l'épreuve des extremes") {
                $zone->addChallenge($challenge2);
            }

            $manager->persist($zone);
        }

        $parcours = (new Journey())
            ->setTitle("Le Mystère du Professeur Verdant")
            ->setInfos("Aider le Professeur Verdant à retrouver ses recherches sur la faune et la flore du parc !")
            ->setSlug($this->slugger->slug("Le Mystère du Professeur Verdant"));
        foreach ($parcoursAreas as $a) {
            $parcours->addArea($a);
        }

        $ongoingChallenge = (new OngoingChallenge())
            ->setChallenge($challenge1)
            ->setLastHint(2)
            ->addScannedSpecies($dionae)
            ->addScannedSpecies($challenge1->getSpecies()->last())
            ->setUpdatedAt(new \DateTimeImmutable());

        $partie = (new Game())
            ->setUser($user)
            ->setJourney($parcours)
            ->setNumStartingArea(3)
            ->setOngoingChallenge($ongoingChallenge)
            ->setNumberOfAreas(11)
            ->setNumberOfAreasCompleted(9)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());

        $admin = new User();
        $admin->setEmail("admin@admin.fr")
            ->setPassword($this->hasher->hashPassword($user, "admin"))
            ->setUsername("Admin")
            ->setSettings((new Settings())->setLanguage('fr'))
            ->setGlossary((new Glossary())->setUser($admin))
            ->setStatistics((new Statistics())->setUser($admin))
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);
        $manager->persist($settings);
        $manager->persist($glossary);
        $manager->persist($badge);
        $manager->persist($statistics);
        $manager->persist($user);
        $manager->persist($quiz1);
        $manager->persist($quiz2);
        $manager->persist($challenge1);
        $manager->persist($challenge2);
        $manager->persist($parcours);
        $manager->persist($ongoingChallenge);
        $manager->persist($partie);

        $manager->flush();
    }
}
