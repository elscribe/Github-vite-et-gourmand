<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Textes publics alignes avec les maquettes Figma.
 *
 * La base contient encore des donnees de demonstration en anglais. Ce service
 * garde un affichage francais coherent sans modifier la base de donnees.
 */
final class MenuPresentation
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function all(): array
    {
        return [
            1 => [
                'order' => 1,
                'title' => 'Menu Noël Tradition',
                'description' => "Foie gras maison, volaille festive, légumes rôtis et dessert gourmand pour vos repas de fin d'année.",
                'image' => '/images/home/menu-noel-tradition.png',
                'image_alt' => 'Table de fête avec volaille et chandeliers',
                'badge' => 'NOËL',
                'people' => 8,
                'price' => 190,
                'regime_label' => 'Classique',
                'theme_label' => 'Noël',
                'status' => 'Stock limité : 5 commandes restantes',
                'detail_status' => '5 commandes restantes',
                'status_type' => 'limited',
                'available' => false,
                'party' => true,
                'conditions' => 'Commande recommandée au moins 14 jours avant la livraison. À conserver au frais jusqu’au service.',
                'detail_description' => "Menu festif traditionnel pour célébrer Noël en famille ou entre amis. Des saveurs classiques revisitées par notre chef traiteur bordelais pour émerveiller vos convives lors de vos réceptions de fin d'année.",
                'detail_theme' => 'Noël / fêtes',
                'detail_images' => [
                    [
                        'src' => '/images/menu-details/noel/hero.png',
                        'alt' => 'Table de Noël avec volaille festive, chandeliers et décorations',
                    ],
                    [
                        'src' => '/images/menu-details/noel/thumb-1.png',
                        'alt' => 'Assiette festive dressée',
                    ],
                    [
                        'src' => '/images/menu-details/noel/thumb-2.png',
                        'alt' => 'Volaille de Noël servie entière',
                    ],
                    [
                        'src' => '/images/menu-details/noel/thumb-3.png',
                        'alt' => 'Velouté de fête',
                    ],
                    [
                        'src' => '/images/menu-details/noel/thumb-4.png',
                        'alt' => 'Bûche pâtissière chocolatée',
                    ],
                    [
                        'src' => '/images/menu-details/noel/thumb-5.png',
                        'alt' => 'Mignardises et desserts de fête',
                    ],
                ],
                'important_conditions' => [
                    'Commande exigée au moins 7 jours avant la date de la prestation.',
                    'Stockage au frais obligatoire dès réception pour préserver la fraîcheur.',
                    'Annulation ou modification possible sans frais avant acceptation finale.',
                    'Contact client obligatoire avant toute modification interne de la commande.',
                ],
                'detail_sections' => [
                    [
                        'title' => 'Entrées',
                        'items' => [
                            ['name' => 'Foie gras maison mi-cuit, chutney de figues', 'tags' => ['gluten', 'œufs']],
                            ['name' => 'Velouté de châtaignes aux éclats de truffe', 'tags' => ['fruits à coque', 'lactose']],
                        ],
                    ],
                    [
                        'title' => 'Plats principaux',
                        'items' => [
                            ['name' => 'Volaille festive farcie aux marrons et herbes fines', 'tags' => ['gluten', 'œufs', 'fruits à coque']],
                            ['name' => "Légumes d'antan rôtis au thym frais", 'tags' => []],
                            ['name' => 'Gratin dauphinois traditionnel à la crème', 'tags' => ['lactose', 'gluten']],
                        ],
                    ],
                    [
                        'title' => 'Desserts',
                        'items' => [
                            ["name" => "Bûche pâtissière au chocolat noir d'origine", 'tags' => ['gluten', 'lactose', 'œufs']],
                            ['name' => 'Assiette de mignardises et macarons de fête', 'tags' => ['gluten', 'fruits à coque']],
                        ],
                    ],
                ],
            ],
            6 => [
                'order' => 2,
                'title' => 'Menu Cocktail Bordelais',
                'description' => 'Bouchées salées, verrines, mini tartines et mignardises pour vos réceptions privées ou professionnelles.',
                'image' => '/images/home/menu-cocktail-bordelais.png',
                'image_alt' => 'Buffet de verrines et bouchées apéritives',
                'badge' => 'BUFFET',
                'people' => 10,
                'price' => 220,
                'regime_label' => 'Classique',
                'theme_label' => 'Cocktail',
                'status' => 'Disponible cette semaine',
                'status_type' => 'available',
                'available' => true,
                'party' => false,
                'conditions' => 'Commande recommandée au moins 7 jours avant l’événement. Les plateaux cocktail sont à restituer après la prestation.',
                'detail_description' => "Formule cocktail dînatoire élégante, idéale pour vos événements professionnels ou réceptions privées à Bordeaux. Une sélection de bouchées raffinées mettant à l'honneur les saveurs emblématiques du Sud-Ouest.",
                'detail_theme' => 'Cocktail / événement',
                'detail_images' => [
                    [
                        'src' => '/images/menu-details/cocktail/hero.png',
                        'alt' => 'Plateaux cocktail avec bouchées salées et verrines',
                    ],
                    [
                        'src' => '/images/menu-details/cocktail/thumb-1.png',
                        'alt' => 'Mini bouchées cocktail colorées',
                    ],
                    [
                        'src' => '/images/menu-details/cocktail/thumb-2.png',
                        'alt' => 'Verrines fraîches pour réception',
                    ],
                    [
                        'src' => '/images/menu-details/cocktail/thumb-3.png',
                        'alt' => 'Plateau de mini tartines',
                    ],
                    [
                        'src' => '/images/menu-details/cocktail/thumb-4.png',
                        'alt' => 'Mignardises cocktail dressées',
                    ],
                    [
                        'src' => '/images/menu-details/cocktail/thumb-5.png',
                        'alt' => 'Assortiment cocktail prêt à servir',
                    ],
                ],
                'important_conditions' => [
                    'Commande exigée au moins 5 jours avant la date de la prestation.',
                    'Annulation ou modification possible sans frais avant acceptation finale.',
                    'Contact client obligatoire avant toute modification interne de la commande.',
                ],
                'detail_sections' => [
                    [
                        'title' => 'Entrées',
                        'items' => [
                            ['name' => 'Bouchées salées assorties de saison', 'tags' => ['gluten', 'lactose']],
                            ['name' => 'Verrines fraîcheur terre-mer', 'tags' => ['œufs']],
                        ],
                    ],
                    [
                        'title' => 'Plats principaux',
                        'items' => [
                            ['name' => 'Mini tartines croustillantes au foie gras du Sud-Ouest', 'tags' => ['gluten', 'fruits à coque']],
                            ['name' => 'Cannelés salés authentiques façon bordelaise', 'tags' => ['gluten', 'lactose', 'œufs']],
                        ],
                    ],
                    [
                        'title' => 'Desserts',
                        'items' => [
                            ['name' => 'Sélection de mignardises sucrées fines', 'tags' => ['gluten', 'lactose', 'fruits à coque']],
                            ['name' => 'Verrines gourmandes aux fruits de saison', 'tags' => []],
                        ],
                    ],
                ],
            ],
            4 => [
                'order' => 3,
                'title' => 'Menu Végé-Gourmand',
                'description' => 'Tartes, légumes rôtis, quiches et desserts végétariens pour un repas complet et savoureux.',
                'image' => '/images/home/menu-vege-gourmand.png',
                'image_alt' => 'Plat végétarien généreux servi sur une table claire',
                'badge' => '100 % VÉGÉTAL',
                'people' => 6,
                'price' => 120,
                'regime_label' => 'Végétarien',
                'theme_label' => 'Végétarien',
                'status' => 'Disponible',
                'status_type' => 'available',
                'available' => true,
                'party' => false,
                'conditions' => 'Commande recommandée au moins 72 heures avant la livraison. Menu à déguster le jour même.',
                'detail_description' => 'Menu 100% végétarien gourmand, coloré et créatif. Des recettes originales élaborées avec passion qui raviront tous vos convives, même les plus habitués aux menus classiques.',
                'detail_theme' => 'Végétarien / convivial',
                'detail_images' => [
                    [
                        'src' => '/images/menu-details/vege/hero.png',
                        'alt' => 'Table végétarienne colorée avec légumes et tartes',
                    ],
                    [
                        'src' => '/images/menu-details/vege/thumb-1.png',
                        'alt' => 'Tarte fine aux légumes de saison',
                    ],
                    [
                        'src' => '/images/menu-details/vege/thumb-2.png',
                        'alt' => 'Salade végétarienne croquante',
                    ],
                    [
                        'src' => '/images/menu-details/vege/thumb-3.png',
                        'alt' => 'Quiche végétarienne généreuse',
                    ],
                    [
                        'src' => '/images/menu-details/vege/thumb-4.png',
                        'alt' => 'Dessert végétarien chocolaté',
                    ],
                    [
                        'src' => '/images/menu-details/vege/thumb-5.png',
                        'alt' => 'Fruits frais dressés',
                    ],
                ],
                'important_conditions' => [
                    'Commande exigée au moins 4 jours avant la date de la prestation.',
                    'Annulation ou modification de devis possible avant acceptation.',
                    'Contact client obligatoire avant toute modification interne de la commande.',
                ],
                'detail_sections' => [
                    [
                        'title' => 'Entrées',
                        'items' => [
                            ['name' => 'Tarte fine feuilletée aux légumes de saison confits', 'tags' => ['gluten', 'lactose']],
                            ['name' => 'Salade composée croquante aux graines torréfiées', 'tags' => ['fruits à coque']],
                        ],
                    ],
                    [
                        'title' => 'Plats principaux',
                        'items' => [
                            ['name' => 'Quiche végétarienne généreuse aux épinards frais', 'tags' => ['gluten', 'lactose', 'œufs']],
                            ['name' => 'Légumes glacés rôtis au miel de pays et romarin', 'tags' => []],
                        ],
                    ],
                    [
                        'title' => 'Desserts',
                        'items' => [
                            ['name' => 'Fondant intense au chocolat noir végétarien', 'tags' => ['gluten', 'œufs']],
                            ['name' => 'Fraîcheur de fruits de saison finement coupés', 'tags' => []],
                        ],
                    ],
                ],
            ],
            3 => [
                'order' => 4,
                'title' => 'Menu Terre & Mer',
                'description' => 'Produits marins et saveurs traditionnelles pour une prestation complète et raffinée.',
                'image' => '/images/home/menu-terre-mer.png',
                'image_alt' => 'Assiette raffinée de poisson avec légumes colorés',
                'badge' => 'ÉVÉNEMENT',
                'people' => 6,
                'price' => 150,
                'regime_label' => 'Classique',
                'theme_label' => 'Réception',
                'status' => '4 commandes restantes',
                'status_type' => 'limited',
                'available' => false,
                'party' => false,
                'conditions' => 'Commande recommandée au moins 10 jours avant la livraison. Matériel de service disponible sur demande.',
                'detail_description' => 'Alliance subtile entre produits de la terre et de la mer. Un menu raffiné qui ravira les palais exigeants avec des produits frais sélectionnés par notre chef.',
                'detail_theme' => 'Classique / raffiné',
                'detail_images' => [
                    [
                        'src' => '/images/menu-details/terre-mer/hero.png',
                        'alt' => 'Assiette raffinée terre et mer avec poisson et légumes',
                    ],
                    [
                        'src' => '/images/menu-details/terre-mer/thumb-1.png',
                        'alt' => 'Carpaccio de Saint-Jacques',
                    ],
                    [
                        'src' => '/images/menu-details/terre-mer/thumb-2.png',
                        'alt' => 'Velouté de homard',
                    ],
                    [
                        'src' => '/images/menu-details/terre-mer/thumb-3.png',
                        'alt' => 'Filet de bar rôti aux herbes',
                    ],
                    [
                        'src' => '/images/menu-details/terre-mer/thumb-4.png',
                        'alt' => 'Tarte fine aux poires',
                    ],
                    [
                        'src' => '/images/menu-details/terre-mer/thumb-5.png',
                        'alt' => 'Dessert au chocolat et fleur de sel',
                    ],
                ],
                'important_conditions' => [
                    'Commande exigée au moins 6 jours avant la date de la prestation.',
                    'Stockage au frais obligatoire dès réception pour préserver la fraîcheur.',
                    'Annulation ou modification possible sans frais avant acceptation finale.',
                    'Contact client obligatoire avant toute modification interne de la commande.',
                ],
                'detail_sections' => [
                    [
                        'title' => 'Entrées',
                        'items' => [
                            ['name' => 'Carpaccio de Saint-Jacques au citron vert', 'tags' => ['crustacés', 'poisson']],
                            ['name' => 'Velouté de homard', 'tags' => ['crustacés', 'lactose']],
                        ],
                    ],
                    [
                        'title' => 'Plats principaux',
                        'items' => [
                            ['name' => 'Filet de bar rôti aux herbes', 'tags' => ['poisson', 'gluten']],
                            ['name' => 'Suprême de volaille aux morilles', 'tags' => ['gluten', 'lactose']],
                            ['name' => 'Accompagnements traditionnels de saison', 'tags' => []],
                        ],
                    ],
                    [
                        'title' => 'Desserts',
                        'items' => [
                            ['name' => 'Tarte fine aux poires et amandes', 'tags' => ['gluten', 'lactose', 'fruits à coque']],
                            ['name' => 'Mousse au chocolat et fleur de sel', 'tags' => ['lactose', 'œufs']],
                        ],
                    ],
                ],
            ],
            2 => [
                'order' => 5,
                'title' => 'Menu Saint-Valentin',
                'description' => 'Entrées, plats et desserts gourmands pour un dîner en amoureux, à partager à la maison.',
                'image' => '/images/home/menu-saint-valentin.png',
                'image_alt' => 'Dessert aux fraises et chocolats sur une table aux bougies',
                'badge' => 'SAINT-VALENTIN',
                'people' => 2,
                'price' => 80,
                'regime_label' => 'Saint-Valentin',
                'theme_label' => 'Saint-Valentin',
                'status' => 'Stock limité : 3 commandes restantes',
                'detail_status' => '3 commandes restantes',
                'status_type' => 'limited',
                'available' => false,
                'party' => true,
                'conditions' => 'Commande recommandée au moins 48 heures avant la livraison. Livraison froide avec conseils de dressage.',
                'detail_description' => 'Un dîner romantique à domicile, pensé pour deux. Des saveurs délicates et un dessert à partager pour une soirée inoubliable.',
                'detail_theme' => 'Saint-Valentin',
                'detail_images' => [
                    [
                        'src' => '/images/menu-details/saint-valentin/hero.png',
                        'alt' => 'Dîner romantique avec dessert et bougies',
                    ],
                    [
                        'src' => '/images/menu-details/saint-valentin/thumb-1.png',
                        'alt' => 'Verrines romantiques',
                    ],
                    [
                        'src' => '/images/menu-details/saint-valentin/thumb-2.png',
                        'alt' => 'Salade tiède de chèvre et miel',
                    ],
                    [
                        'src' => '/images/menu-details/saint-valentin/thumb-3.png',
                        'alt' => 'Plat Saint-Valentin dressé',
                    ],
                    [
                        'src' => '/images/menu-details/saint-valentin/thumb-4.png',
                        'alt' => 'Cœur fondant au chocolat',
                    ],
                    [
                        'src' => '/images/menu-details/saint-valentin/thumb-5.png',
                        'alt' => 'Mignardises assorties',
                    ],
                ],
                'important_conditions' => [
                    'Commande exigée au moins 3 jours avant la date de la prestation.',
                    'Stockage au frais obligatoire dès réception pour préserver la fraîcheur.',
                    'Annulation ou modification possible sans frais avant acceptation finale.',
                    'Contact client obligatoire avant toute modification interne de la commande.',
                ],
                'detail_sections' => [
                    [
                        'title' => 'Entrées',
                        'items' => [
                            ['name' => 'Duo de verrines romantiques', 'tags' => ['œufs', 'lactose']],
                            ['name' => 'Salade tiède de chèvre et miel', 'tags' => ['lactose', 'gluten']],
                        ],
                    ],
                    [
                        'title' => 'Plats principaux',
                        'items' => [
                            ['name' => 'Tournedos Rossini traditionnel', 'tags' => ['gluten', 'œufs']],
                            ['name' => 'Risotto aux Saint-Jacques', 'tags' => ['crustacés', 'lactose']],
                        ],
                    ],
                    [
                        'title' => 'Desserts',
                        'items' => [
                            ['name' => 'Cœur fondant au chocolat pour deux', 'tags' => ['gluten', 'lactose', 'œufs']],
                            ['name' => 'Mignardises assorties et petits fours', 'tags' => []],
                        ],
                    ],
                ],
            ],
            5 => [
                'order' => 6,
                'title' => 'Menu Pâques en Famille',
                'description' => 'Plats printaniers, desserts colorés et mignardises pour un repas de fête convivial.',
                'image' => '/images/home/menu-paques-famille.png',
                'image_alt' => 'Buffet de Pâques familial avec gâteau et fleurs',
                'badge' => 'PÂQUES',
                'people' => 8,
                'price' => 170,
                'regime_label' => 'Pâques',
                'theme_label' => 'Pâques',
                'status' => 'Disponible cette semaine',
                'detail_status' => 'Disponible',
                'status_type' => 'available',
                'available' => true,
                'party' => true,
                'conditions' => 'Commande recommandée au moins 48 heures avant la livraison. Desserts et mignardises à conserver au frais.',
                'detail_description' => 'Célébrez Pâques en famille avec un menu printanier et généreux. Des plats colorés et festifs préparés avec les premiers légumes de saison.',
                'detail_theme' => 'Pâques / famille',
                'detail_images' => [
                    [
                        'src' => '/images/menu-details/paques/hero.png',
                        'alt' => 'Buffet de Pâques avec gâteau, fleurs et mignardises',
                    ],
                    [
                        'src' => '/images/menu-details/paques/thumb-1.png',
                        'alt' => 'Terrine printanière aux légumes',
                    ],
                    [
                        'src' => '/images/menu-details/paques/thumb-2.png',
                        'alt' => "Velouté d'asperges vertes",
                    ],
                    [
                        'src' => '/images/menu-details/paques/thumb-3.png',
                        'alt' => "Gigot d'agneau pascal",
                    ],
                    [
                        'src' => '/images/menu-details/paques/thumb-4.png',
                        'alt' => 'Nid de Pâques au chocolat',
                    ],
                    [
                        'src' => '/images/menu-details/paques/thumb-5.png',
                        'alt' => 'Mignardises printanières',
                    ],
                ],
                'important_conditions' => [
                    'Commande exigée au moins 6 jours avant la date de la prestation.',
                    'Stockage au frais obligatoire dès réception pour préserver la fraîcheur.',
                    'Annulation ou modification possible sans frais avant acceptation finale.',
                    'Contact client obligatoire avant toute modification interne de la commande.',
                ],
                'detail_sections' => [
                    [
                        'title' => 'Entrées',
                        'items' => [
                            ['name' => 'Terrine de printemps aux petits légumes', 'tags' => ['gluten', 'œufs']],
                            ['name' => "Velouté d'asperges vertes de saison", 'tags' => ['lactose']],
                        ],
                    ],
                    [
                        'title' => 'Plats principaux',
                        'items' => [
                            ['name' => "Gigot d'agneau pascal rôti", 'tags' => ['gluten']],
                            ['name' => 'Gratin de légumes printaniers au fromage', 'tags' => ['lactose', 'gluten']],
                            ['name' => 'Accompagnements de saison du maraîcher', 'tags' => []],
                        ],
                    ],
                    [
                        'title' => 'Desserts',
                        'items' => [
                            ['name' => 'Nid de Pâques au chocolat intense', 'tags' => ['gluten', 'lactose', 'œufs', 'fruits à coque']],
                            ['name' => 'Mignardises printanières parfumées', 'tags' => []],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $menu
     *
     * @return array<string, mixed>
     */
    public static function forMenu(array $menu): array
    {
        $menuId = (int) ($menu['id_menu'] ?? 0);

        return self::all()[$menuId] ?? [
            'title' => (string) ($menu['titre'] ?? ''),
            'description' => (string) ($menu['description'] ?? ''),
            'people' => (int) ($menu['nombre_personnes_minimum'] ?? 0),
            'price' => (float) ($menu['prix_minimum'] ?? 0),
            'regime_label' => self::regimeLabel((string) ($menu['regime'] ?? '')),
            'theme_label' => self::themeLabel((string) ($menu['theme'] ?? '')),
            'status' => ((int) ($menu['stock_disponible'] ?? 0)) . ' commandes disponibles',
            'conditions' => (string) ($menu['conditions'] ?? ''),
        ];
    }

    public static function themeLabel(string $label): string
    {
        return [
            'Christmas' => 'Noël',
            'Cocktail' => 'Cocktail',
            'Family' => 'Famille',
            'Prestige' => 'Réception',
            'Vegetarian' => 'Végétarien',
            'Wedding' => 'Mariage',
        ][$label] ?? $label;
    }

    public static function regimeLabel(string $label): string
    {
        return [
            'Classic' => 'Classique',
            'Gluten-free' => 'Sans gluten',
            'Vegan' => 'Vegan',
            'Vegetarian' => 'Végétarien',
        ][$label] ?? $label;
    }

    public static function allergenLabel(string $label): string
    {
        return [
            'Celery' => 'Céleri',
            'Cereals containing gluten' => 'Céréales contenant du gluten',
            'Crustaceans' => 'Crustacés',
            'Eggs' => 'Œufs',
            'Fish' => 'Poisson',
            'Lupin' => 'Lupin',
            'Milk' => 'Lait',
            'Molluscs' => 'Mollusques',
            'Mustard' => 'Moutarde',
            'Nuts' => 'Fruits à coque',
            'Peanuts' => 'Arachides',
            'Sesame' => 'Sésame',
            'Soybeans' => 'Soja',
            'Sulphur dioxide and sulphites' => 'Sulfites',
        ][$label] ?? $label;
    }
}
