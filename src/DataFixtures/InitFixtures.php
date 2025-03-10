<?php

namespace App\DataFixtures;

use App\Entity\Area;
use App\Entity\Challenge;
use App\Entity\Glossary;
use App\Entity\Journey;
use App\Entity\Quiz;
use App\Entity\Settings;
use App\Entity\Species;
use App\Entity\Statistics;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class InitFixtures extends Fixture
{
    private static $initalData = 
    [
        [
            'title' => "Instant d'Asie",
            'areaDescription' => "Plongez au pays du soleil levant avec une promenade entre rizière et camélias afin de découvrir l’action de l’homme sur les paysages.",
            'latGPS' => 47.500321,
            'lngGPS' => -0.569403,
            'species' =>
            [
                [
                    "LatinName" => "Camellia japonica",
                    "CommonName" => "Camélia du Japon",
                    "Origin" => "Originaire d'Asie de l'Est, notamment du Japon, de la Chine et de la Corée.",
                    "Characteristics" => "Arbuste persistant à fleurs spectaculaires, pouvant être rouges, roses ou blanches.\nFeuillage vert foncé lustré.\nFloraison hivernale ou printanière selon la variété.",
                    "Utility" => "Utilisé en ornementation dans les jardins et parcs.\nCertaines variétés sont exploitées pour la production de thé.",
                    "CultivationCondition" => "Sol acide, riche et bien drainé.\nExposition mi-ombre ou soleil tamisé.\nArrosage régulier, mais sans excès d'eau stagnante.",
                    'latGPS' => 47.50038,
                    'lngGPS' => -0.56951,
                    'images' => [ "images/species/camellia_1.png", "images/species/camellia_2.png", "images/species/camellia_3.png" ]
                ],
                [
                    "LatinName" => "Oryza sativa",
                    "CommonName" => "Riz",
                    "Origin" => "Asie du Sud et de l'Est, cultivé depuis plus de 10 000 ans.",
                    "Characteristics" => "Plante herbacée annuelle poussant en touffes.\nGraminée essentielle pour l'alimentation humaine.\nFleurs discrètes suivies de grains de riz dans des épis.",
                    "Utility" => "Aliment de base pour plus de la moitié de la population mondiale.\nUtilisé aussi dans la production d'amidon et d'alcool.",
                    "CultivationCondition" => "Sol inondé ou humide selon la culture (riz irrigué ou pluvial).\nExposition ensoleillée.\nTempérature chaude (supérieure à 20°C).",
                    'latGPS' => 47.50035,
                    'lngGPS' => -0.56931,
                    'images' => [ "images/species/oryza_1.png", "images/species/oryza_2.png", "images/species/oryza_3.png" ],
                    'quiz' => [
                        'question' => "Quel est l'usage principal du riz dans l'alimentation humaine ?",
                        'answers' => [
                            "Épice",
                            "Céréale de base",
                            "Fruit",
                            "Légume racine"
                        ],
                        'correctAnswer' => "Céréale de base"
                    ],
                    'challenge' => [
                        'description' => "Cette plante est cultivée depuis des millénaires et constitue l’aliment de base de milliards de personnes. Peux-tu deviner de quelle espèce il s'agit ?",
                        'hints' => [
                            "Cette plante pousse en touffes et préfère un sol humide, voire inondé. Cherche une espèce qui aime l’eau.",
                            "Elle fait partie de la famille des graminées et produit des grains utilisés dans l’alimentation humaine.",
                            "Cette plante est cultivée dans des rizières et est une des céréales les plus consommées au monde.",
                            "Les principaux pays producteurs se situent en Asie, notamment en Chine et en Inde.",
                            "Je me souviens ! La plante que nous cherchons est le riz, aussi appelé Oryza sativa.",
                        ],
                    ],
                ],
                [
                    "LatinName" => "Acer palmatum",
                    "CommonName" => "Érable du Japon",
                    "Origin" => "Japon, Corée et Chine.",
                    "Characteristics" => "Petit arbre caduc à feuilles palmées et finement découpées.\nSuperbe coloration automnale allant du rouge vif à l'orange.",
                    "Utility" => "Très apprécié en ornementation, notamment pour les jardins japonais.\nCertains cultivars sont utilisés pour le bonsaï.",
                    "CultivationCondition" => "Sol riche, bien drainé et légèrement acide.\nExposition mi-ombre à ensoleillée.\nProtection contre les vents froids.",
                    'latGPS' => 47.50029,
                    'lngGPS' => -0.56946,
                    'images' => [ "images/species/acer_1.png", "images/species/acer_2.png", "images/species/acer_3.png" ]
                ],
                [
                    "LatinName" => "Bambusa vulgaris",
                    "CommonName" => "Bambou commun",
                    "Origin" => "Originaire d'Asie du Sud et du Sud-Est.",
                    "Characteristics" => "Plante à croissance rapide formant des touffes denses.\nTiges lignifiées pouvant atteindre plusieurs mètres de hauteur.\nFeuillage persistant et léger.",
                    "Utility" => "Utilisé pour la construction, la vannerie et comme plante ornementale.\nCertaines espèces sont consommées pour leurs jeunes pousses.",
                    "CultivationCondition" => "Sol bien drainé, fertile et humide.\nExposition ensoleillée ou mi-ombre.\nSupporte bien l'humidité mais tolère la sécheresse modérée.",
                    'latGPS' => 47.50019,
                    'lngGPS' => -0.56934,
                    'images' => [ "images/species/bambusa_1.png", "images/species/bambusa_2.png", "images/species/bambusa_3.png" ]
                ]
            ]
	    ],
        [
            'title' => "Les plantes carnivores",
            'areaDescription' => "Laissez-vous surprendre par la diversité de 4 jardins fascinants avec nos différentes plantes carnivores ! Prenez garde à vos doigts !",
            'latGPS' => 47.50008,
            'lngGPS' => -0.56834,
            'species' =>
            [
                [
                    "LatinName" => "Dionaea muscipula",
                    "CommonName" => "Dionée attrape-mouche",
                    "Origin" => "Endémique des zones marécageuses de la Caroline du Nord et du Sud aux États-Unis, où elle pousse dans des sols sableux pauvres soumis à une forte humidité et ensoleillement.",
                    "Characteristics" => "Pièges actifs en forme de mâchoire qui se referment sur les insectes.\nPossède des poils sensitifs déclenchant la fermeture du piège.\nSécrète des enzymes pour digérer les proies.",
                    "Utility" => "Régule naturellement les populations de petits insectes volants dans son environnement.",
                    "CultivationCondition" => "Substrat : Tourbe blonde pure ou mélangée à de la perlite.\nLumière : Soleil direct ou lumière vive.\nHumidité : Substrat toujours humide avec de l’eau déminéralisée.\nTempérature : Entre 15 et 30 °C, avec une période de dormance en hiver (moins de 10 °C).",
                    "latGPS" => '47.50005',
                    "lngGPS" => '-0.56843',
                    "images" => [ 'images/species/dionaea_1.jpg', 'images/species/dionaea_2.jpg', 'images/species/dionaea_3.jpg' ],
                    'quiz' => [
                        'question' => "Quel mécanisme unique la Dionée utilise-t-elle pour attraper ses proies ?",
                        'answers' => [
                            "Une substance collante",
                            "Des pièges en forme d'urnes",
                            "Des mâchoires sensibles qui se referment rapidement",
                            "Une toile gluante"
                        ],
                        'correctAnswer' => "Des mâchoires sensibles qui se referment rapidement"
                    ],
                    'challenge' => [
                        'description' => "Ah, cette plante carnivore… Je me souviens qu’elle a un mécanisme vraiment fascinant. Malheureusement, mes notes sont incomplètes ! Explore cette zone et utilise ton scanner pour m’aider.",
                        'hints' => [
                            "Elle se nourrit d’insectes comme les autres ici, mais son piège est unique. Il ne ressemble ni à un tube ni à une surface collante.",
                            "Elle utilise un mécanisme en deux parties qui se referme sur sa proie. Un piège redoutable !",
                            "Son piège ne se déclenche qu’après plusieurs mouvements. Elle ne se laisse pas berner facilement.",
                            "Je me souviens, elle est incroyablement rapide. Ses 'mâchoires' capturent ses proies en un éclair.",
                            "Ça y est, son nom me revient : Dionaea muscipula, ou plus simplement, la Dionée attrape-mouche !"
                        ]
                    ],
                ],
                [
                    "LatinName" => "Drosera",
                    "CommonName" => "Rossolis",
                    "Origin" => "Présentes sur les cinq continents, les Droseras se développent surtout dans les zones marécageuses ou sablonneuses pauvres en nutriments, comme en Europe (tourbières), Australie et Afrique du Sud.",
                    "Characteristics" => "Feuilles couvertes de poils glanduleux sécrétant une substance collante.\nAttire et piège de petits insectes comme les moucherons.\nLes feuilles s'enroulent autour des proies pour faciliter la digestion.",
                    "Utility" => "Dans certaines cultures, la Drosera rotundifolia est utilisée en phytothérapie pour ses propriétés antitussives (traitement des toux et maux de gorge).",
                    "CultivationCondition" => "Substrat : Tourbe blonde mélangée à du sable ou de la perlite.\nLumière : Lumière intense ou soleil filtré.\nHumidité : Substrat humide avec de l’eau déminéralisée ou de pluie.\nTempérature : Entre 15 et 30 °C.",
                    "latGPS" => "47.50014",
                    "lngGPS" => "-0.5684",
                    "images" => [ "images/species/drosera_1.jpg", "images/species/drosera_2.jpg", "images/species/drosera_3.jpg" ],
                ],
                [
                    "LatinName" => "Sarracenia",
                    "CommonName" => "Sarracénie",
                    "Origin" => "Ces plantes sont originaires d'Amérique du Nord, où elles colonisent les tourbières acides et marécages ensoleillés, principalement dans le sud-est des États-Unis et le Canada pour certaines espèces.",
                    "Characteristics" => "Feuilles modifiées en urnes ou tubes colorés pour attirer les insectes.\nLes proies glissent dans le tube et se noient dans un liquide digestif.\nCertaines espèces peuvent capturer des petits vertébrés (rare).",
                    "Utility" => "Sarracenia purpurea est utilisée traditionnellement par certains peuples autochtones pour traiter des maladies respiratoires (usages médicinaux non validés scientifiquement).",
                    "CultivationCondition" => "Substrat : Tourbe blonde pure ou mélangée à de la perlite ou du sable non calcaire.\nLumière : Exposition plein soleil.\nHumidité : Substrat très humide en été, légèrement moins en hiver.\nTempérature : Entre 20 et 30 °C en été, avec une dormance hivernale (0 à 10 °C).",
                    "latGPS" => "47.50013",
                    "lngGPS" => "-0.56821",
                    "images" => [ "images/species/sarracenia_1.jpg", "images/species/sarracenia_2.jpg", "images/species/sarracenia_3.jpg" ],
                ],
                [
                    "LatinName" => "Nepenthes",
                    "CommonName" => "Népenthès",
                    "Origin" => "Originaire des forêts tropicales humides d'Asie du Sud-Est, des Philippines, de Bornéo, de Sumatra et de Madagascar.",
                    "Characteristics" => "Les Népenthès ont des feuilles modifiées en urnes suspendues qui capturent des insectes.\nLes urnes sont souvent remplies de liquide digestif et sont dotées de crochets ou de glandes pour piéger les proies.",
                    "Utility" => "Certains peuples d'Asie utilisent traditionnellement le nectar des Népenthès à des fins médicinales, mais les usages sont principalement folkloriques et non validés scientifiquement.",
                    "CultivationCondition" => "Substrat : Mélange de tourbe et de sable.\nLumière : Lumière vive mais indirecte.\nHumidité : Très haute, le substrat doit toujours être humide.\nTempérature : Préfère des températures chaudes et humides, entre 20 et 30 °C.",
                    "latGPS" => "47.50001",
                    "lngGPS" => "-0.56828",
                    "images" => ["images/species/nepenthes_1.jpg", "images/species/nepenthes_2.jpg", "images/species/nepenthes_3.jpg" ],
                ]
            ]
        ],
        [
            'title' => "A l'épreuve des extremes",
            'areaDescription' => "Grâce à des hologrammes, ce spectacle vous révélera comment certaines plantes réussissent à s’adapter pour résister à des conditions climatiques extrêmes.",
            'latGPS' => 47.49995,
            'lngGPS' => -0.567926,
            'species' => [
                [
                    "LatinName" => "Echinocactus grusonii",
                    "CommonName" => "Coussin de Belle-Mère",
                    "Origin" => "Originaire des zones arides du Mexique, en particulier de l'État de Durango.",
                    "Characteristics" => "Cactus globulaire avec de fortes épines jaunes disposées en rayons réguliers.\nIl peut atteindre jusqu'à 1 mètre de diamètre dans des conditions optimales.\nPlante décorative, souvent cultivée en pot dans les régions plus fraîches.",
                    "Utility" => "Bien que peu utilisée pour des fins alimentaires ou médicinales, son aspect esthétique la rend populaire en jardinage et dans la décoration intérieure.",
                    "CultivationCondition" => "Substrat : Sol bien drainé, composé de sable et de terreau pour cactus.\nLumière : Ensoleillement direct, préfère des climats chauds et secs.\nHumidité : Faible, nécessite un environnement sec avec peu d'arrosage.\nTempérature : Entre 20 et 30 °C, sensible au gel.",
                    "images" => [ "images/species/echinocactus_1.jpg", "images/species/echinocactus_2.jpg", "images/species/echinocactus_3.jpg" ],
                    "latGPS" => "47.50009",
                    "lngGPS" => "-0.56779",
                    'quiz' => [
                        'question' => "Quelles sont les caractéristiques physiques du Coussin de Belle-Mère ?",
                        'answers' => [
                            "Globulaire, avec de courtes épines jaunes disposées en rayons",
                            "En forme de raquette avec de petites fleurs roses",
                            "Pyramidal, avec des épines longues et droites",
                            "En forme de colonne, recouvert de poils soyeux",
                        ],
                        'correctAnswer' => "Globulaire, avec de courtes épines jaunes disposées en rayons"
                    ],
                    'challenge' => [
                        'description' => "Ah, les lieux extrêmes… des endroits fascinant, mais je me souviens d’une plante en particulier. Elle a une forme unique et est très piquante. J’ai besoin de ton aide pour la retrouver !",
                        'hints' => [
                            "Cette plante est parfaitement adaptée à la sécheresse grâce à sa capacité à stocker de l’eau. Cherche une espèce qui semble bien préparée pour les environnements les plus secs.",
                            "Je me souviens que cette plante a des épines ! Elles remplacent ses feuilles et l’aident à limiter la perte d’eau. Examine les plantes épineuses de la zone.",
                            "Cette plante pousse lentement et peut vivre des dizaines d’années. Elle est souvent utilisée dans des jardins secs pour sa robustesse et son apparence fascinante. Continue de chercher parmi les plantes qui paraissent immobiles et résilientes.",
                            "Elle a une forme arrondie très distincte, comme un coussin. Ses épines dorées la rendent aussi belle qu’intouchable. As-tu remarqué une plante qui ressemble à une sphère dans le Jardin sans Eau ?",
                            "Je me souviens ! La plante que nous cherchons se nomme l’Echinocactus grusonii, aussi surnommé le Coussin de Belle-Mère.",
                        ]
                    ],
                ],
                [
                    "LatinName" => "Agave",
                    "CommonName" => "Agave",
                    "Origin" => "Originaire des régions arides et semi-arides du Mexique, de l'Amérique centrale et du Sud-Ouest des États-Unis.",
                    "Characteristics" => "Plante succulente à grandes feuilles charnues, souvent disposées en rosette.\nLes feuilles sont souvent bordées d'épines et peuvent être recouvertes de cire blanche.\nProduit une grande tige florale lorsque la plante atteint sa maturité, souvent après de nombreuses années.",
                    "Utility" => "L'Agave est également utilisé dans la fabrication de nectar, de sirop d'agave et pour des usages médicinaux traditionnels.",
                    "CultivationCondition" => "Substrat : Sol bien drainé, légèrement sableux ou caillouteux.\nLumière : Ensoleillement direct, préfère les climats chauds et secs.\nHumidité : Faible, bien que résistant à des périodes de sécheresse prolongées.\nTempérature : Entre 18 et 30 °C, résiste à la chaleur mais pas au gel.",
                    "images" => [ "images/species/agave_1.jpg", "images/species/agave_2.jpg", "images/species/agave_3.jpg" ],
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
                    "images" => [ "images/species/opuntia_1.jpg", "images/species/opuntia_2.jpg", "images/species/opuntia_3.jpg" ],
                    "latGPS" => "47.49992",
                    "lngGPS" => "-0.5677"
                ]
            ]
        ],
        [
            'title' => "Le jardin sans eau",
            'areaDescription' => "Évadez-vous parmi des plantes caractéristiques de cette belle région.",
            'latGPS' => 47.501177,
            'lngGPS' => -0.569753,
            'species' => [
                [
                    "LatinName" => "Lavandula angustifolia",
                    "CommonName" => "Lavande vraie",
                    "Origin" => "Originaire du bassin méditerranéen.",
                    "Characteristics" => "Arbuste aromatique à fleurs violettes très parfumées.\nFeuilles persistantes étroites et gris-vert.\nAttire les pollinisateurs comme les abeilles.",
                    "Utility" => "Utilisée en parfumerie et pour ses propriétés médicinales (calmante, antiseptique).\nExploitable en infusion ou huile essentielle.",
                    "CultivationCondition" => "Sol bien drainé, plutôt calcaire et sec.\nExposition plein soleil.\nArrosage faible, supporte bien la sécheresse.",
                    'latGPS' => 47.50131,
                    'lngGPS' => -0.56958,
                    'images' => [ "images/species/lavandula_1.png", "images/species/lavandula_2.png", "images/species/lavandula_3.png" ]
                ],
                [
                    "LatinName" => "Santolina chamaecyparissus",
                    "CommonName" => "Santoline",
                    "Origin" => "Originaire du bassin méditerranéen.",
                    "Characteristics" => "Petit arbuste persistant aux feuilles grisâtres et aux fleurs jaunes en été.\nTrès résistant à la sécheresse.\nÉmet une forte odeur aromatique.",
                    "Utility" => "Utilisé en haie basse ou couvre-sol.\nPossède des propriétés médicinales (antiseptique, insectifuge).",
                    "CultivationCondition" => "Sol sec, bien drainé, pauvre.\nExposition plein soleil.\nRésiste aux fortes chaleurs et aux sols pauvres.",
                    'latGPS' => 47.5013,
                    'lngGPS' => -0.56983,
                    'images' => [ "images/species/santolina_1.png", "images/species/santolina_2.png", "images/species/santolina_3.png" ]
                ],
                [
                    "LatinName" => "Sedum spectabile",
                    "CommonName" => "Orpin remarquable",
                    "Origin" => "Originaire de Chine et de Corée.",
                    "Characteristics" => "Plante succulente à feuilles charnues et fleurs roses en automne.\nAttire les papillons et les abeilles.\nRésistante à la sécheresse.",
                    "Utility" => "Utilisé en massifs ou toitures végétalisées.\nTrès ornemental en fin de saison.",
                    "CultivationCondition" => "Sol pauvre et drainant.\nExposition plein soleil.\nTrès faible besoin en eau.",
                    'latGPS' => 47.5011,
                    'lngGPS' => -0.56968,
                    'images' => [ "images/species/sedum_1.png", "images/species/sedum_2.png", "images/species/sedum_3.png" ]
                ],
                [
                    "LatinName" => "Euphorbia characias",
                    "CommonName" => "Euphorbe méditerranéenne",
                    "Origin" => "Originaire d'Europe du Sud.",
                    "Characteristics" => "Plante vivace au port dressé et aux inflorescences vert-jaune.\nExsude un latex toxique en cas de blessure.\nPersistante et très résistante à la sécheresse.",
                    "Utility" => "Utilisée pour stabiliser les sols secs.\nOrnementale pour jardins méditerranéens.",
                    "CultivationCondition" => "Sol bien drainé, sableux ou caillouteux.\nExposition plein soleil.\nNe nécessite pratiquement aucun arrosage.",
                    'latGPS' => 47.50101,
                    'lngGPS' => -0.56975,
                    'images' => [ "images/species/euphorbia_1.png", "images/species/euphorbia_2.png", "images/species/euphorbia_3.png" ],
                    'quiz' => [
                        'question' => "Quel est un trait distinctif de l'Euphorbe méditerranéenne ?",
                        'answers' => [
                            "Elle possède des fleurs bleues",
                            "Elle exsude un latex toxique",
                            "Elle pousse uniquement dans l'eau",
                            "Elle produit des baies comestibles"
                        ],
                        'correctAnswer' => "Elle exsude un latex toxique"
                    ],
                    'challenge' => [
                        'description' => "Je me souviens d’une plante à l’allure particulière… Elle produit une substance laiteuse et peut survivre aux conditions les plus arides. Aide-moi à retrouver son nom !",
                        'hints' => [
                            "Cette plante a des tiges dressées et porte de petites fleurs vert-jaune.",
                            "Elle est originaire d’Europe du Sud et s’adapte aux climats secs et ensoleillés.",
                            "Elle exsude un latex toxique lorsqu’elle est coupée, ce qui la rend peu appétissante pour les herbivores.",
                            "On la retrouve souvent dans les jardins méditerranéens où elle est appréciée pour sa rusticité.",
                            "Je me souviens ! La plante que nous cherchons est l’Euphorbia characias, aussi appelée Euphorbe méditerranéenne.",
                        ],
                    ],
                ]
            ]
        ],
        [
            'title' => "Les racines de la vie",
            'areaDescription' => "Remontez l’histoire du végétal, de la forêt primaire jusqu’à l’apparition de l’homme. Un dépaysement et un voyage dans le temps assuré !",
            'latGPS' => 47.501504,
            'lngGPS' => -0.568679,
            'species' => [
                [
                    "LatinName" => "Ginkgo biloba",
                    "CommonName" => "Ginkgo",
                    "Origin" => "Originaire de Chine, considéré comme une espèce fossile vivante.",
                    "Characteristics" => "Arbre caduc à feuilles en éventail, pouvant atteindre 30 mètres de haut.\nRésistant à la pollution et aux maladies.\nLes feuilles prennent une magnifique couleur dorée en automne.",
                    "Utility" => "Utilisé en médecine traditionnelle pour ses propriétés sur la mémoire et la circulation sanguine.\nPlante ornementale populaire dans les jardins et espaces urbains.",
                    "CultivationCondition" => "Sol profond, bien drainé et légèrement acide.\nExposition plein soleil.\nRésistant aux conditions climatiques difficiles.",
                    'latGPS' => 47.50141,
                    'lngGPS' => -0.5682,
                    'images' => [ "images/species/ginkgo_1.png", "images/species/ginkgo_2.png", "images/species/ginkgo_3.png" ]
                ],
                [
                    "LatinName" => "Equisetum arvense",
                    "CommonName" => "Prêle des champs",
                    "Origin" => "Originaire de l’hémisphère nord, présent dans les milieux humides.",
                    "Characteristics" => "Plante vivace primitive à tiges creuses et rigides.\nNe produit pas de fleurs mais se reproduit par spores.\nContient une forte concentration en silice.",
                    "Utility" => "Utilisée en phytothérapie pour ses propriétés diurétiques et reminéralisantes.\nEmployée en agriculture biologique comme fongicide naturel.",
                    "CultivationCondition" => "Sol humide, riche en silice.\nExposition soleil ou mi-ombre.\nPousse spontanément dans les zones humides et marécageuses.",
                    'latGPS' => 47.50128,
                    'lngGPS' => -0.56777,
                    'images' => [ "images/species/equisetum_1.png", "images/species/equisetum_2.png", "images/species/equisetum_3.png" ],
                    'quiz' => [
                        'question' => "Pourquoi la prêle des champs est-elle considérée comme une plante primitive ?",
                        'answers' => [
                            "Elle produit des fleurs énormes",
                            "Elle se reproduit uniquement par graines",
                            "Elle se propage par spores et non par graines",
                            "Elle change de couleur en hiver"
                        ],
                        'correctAnswer' => "Elle se propage par spores et non par graines"
                    ],
                    'challenge' => [
                        'description' => "Une plante très ancienne, presque préhistorique, pousse dans cette zone… Elle n’a pas de fleurs ni de graines, mais elle prospère depuis des millions d’années. Peux-tu la retrouver ?",
                        'hints' => [
                            "Cette plante pousse dans les milieux humides et marécageux.",
                            "Elle possède des tiges creuses et rigides qui rappellent le bambou.",
                            "Contrairement aux autres plantes, elle ne produit pas de fleurs, mais se reproduit par spores.",
                            "Elle contient une forte concentration en silice, ce qui lui donne un aspect robuste et particulier.",
                            "Je me souviens ! La plante que nous cherchons est la prêle des champs, aussi connue sous le nom d’Equisetum arvense.",
                        ],
                    ],
                ],
                [
                    "LatinName" => "Araucaria araucana",
                    "CommonName" => "Désespoir des singes",
                    "Origin" => "Originaire du Chili et d'Argentine.",
                    "Characteristics" => "Grand conifère au port pyramidal et aux branches couvertes de feuilles épaisses et piquantes.\nPeut vivre plusieurs centaines d'années.\nÉcorce très résistante aux incendies.",
                    "Utility" => "Bois utilisé en menuiserie et ébénisterie.\nArbre ornemental apprécié pour son aspect préhistorique.",
                    "CultivationCondition" => "Sol bien drainé, légèrement acide.\nExposition ensoleillée.\nSupporte le froid mais craint les sols trop humides.",
                    'latGPS' => 47.5015,
                    'lngGPS' => -0.56771,
                    'images' => [ "images/species/araucaria_1.png", "images/species/araucaria_2.png", "images/species/araucaria_3.png" ]
                ],
                [
                    "LatinName" => "Cycas revoluta",
                    "CommonName" => "Cycas du Japon",
                    "Origin" => "Originaire du Japon et de l’Asie du Sud-Est.",
                    "Characteristics" => "Plante ressemblant à un palmier avec un tronc court et des feuilles en rosette.\nPlante dioïque (pieds mâles et femelles séparés).\nCroissance très lente.",
                    "Utility" => "Plante ornementale très prisée pour les jardins tropicaux.\nFeuilles et graines toxiques si ingérées.",
                    "CultivationCondition" => "Sol bien drainé, sablonneux ou caillouteux.\nExposition plein soleil ou mi-ombre.\nArrosage modéré, tolère la sécheresse.",
                    'latGPS' => 47.50162,
                    'lngGPS' => -0.56809,
                    'images' => [ "images/species/cycas_1.png", "images/species/cycas_2.png", "images/species/cycas_3.png" ]
                ]
            ]
        ],
        [
            'title' => "Bain de couleurs",
            'areaDescription' => "Toutes la palette des plantes tinctoriales servant de teintures végétales.",
            'latGPS' => 47.50166,
            'lngGPS' => -0.569141,
            'species' => [
                [
                    "LatinName" => "Rubia tinctorum",
                    "CommonName" => "Garance des teinturiers",
                    "Origin" => "Originaire d'Europe et d'Asie.",
                    "Characteristics" => "Plante herbacée grimpante à tiges carrées et rugueuses.\nRacines rouges contenant des pigments utilisés pour la teinture.\nFleurs petites et jaunâtres.",
                    "Utility" => "Utilisée depuis l’Antiquité pour produire un colorant rouge (alizarine).\nAussi employée en médecine traditionnelle pour ses propriétés diurétiques.",
                    "CultivationCondition" => "Sol léger, bien drainé et calcaire.\nExposition ensoleillée ou mi-ombre.\nArrosage modéré, supporte la sécheresse.",
                    'latGPS' => 47.50161,
                    'lngGPS' => -0.56896,
                    'images' => [ "images/species/rubia_1.png", "images/species/rubia_2.png", "images/species/rubia_3.png" ]
                ],
                [
                    "LatinName" => "Isatis tinctoria",
                    "CommonName" => "Pastel des teinturiers",
                    "Origin" => "Originaire d'Europe et d'Asie centrale.",
                    "Characteristics" => "Plante bisannuelle à grandes feuilles allongées et fleurs jaunes en grappes.\nLes feuilles contiennent un pigment bleu utilisé pour la teinture (indigo).",
                    "Utility" => "Historiquement utilisée pour produire du bleu pastel, notamment en France (Pays de Cocagne).\nEmployée aussi en médecine traditionnelle pour ses propriétés antiseptiques.",
                    "CultivationCondition" => "Sol calcaire, bien drainé.\nExposition en plein soleil.\nRésistante à la sécheresse et aux climats tempérés.",
                    'latGPS' => 47.50165,
                    'lngGPS' => -0.5689,
                    'images' => [  "images/species/isatis_1.png", "images/species/isatis_2.png", "images/species/isatis_3.png" ]
                ],
                [
                    "LatinName" => "Carthamus tinctorius",
                    "CommonName" => "Carthame des teinturiers",
                    "Origin" => "Originaire d'Asie et du bassin méditerranéen.",
                    "Characteristics" => "Plante herbacée annuelle à fleurs jaunes, orangées ou rouges.\nSes fleurs et ses graines sont utilisées pour la teinture et la production d'huile.",
                    "Utility" => "Source de colorant jaune et rouge pour l'industrie textile.\nHuile extraite des graines utilisée en alimentation et cosmétique.",
                    "CultivationCondition" => "Sol bien drainé, plutôt pauvre.\nExposition plein soleil.\nTolère bien la sécheresse et la chaleur.",
                    'latGPS' => 47.50174,
                    'lngGPS' => -0.56891,
                    'images' => [ "images/species/carthamus_1.png", "images/species/carthamus_2.png", "images/species/carthamus_3.png" ],
                    'quiz' => [
                        'question' => "Quel est l'usage principal du Carthame des teinturiers ?",
                        'answers' => [
                            "Production de colorants",
                            "Fabrication de vin",
                            "Plante médicinale contre la fièvre",
                            "Production de latex naturel"
                        ],
                        'correctAnswer' => "Production de colorants"
                    ],
                    'challenge' => [
                        'description' => "Une plante aux couleurs flamboyantes était autrefois utilisée pour teindre les vêtements. Peux-tu deviner de quelle espèce il s'agit ?",
                        'hints' => [
                            "Cette plante produit des fleurs jaune-orangé éclatantes.",
                            "Elle est utilisée depuis des siècles pour la fabrication de teintures naturelles.",
                            "On l’utilise aussi pour produire une huile végétale appréciée en cuisine et en cosmétique.",
                            "Elle tolère bien la sécheresse et pousse dans des sols bien drainés.",
                            "Je me souviens ! La plante que nous cherchons est le Carthame des teinturiers, aussi appelé Carthamus tinctorius.",
                        ],
                    ],
                ],
                [
                    "LatinName" => "Haematoxylum campechianum",
                    "CommonName" => "Bois de Campêche",
                    "Origin" => "Originaire d'Amérique centrale et des Caraïbes.",
                    "Characteristics" => "Petit arbre à bois dense et riche en pigments colorés.\nFeuilles composées et fleurs jaunes parfumées.\nLe bois produit une teinture violet-rouge.",
                    "Utility" => "Principalement utilisé pour la teinture textile et la fabrication d'encre.\nEmployé en médecine traditionnelle pour ses propriétés antioxydantes.",
                    "CultivationCondition" => "Sol bien drainé et riche.\nExposition ensoleillée.\nRésiste aux climats chauds et secs.",
                    'latGPS' => 47.50179,
                    'lngGPS' => -0.56889,
                    'images' => [ "images/species/haematoxylum_1.png", "images/species/haematoxylum_2.png", "images/species/haematoxylum_3.png" ]
                ]
    
            ]
        ],
        [
            'title' => "La Roseraie",
            'areaDescription' => "Immergez-vous au coeur de la nouvelle roseraie autour d’une scénographie totalement dédiée à cette plante emblématique de l’Anjou ! Sans oublier le coin des amoureux …",
            'latGPS' => 47.502219,
            'lngGPS' => -0.569910,
            'species' => [
                [
                    "LatinName" => "Rosa gallica",
                    "CommonName" => "Rose de Provins",
                    "Origin" => "Originaire d'Europe centrale et méridionale.",
                    "Characteristics" => "Arbuste à feuilles caduques produisant des fleurs simples ou semi-doubles roses à rouge foncé.\nFloraison en début d’été.\nForte teneur en huiles essentielles.",
                    "Utility" => "Utilisée en parfumerie et en phytothérapie pour ses propriétés apaisantes.\nTraditionnellement employée pour la fabrication de confitures et sirops.",
                    "CultivationCondition" => "Sol riche, bien drainé et légèrement acide.\nExposition ensoleillée.\nArrosage régulier mais sans excès.",
                    'latGPS' => 47.50227,
                    'lngGPS' => -0.56984,
                    'images' => [ "images/species/rosa_gallica_1.png", "images/species/rosa_gallica_2.png", "images/species/rosa_gallica_3.png" ],
                    'quiz' => [
                        'question' => "Quelle est l'utilisation principale de la Rose de Provins ?",
                        'answers' => [
                            "Fabrication de parfums et produits cosmétiques",
                            "Utilisation comme engrais naturel",
                            "Source de bois de construction",
                            "Production de fruits comestibles"
                        ],
                        'correctAnswer' => "Fabrication de parfums et produits cosmétiques"
                    ],
                    'challenge' => [
                        'description' => "Cette plante est célèbre pour son parfum et ses pétales délicats. On l’utilise depuis des siècles en parfumerie. Peux-tu deviner de quelle rose il s'agit ?",
                        'hints' => [
                            "Elle produit de magnifiques fleurs rouges ou roses.",
                            "Elle est originaire d’Europe centrale et méridionale et est cultivée depuis l’Antiquité.",
                            "Ses pétales sont utilisés pour fabriquer des parfums et des huiles essentielles.",
                            "On l’appelle parfois la Rose de Provins, car elle était autrefois cultivée dans cette ville française.",
                            "Je me souviens ! La plante que nous cherchons est Rosa gallica, la Rose de Provins.",
                        ],
                    ],
                ],
                [
                    "LatinName" => "Rosa centifolia",
                    "CommonName" => "Rose de mai",
                    "Origin" => "Originaire d’Europe et largement cultivée en Méditerranée.",
                    "Characteristics" => "Rosier arbustif aux fleurs très doubles et parfumées.\nFloraison printanière et estivale.\nTiges légèrement épineuses.",
                    "Utility" => "Cultivée pour la production d’huiles essentielles en parfumerie.\nAppréciée pour la confection de pot-pourris et cosmétiques naturels.",
                    "CultivationCondition" => "Sol bien drainé et fertile.\nExposition plein soleil.\nBesoin d’une taille annuelle pour une floraison optimale.",
                    'latGPS' => 47.50224,
                    'lngGPS' => -0.56975,
                    'images' => [ "images/species/rosa_centifolia_1.png", "images/species/rosa_centifolia_2.png", "images/species/rosa_centifolia_3.png" ]
                ],
                [
                    "LatinName" => "Rosa rugosa",
                    "CommonName" => "Rosier rugueux",
                    "Origin" => "Originaire d’Asie de l’Est (Chine, Japon, Corée).",
                    "Characteristics" => "Arbuste très résistant avec des feuilles gaufrées et des fleurs roses parfumées.\nProduisant des cynorrhodons riches en vitamine C.\nTrès rustique et tolérant aux conditions difficiles.",
                    "Utility" => "Utilisé en haies défensives et pour stabiliser les dunes de sable.\nSes fruits sont utilisés en tisanes et confitures.",
                    "CultivationCondition" => "Sol bien drainé mais tolère une grande variété de sols.\nExposition plein soleil.\nPeu d’entretien nécessaire, très résistant aux maladies.",
                    'latGPS' => 47.5022,
                    'lngGPS' => -0.56998,
                    'images' => [ "images/species/rosa_rugosa_1.png", "images/species/rosa_rugosa_2.png", "images/species/rosa_rugosa_3.png" ]
                ],
                [
                    "LatinName" => "Rosa damascena",
                    "CommonName" => "Rose de Damas",
                    "Origin" => "Originaire du Moyen-Orient, notamment de Syrie et de Turquie.",
                    "Characteristics" => "Rosier à fleurs doubles et très parfumées.\nFloraison unique en début d’été.\nFleurs utilisées pour extraire l’eau et l’huile essentielle de rose.",
                    "Utility" => "Essentielle en parfumerie et cosmétique.\nEmployée en phytothérapie pour ses propriétés relaxantes et tonifiantes.",
                    "CultivationCondition" => "Sol fertile, bien drainé et légèrement alcalin.\nExposition ensoleillée.\nArrosage modéré mais régulier.",
                    'latGPS' => 47.50216,
                    'lngGPS' => -0.5699,
                    'images' => [ "images/species/rosa_damascena_1.png", "images/species/rosa_damascena_2.png", "images/species/rosa_damascena_3.png" ]
                ]
            ]
        ],
        [
            'title' => "L'allée des grands-mères",
            'areaDescription' => "Une allée de la transmission intergénérationnelle avec ces recettes de grands-mères illustrées et appétissantes.",
            'latGPS' => 47.501974,
            'lngGPS' => -0.571391,
            'species' => [
                [
                    "LatinName" => "Thymus vulgaris",
                    "CommonName" => "Thym",
                    "Origin" => "Originaire du bassin méditerranéen.",
                    "Characteristics" => "Petite plante vivace aux feuilles persistantes et aromatiques.\nFleurs violettes attirant les pollinisateurs.\nFort parfum utilisé en cuisine et en médecine.",
                    "Utility" => "Utilisé en cuisine pour aromatiser les plats.\nEn infusion, il possède des vertus antiseptiques et digestives.",
                    "CultivationCondition" => "Sol bien drainé, sableux ou rocailleux.\nExposition plein soleil.\nArrosage modéré, supporte bien la sécheresse.",
                    'latGPS' => 47.50216,
                    'lngGPS' => -0.57117,
                    'images' => [ "images/species/thymus_1.png", "images/species/thymus_2.png", "images/species/thymus_3.png" ]
                ],
                [
                    "LatinName" => "Rosmarinus officinalis",
                    "CommonName" => "Romarin",
                    "Origin" => "Originaire des régions méditerranéennes.",
                    "Characteristics" => "Arbuste persistant à feuilles étroites et très aromatiques.\nFleurs bleuâtres attirant les abeilles.\nTrès résistant à la sécheresse.",
                    "Utility" => "Utilisé comme condiment en cuisine.\nEmployé en infusion pour ses propriétés stimulantes et digestives.",
                    "CultivationCondition" => "Sol bien drainé, pauvre et calcaire.\nExposition plein soleil.\nTolère la sécheresse et les climats chauds.",
                    'latGPS' => 47.50206,
                    'lngGPS' => -0.57104,
                    'images' => [ "images/species/rosmarinus_1.png", "images/species/rosmarinus_2.png", "images/species/rosmarinus_3.png" ]
                ],
                [
                    "LatinName" => "Mentha spicata",
                    "CommonName" => "Menthe verte",
                    "Origin" => "Originaire d'Europe et d'Asie.",
                    "Characteristics" => "Plante vivace au feuillage vert clair très parfumé.\nCroissance rapide et envahissante.\nFleurs violettes en épis attirant les insectes pollinisateurs.",
                    "Utility" => "Utilisée en cuisine et en infusion pour ses propriétés rafraîchissantes et digestives.\nEmployée aussi en phytothérapie pour apaiser les maux de tête et les troubles gastriques.",
                    "CultivationCondition" => "Sol riche et humide mais bien drainé.\nExposition soleil ou mi-ombre.\nArrosage régulier pour éviter le dessèchement.",
                    'latGPS' => 47.50202,
                    'lngGPS' => -0.57098,
                    'images' => [ "images/species/mentha_1.png", "images/species/mentha_2.png", "images/species/mentha_3.png" ]
                ],
                [
                    "LatinName" => "Foeniculum vulgare",
                    "CommonName" => "Fenouil",
                    "Origin" => "Originaire du bassin méditerranéen.",
                    "Characteristics" => "Plante aromatique à longues tiges et feuillage finement découpé.\nFleurs jaunes en ombelles en été.\nSaveur anisée caractéristique.",
                    "Utility" => "Utilisé en cuisine pour aromatiser les plats.\nEn infusion, il favorise la digestion et réduit les ballonnements.",
                    "CultivationCondition" => "Sol bien drainé, léger et riche en nutriments.\nExposition plein soleil.\nArrosage modéré, tolère bien la sécheresse.",
                    'latGPS' => 47.50197,
                    'lngGPS' => -0.5709,
                    'images' => [ "images/species/foeniculum_1.png", "images/species/foeniculum_2.png", "images/species/foeniculum_3.png" ],
                    'quiz' => [
                        'question' => "Quel goût caractéristique a le fenouil ?",
                        'answers' => [
                            "Anisé",
                            "Acidulé",
                            "Piquant",
                            "Amer"
                        ],
                        'correctAnswer' => "Anisé"
                    ],
                    'challenge' => [
                        'description' => "Une plante au parfum anisé et aux tiges élancées… On l’utilise en cuisine et en médecine. Peux-tu retrouver son nom ?",
                        'hints' => [
                            "Cette plante a des feuilles fines et plumeuses.",
                            "Elle produit des fleurs jaunes regroupées en ombelles.",
                            "Son goût est très apprécié en cuisine et rappelle l’anis.",
                            "Elle est utilisée en infusion pour favoriser la digestion.",
                            "Je me souviens ! La plante que nous cherchons est le fenouil, aussi appelé Foeniculum vulgare.",
                        ],
                    ],
                ]
            ]
        ],
        [
            'title' => "Les Bayous de Louisiane",
            'areaDescription' => "Parcours découverte de cette végétation atypique. Voyage et dépaysement garantis !",
            'latGPS' => 47.501762,
            'lngGPS' => -0.572003,
            'species' => [
                [
                    "LatinName" => "Taxodium distichum",
                    "CommonName" => "Cyprès chauve",
                    "Origin" => "Originaire du sud-est des États-Unis, notamment des zones marécageuses.",
                    "Characteristics" => "Grand conifère caduc pouvant atteindre 30 mètres de haut.\nCapable de survivre dans l'eau grâce à ses pneumatophores (racines aériennes).\nFeuillage fin devenant orangé à l’automne.",
                    "Utility" => "Utilisé pour le reboisement des zones humides.\nSon bois résistant à l'eau est apprécié en construction et ébénisterie.",
                    "CultivationCondition" => "Sol humide, voire inondé.\nExposition ensoleillée.\nTolère aussi les périodes de sécheresse une fois établi.",
                    'latGPS' => 47.50175,
                    'lngGPS' => -0.57181,
                    'images' => [ "images/species/taxodium_1.png", "images/species/taxodium_2.png", "images/species/taxodium_3.png" ]
                ],
                [
                    "LatinName" => "Nelumbo lutea",
                    "CommonName" => "Lotus jaune",
                    "Origin" => "Originaire des marécages et cours d’eau d’Amérique du Nord.",
                    "Characteristics" => "Plante aquatique aux grandes feuilles flottantes et aux fleurs jaunes parfumées.\nSes graines peuvent rester viables pendant des siècles.\nPlante rhizomateuse très résistante.",
                    "Utility" => "Utilisée dans la purification de l'eau.\nRhizomes et graines comestibles, consommés par certaines populations amérindiennes.",
                    "CultivationCondition" => "Eau calme et peu profonde (15 à 50 cm de profondeur).\nExposition plein soleil.\nTempératures chaudes favorisant la floraison.",
                    'latGPS' => 47.50173,
                    'lngGPS' => -0.57225,
                    'images' => [ "images/species/nelumbo_1.png", "images/species/nelumbo_2.png", "images/species/nelumbo_3.png" ],
                    'quiz' => [
                        'question' => "Pourquoi le Lotus jaune est-il considéré comme une plante remarquable ?",
                        'answers' => [
                            "Ses graines peuvent rester viables pendant des siècles",
                            "Il pousse uniquement sur des sols rocheux",
                            "Ses fleurs sont invisibles à l'œil nu",
                            "Il n'a pas besoin d'eau pour se développer"
                        ],
                        'correctAnswer' => "Ses graines peuvent rester viables pendant des siècles"
                    ],
                    'challenge' => [
                        'description' => "Une plante aquatique aux fleurs éclatantes… Elle pousse dans les eaux calmes et ses graines sont légendaires. Peux-tu deviner de quelle espèce il s'agit ?",
                        'hints' => [
                            "Ses grandes feuilles flottent sur l’eau et sont parfaitement imperméables.",
                            "Elle produit de belles fleurs jaunes qui attirent les insectes pollinisateurs.",
                            "Ses graines peuvent rester viables pendant des siècles.",
                            "Elle est originaire des marécages et cours d’eau d’Amérique du Nord.",
                            "Je me souviens ! La plante que nous cherchons est le Lotus jaune, aussi appelé Nelumbo lutea.",
                        ],
                    ],
                ],
                [
                    "LatinName" => "Sagittaria latifolia",
                    "CommonName" => "Sagittaire à larges feuilles",
                    "Origin" => "Originaire d'Amérique du Nord, fréquente dans les marais et les étangs.",
                    "Characteristics" => "Plante aquatique vivace à feuilles en forme de flèche.\nFleurs blanches à trois pétales apparaissant en été.\nSes tubercules sont riches en amidon.",
                    "Utility" => "Tubercules consommés par les Amérindiens et parfois cultivés pour l’alimentation.\nUtilisée pour stabiliser les berges et favoriser la biodiversité aquatique.",
                    "CultivationCondition" => "Sol vaseux ou immergé sous quelques centimètres d’eau.\nExposition ensoleillée.\nSupporte bien les climats tempérés à chauds.",
                    'latGPS' => 47.50158,
                    'lngGPS' => -0.57204,
                    'images' => [ "images/species/sagittaria_1.png", "images/species/sagittaria_2.png", "images/species/sagittaria_3.png" ]
                ],
                [
                    "LatinName" => "Iris fulva",
                    "CommonName" => "Iris cuivré",
                    "Origin" => "Originaire des zones humides du sud des États-Unis.",
                    "Characteristics" => "Plante vivace à rhizome produisant des fleurs rouge-cuivré.\nPousse naturellement dans les sols détrempés.\nAttire de nombreux pollinisateurs.",
                    "Utility" => "Utilisé comme plante ornementale pour embellir les zones aquatiques.\nStabilise les sols humides et lutte contre l’érosion des berges.",
                    "CultivationCondition" => "Sol humide, voire immergé.\nExposition soleil ou mi-ombre.\nArrosage constant en dehors des périodes de crue naturelle.",
                    'latGPS' => 47.50155,
                    'lngGPS' => -0.57172,
                    'images' => [ "images/species/iris_1.png", "images/species/iris_2.png", "images/species/iris_3.png" ]
                ]
            ]
        ],
        [
            'title' => "Le Trésor de la Pérouse",
            'areaDescription' => "Tiré d’une histoire vraie de l’Anjou, revivez les grandes explorations des botanistes et aventuriers du XVIIIème siècle à la recherche de trésors botaniques inestimables.",
            'latGPS' => 47.500925,
            'lngGPS' => -0.572340,
            'species' => [
                [
                    "LatinName" => "Cinchona officinalis",
                    "CommonName" => "Quinquina",
                    "Origin" => "Originaire des forêts tropicales d'Amérique du Sud, notamment des Andes.",
                    "Characteristics" => "Petit arbre ou arbuste au feuillage persistant.\nÉcorce riche en quinine, un alcaloïde utilisé contre le paludisme.\nFleurs parfumées de couleur rose pâle.",
                    "Utility" => "Principalement utilisé pour l’extraction de la quinine, un remède contre la malaria.\nEmployé aussi pour ses propriétés fébrifuges et digestives.",
                    "CultivationCondition" => "Sol bien drainé, riche et légèrement acide.\nExposition mi-ombre à ensoleillée.\nClimat tropical humide avec arrosage modéré.",
                    'latGPS' => 47.50109,
                    'lngGPS' => -0.57275,
                    'images' => [ "images/species/cinchona_1.png", "images/species/cinchona_2.png", "images/species/cinchona_3.png" ]
                ],
                [
                    "LatinName" => "Vanilla planifolia",
                    "CommonName" => "Vanillier",
                    "Origin" => "Originaire du Mexique et des forêts tropicales humides d’Amérique centrale.",
                    "Characteristics" => "Liane orchidée à longues tiges grimpantes.\nFleurs jaune pâle produisant des gousses de vanille après pollinisation.\nNécessite un tuteur ou un arbre support pour se développer.",
                    "Utility" => "Source principale de la vanille naturelle utilisée en cuisine et parfumerie.\nExploité aussi en phytothérapie pour ses propriétés apaisantes.",
                    "CultivationCondition" => "Sol riche en humus, bien drainé et légèrement acide.\nExposition mi-ombre, sous couvert forestier.\nClimat chaud et humide, avec arrosage fréquent.",
                    'latGPS' => 47.50099,
                    'lngGPS' => -0.57241,
                    'images' => [ "images/species/vanilla_1.png", "images/species/vanilla_2.png", "images/species/vanilla_3.png" ]
                ],
                [
                    "LatinName" => "Theobroma cacao",
                    "CommonName" => "Cacaoyer",
                    "Origin" => "Originaire des forêts tropicales d'Amérique du Sud.",
                    "Characteristics" => "Petit arbre tropical à grandes feuilles persistantes.\nFleurs petites et blanchâtres poussant directement sur le tronc.\nFruits en cabosses contenant les fèves de cacao.",
                    "Utility" => "Source du chocolat, produit à partir des fèves fermentées et séchées.\nUtilisé aussi en cosmétique et en médecine traditionnelle pour ses propriétés énergisantes.",
                    "CultivationCondition" => "Sol riche, bien drainé et humide.\nExposition mi-ombre, sous canopée.\nTempératures constantes entre 20 et 30°C avec forte humidité.",
                    'latGPS' => 47.50097,
                    'lngGPS' => -0.57287,
                    'images' => [ "images/species/theobroma_1.png", "images/species/theobroma_2.png", "images/species/theobroma_3.png" ],
                    'quiz' => [
                        'question' => "Quelle partie du cacaoyer est utilisée pour produire du chocolat ?",
                        'answers' => [
                            "Les feuilles",
                            "Les fleurs",
                            "Les fèves contenues dans les cabosses",
                            "Les racines"
                        ],
                        'correctAnswer' => "Les fèves contenues dans les cabosses"
                    ],
                    'challenge' => [
                        'description' => "Une plante précieuse, cultivée par les civilisations anciennes pour ses fèves riches et savoureuses. Peux-tu deviner de quelle espèce il s'agit ?",
                        'hints' => [
                            "Cette plante pousse dans les forêts tropicales chaudes et humides.",
                            "Elle produit des fruits en forme de cabosses, contenant des fèves.",
                            "Ces fèves sont fermentées et séchées pour donner un produit très apprécié dans le monde entier.",
                            "Les civilisations précolombiennes utilisaient ses fèves comme monnaie d’échange.",
                            "Je me souviens ! La plante que nous cherchons est le cacaoyer, aussi appelé Theobroma cacao.",
                        ],
                    ],
                ],
                [
                    "LatinName" => "Ceiba pentandra",
                    "CommonName" => "Fromager",
                    "Origin" => "Originaire des régions tropicales d'Amérique du Sud, d'Afrique et d’Asie.",
                    "Characteristics" => "Arbre majestueux pouvant atteindre 60 mètres de haut.\nTronc épineux et branches formant une large canopée.\nProduisant des fruits contenant des fibres soyeuses (kapok).",
                    "Utility" => "Bois utilisé pour la construction de pirogues et d’objets artisanaux.\nFibre de kapok employée pour le rembourrage des coussins et matelas.",
                    "CultivationCondition" => "Sol profond et bien drainé.\nExposition ensoleillée.\nClimat chaud et humide, supporte les périodes de sécheresse.",
                    'latGPS' => 47.50087,
                    'lngGPS' => -0.57256,
                    'images' => [ "images/species/ceiba_1.png", "images/species/ceiba_2.png", "images/species/ceiba_3.png" ]
                ]
            ]
        ],
        [
            'title' => "La serre au papillons",
            'areaDescription' => "Participez à nos animations pour observer nos lâchers spectaculaires et assistez tout au long de la journée à des éclosions en direct !",
            'latGPS' => 47.500479,
            'lngGPS' => -0.571537,
            'species' =>
            [
                [
                    "LatinName" => "Passiflora caerulea",
                    "CommonName" => "Passiflore bleue",
                    "Origin" => "Originaire des régions tropicales et subtropicales d'Amérique du Sud.",
                    "Characteristics" => "Plante grimpante à grandes fleurs bleues et blanches.\nFruits comestibles en forme de grenadille.\nPeut atteindre jusqu'à 10 mètres de hauteur.",
                    "Utility" => "Nourrit certaines espèces de papillons.\nCultivée aussi pour ses fleurs ornementales et ses fruits.",
                    "CultivationCondition" => "Sol bien drainé et légèrement acide.\nExposition ensoleillée ou partiellement ombragée.\nClimat tempéré à subtropical, avec arrosage modéré.",
                    'latGPS' => 47.50051,
                    'lngGPS' => -0.57163,
                    'images' => [ "images/species/passiflora_1.png", "images/species/passiflora_2.png", "images/species/passiflora_3.png" ],
                    'quiz' =>
                    [
                        'question' => "Pourquoi la Passiflore bleue est-elle appréciée dans les jardins ?",
                        'answers' =>
                        [
                            "Pour ses fleurs spectaculaires et son attrait pour les papillons",
                            "Pour ses fruits toxiques",
                            "Parce qu'elle pousse uniquement dans les zones désertiques",
                            "Car elle peut survivre sans lumière"
                        ],
                        'correctAnswer' => "Pour ses fleurs spectaculaires et son attrait pour les papillons"
                    ],
                    'challenge' =>
                    [
                        'description' => "Une plante grimpante aux fleurs exotiques et aux fruits comestibles… Peux-tu retrouver son nom ?",
                        'hints' => [
                            "Cette plante produit de magnifiques fleurs aux couleurs vives, avec des pétales blancs et une couronne bleue.",
                            "Ses fruits ovales sont appelés grenadilles et sont parfois comestibles.",
                            "Elle est appréciée pour son attrait pour les papillons et insectes pollinisateurs.",
                            "On la trouve souvent dans les jardins, où elle grimpe sur des treillis et clôtures.",
                            "Je me souviens ! La plante que nous cherchons est la Passiflore bleue, aussi appelée Passiflora caerulea.",
                        ],
                    ],
                ],
                [
                    "LatinName" => "Asclepias curassavica",
                    "CommonName" => "Herbe aux papillons",
                    "Origin" => "Originaire des régions tropicales d'Amérique centrale et des Caraïbes.",
                    "Characteristics" => "Plante vivace à fleurs colorées, généralement orange et jaune.\nAttire de nombreux pollinisateurs, notamment les papillons.",
                    "Utility" => "Attire les papillons monarques, qui pondent leurs œufs sur cette plante.\nUtilisée pour ses propriétés médicinales dans certaines cultures.",
                    "CultivationCondition" => "Sol bien drainé et fertile.\nExposition ensoleillée.\nClimat chaud et humide, avec arrosage modéré.",
                    'latGPS' => 47.50046,
                    'lngGPS' => -0.57165,
                    'images' => [ "images/species/asclepias_1.png", "images/species/asclepias_2.png", "images/species/asclepias_3.png" ]
                ],
                [
                    "LatinName" => "Lantana camara",
                    "CommonName" => "Lantana",
                    "Origin" => "Originaire des régions tropicales et subtropicales des Amériques.",
                    "Characteristics" => "Plante arbustive à fleurs multicolores en petites grappes.\nTrès résistante à la chaleur et aux sols pauvres.",
                    "Utility" => "Plante nectarifère pour les papillons, notamment les colibris.\nUtilisée également en haies décoratives.",
                    "CultivationCondition" => "Sol bien drainé, même sec.\nExposition ensoleillée.\nClimat chaud et sec, tolère les périodes de sécheresse.",
                    'latGPS' => 47.50047,
                    'lngGPS' => -0.57141,
                    'images' => [ "images/species/lantana_1.png", "images/species/lantana_2.png", "images/species/lantana_3.png" ]
                ],
                [
                    "LatinName" => "Buddleja davidii",
                    "CommonName" => "Arbre à papillons",
                    "Origin" => "Originaire de Chine et de Taïwan.",
                    "Characteristics" => "Arbuste ou petit arbre produisant des fleurs parfumées de couleur violette, blanche ou rose.\nTrès attractif pour les papillons.",
                    "Utility" => "Attire de nombreux papillons avec ses fleurs.\nUtilisé comme plante ornementale dans les jardins.",
                    "CultivationCondition" => "Sol bien drainé et légèrement alcalin.\nExposition ensoleillée.\nClimat tempéré, avec arrosage modéré.",
                    'latGPS' => 47.50042,
                    'lngGPS' => -0.57145,
                    'images' => [ "images/species/buddleja_1.png", "images/species/buddleja_2.png", "images/species/buddleja_3.png" ]
                ]
            ]
        ]
    ];

    public function __construct(
        private readonly SluggerInterface $slugger,
        private readonly UserPasswordHasherInterface $hasher
    )
    {

    }

    public function load(ObjectManager $manager): void
    {
        $parcoursAreas = [];
        foreach (self::$initalData as $areaData) {
            $area = (new Area())
                ->setTitle($areaData['title'])
                ->setSlug($this->slugger->slug(strtolower($areaData['title'])))
                ->setInfos($areaData['areaDescription'])
                ->setLatGPS($areaData['latGPS'])
                ->setLngGPS($areaData['lngGPS'])
                ->setRadius(0);
            $parcoursAreas[] = $area;
            $manager->persist($area);

            $areaSpeciesData = $areaData['species'];
            $areaSpecies = [];
            foreach ($areaSpeciesData as $speciesData) {
                $species = (new Species())
                    ->setLatinName($speciesData['LatinName'])
                    ->setSlug($this->slugger->slug(strtolower($speciesData['LatinName'])))
                    ->setCommonName($speciesData['CommonName'])
                    ->setOrigin($speciesData['Origin'])
                    ->setCharacteristics($speciesData['Characteristics'])
                    ->setUtility($speciesData['Utility'])
                    ->setCultivationCondition($speciesData['CultivationCondition'])
                    ->setLatGPS($speciesData['latGPS'])
                    ->setLngGPS($speciesData['lngGPS'])
                    ->setImages($speciesData['images']);
                $areaSpecies[] = $species;
                $manager->persist($species);

                if (isset($speciesData['quiz'])) {
                    $quizData = $speciesData['quiz'];
                    $quiz = (new Quiz())
                        ->setSpecies($species)
                        ->setQuestion($quizData['question'])
                        ->setAnswers($quizData['answers'])
                        ->setCorrectAnswer($quizData['correctAnswer']);
                    $manager->persist($quiz);
                }

                if (isset($speciesData['challenge'])) {
                    $challengeData = $speciesData['challenge'];
                    $challenge = (new Challenge())
                        ->setType('text')
                        ->setDescription($challengeData['description'])
                        ->setHints($challengeData['hints'])
                        ->setSpeciesToGuess($species)
                        ->setArea($area);
                    $manager->persist($challenge);
                }

            }

            foreach ($areaSpecies as $s) {
                $challenge->addSpecies($s);
            }
        }

        $parcours = (new Journey())
            ->setTitle("Le Mystère du Professeur Verdant")
            ->setInfos("Aider le Professeur Verdant à retrouver ses recherches sur la faune et la flore du parc !")
            ->setSlug($this->slugger->slug(strtolower("Le Mystère du Professeur Verdant")));
        foreach ($parcoursAreas as $a) {
            $parcours->addArea($a);
        }
        $manager->persist($parcours);

        $admin = new User();
        $admin->setEmail("admin@admin.fr")
            ->setPassword($this->hasher->hashPassword($admin, "admin"))
            ->setUsername("Admin")
            ->setSettings((new Settings())->setLanguage('fr'))
            ->setGlossary((new Glossary())->setUser($admin))
            ->setStatistics((new Statistics())->setUser($admin))
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $testUser = new User();
        $testUser->setEmail("test@test.fr")
            ->setPassword($this->hasher->hashPassword($testUser, "0000"))
            ->setUsername("Test")
            ->setSettings((new Settings())->setLanguage('fr'))
            ->setGlossary((new Glossary())->setUser($testUser))
            ->setStatistics((new Statistics())->setUser($testUser));
        $manager->persist($testUser);

        $manager->flush();
    }

}
