<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\CarModel;
use App\Models\CustomAttribute;
use App\Models\Inquiry;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleFeature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin User ─────────────────────────────────────────────────
        User::create([
            'name' => 'Admin Mrini',
            'email' => 'admin@autosmrini.ma',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '+212 600-000000',
        ]);

        User::create([
            'name' => 'Editor Mrini',
            'email' => 'editor@autosmrini.ma',
            'password' => bcrypt('password'),
            'role' => 'editor',
            'phone' => '+212 611-111111',
        ]);

        // ── Brands ─────────────────────────────────────────────────────
        $brandsData = [
            ['name' => 'Toyota', 'country' => 'Japan', 'description' => 'One of the world\'s largest automotive manufacturers known for reliability.'],
            ['name' => 'Mercedes-Benz', 'country' => 'Germany', 'description' => 'German luxury automobile manufacturer, a division of Daimler AG.'],
            ['name' => 'BMW', 'country' => 'Germany', 'description' => 'Bavarian Motor Works - German multinational luxury vehicles manufacturer.'],
            ['name' => 'Audi', 'country' => 'Germany', 'description' => 'German automobile manufacturer that designs, engineers, and produces luxury vehicles.'],
            ['name' => 'Volkswagen', 'country' => 'Germany', 'description' => 'The people\'s car - one of the world\'s leading automobile manufacturers.'],
            ['name' => 'Renault', 'country' => 'France', 'description' => 'French multinational automobile manufacturer with a rich heritage.'],
            ['name' => 'Peugeot', 'country' => 'France', 'description' => 'French automotive manufacturer, part of the Stellantis group.'],
            ['name' => 'Dacia', 'country' => 'Romania', 'description' => 'Romanian car manufacturer owned by Renault, known for affordable vehicles.'],
            ['name' => 'Hyundai', 'country' => 'South Korea', 'description' => 'South Korean multinational automotive manufacturer.'],
            ['name' => 'Kia', 'country' => 'South Korea', 'description' => 'South Korea\'s second largest automobile manufacturer.'],
            ['name' => 'Ford', 'country' => 'USA', 'description' => 'American multinational automaker, one of the Big Three.'],
            ['name' => 'Fiat', 'country' => 'Italy', 'description' => 'Italian automobile manufacturer, part of Stellantis.'],
            ['name' => 'Citroën', 'country' => 'France', 'description' => 'French automobile manufacturer known for creative and innovative designs.'],
            ['name' => 'Nissan', 'country' => 'Japan', 'description' => 'Japanese multinational automobile manufacturer, part of the Renault-Nissan-Mitsubishi alliance.'],
            ['name' => 'Honda', 'country' => 'Japan', 'description' => 'Japanese multinational conglomerate known for automobiles and motorcycles.'],
        ];

        $brands = [];
        foreach ($brandsData as $i => $data) {
            $brands[$data['name']] = Brand::create(array_merge($data, [
                'slug' => Str::slug($data['name']),
                'sort_order' => $i,
            ]));
        }

        // ── Car Models ─────────────────────────────────────────────────
        $modelsData = [
            'Toyota' => ['Corolla', 'Camry', 'RAV4', 'Hilux', 'Yaris', 'Land Cruiser', 'C-HR'],
            'Mercedes-Benz' => ['Classe A', 'Classe C', 'Classe E', 'GLA', 'GLC', 'GLE', 'Classe S'],
            'BMW' => ['Série 1', 'Série 3', 'Série 5', 'X1', 'X3', 'X5', 'X6'],
            'Audi' => ['A1', 'A3', 'A4', 'A6', 'Q2', 'Q3', 'Q5', 'Q7'],
            'Volkswagen' => ['Golf', 'Polo', 'Passat', 'Tiguan', 'T-Roc', 'Touareg', 'ID.4'],
            'Renault' => ['Clio', 'Megane', 'Captur', 'Kadjar', 'Arkana', 'Austral'],
            'Peugeot' => ['208', '308', '3008', '5008', '2008', '508'],
            'Dacia' => ['Sandero', 'Duster', 'Logan', 'Jogger', 'Spring'],
            'Hyundai' => ['i10', 'i20', 'Tucson', 'Santa Fe', 'Kona', 'Creta'],
            'Kia' => ['Picanto', 'Rio', 'Sportage', 'Sorento', 'Ceed', 'Niro'],
            'Ford' => ['Fiesta', 'Focus', 'Puma', 'Kuga', 'Mustang', 'Ranger'],
            'Fiat' => ['500', 'Panda', 'Tipo', '500X', 'Doblo'],
            'Citroën' => ['C3', 'C4', 'C5 Aircross', 'Berlingo', 'C3 Aircross'],
            'Nissan' => ['Micra', 'Qashqai', 'Juke', 'X-Trail', 'Leaf'],
            'Honda' => ['Civic', 'CR-V', 'HR-V', 'Jazz', 'Accord'],
        ];

        $carModels = [];
        foreach ($modelsData as $brandName => $models) {
            foreach ($models as $modelName) {
                $carModels[$brandName . ' ' . $modelName] = CarModel::create([
                    'brand_id' => $brands[$brandName]->id,
                    'name' => $modelName,
                    'slug' => Str::slug($modelName),
                ]);
            }
        }

        // ── Vehicle Features ───────────────────────────────────────────
        $featuresData = [
            // Safety
            ['name' => 'ABS', 'category' => 'safety', 'icon' => 'shield-check'],
            ['name' => 'Airbags frontaux', 'category' => 'safety', 'icon' => 'shield'],
            ['name' => 'Airbags latéraux', 'category' => 'safety', 'icon' => 'shield'],
            ['name' => 'ESP', 'category' => 'safety', 'icon' => 'shield-check'],
            ['name' => 'Régulateur de vitesse', 'category' => 'safety', 'icon' => 'gauge'],
            ['name' => 'Caméra de recul', 'category' => 'safety', 'icon' => 'camera'],
            ['name' => 'Radar de stationnement', 'category' => 'safety', 'icon' => 'radar'],
            ['name' => 'Aide au stationnement', 'category' => 'safety', 'icon' => 'parking'],
            ['name' => 'Alerte de franchissement de ligne', 'category' => 'safety', 'icon' => 'alert-triangle'],

            // Comfort
            ['name' => 'Climatisation', 'category' => 'comfort', 'icon' => 'wind'],
            ['name' => 'Climatisation automatique', 'category' => 'comfort', 'icon' => 'thermometer'],
            ['name' => 'Sièges chauffants', 'category' => 'comfort', 'icon' => 'flame'],
            ['name' => 'Sièges en cuir', 'category' => 'comfort', 'icon' => 'armchair'],
            ['name' => 'Volant multifonction', 'category' => 'comfort', 'icon' => 'steering-wheel'],
            ['name' => 'Vitres électriques', 'category' => 'comfort', 'icon' => 'square'],
            ['name' => 'Rétroviseurs électriques', 'category' => 'comfort', 'icon' => 'flip-horizontal'],
            ['name' => 'Fermeture centralisée', 'category' => 'comfort', 'icon' => 'lock'],
            ['name' => 'Démarrage sans clé', 'category' => 'comfort', 'icon' => 'key'],
            ['name' => 'Toit ouvrant', 'category' => 'comfort', 'icon' => 'sun'],

            // Technology
            ['name' => 'GPS Navigation', 'category' => 'technology', 'icon' => 'map-pin'],
            ['name' => 'Bluetooth', 'category' => 'technology', 'icon' => 'bluetooth'],
            ['name' => 'Écran tactile', 'category' => 'technology', 'icon' => 'monitor'],
            ['name' => 'Apple CarPlay', 'category' => 'technology', 'icon' => 'smartphone'],
            ['name' => 'Android Auto', 'category' => 'technology', 'icon' => 'smartphone'],
            ['name' => 'USB', 'category' => 'technology', 'icon' => 'usb'],
            ['name' => 'Tableau de bord numérique', 'category' => 'technology', 'icon' => 'layout-dashboard'],
            ['name' => 'Phares LED', 'category' => 'technology', 'icon' => 'lightbulb'],
            ['name' => 'Feux de jour LED', 'category' => 'technology', 'icon' => 'sun'],

            // Exterior
            ['name' => 'Jantes alliage', 'category' => 'exterior', 'icon' => 'circle'],
            ['name' => 'Barres de toit', 'category' => 'exterior', 'icon' => 'minus'],
            ['name' => 'Vitres teintées', 'category' => 'exterior', 'icon' => 'eye-off'],
            ['name' => 'Peinture métallisée', 'category' => 'exterior', 'icon' => 'paintbrush'],
        ];

        $features = [];
        foreach ($featuresData as $i => $data) {
            $features[] = VehicleFeature::create(array_merge($data, [
                'slug' => Str::slug($data['name']),
                'sort_order' => $i,
            ]));
        }

        // ── Custom Attributes ──────────────────────────────────────────
        $customAttrs = [
            ['name' => 'Nombre de cylindres', 'type' => 'number', 'unit' => null],
            ['name' => 'Couple moteur', 'type' => 'text', 'unit' => 'Nm'],
            ['name' => 'Vitesse max', 'type' => 'number', 'unit' => 'km/h'],
            ['name' => '0-100 km/h', 'type' => 'text', 'unit' => 's'],
            ['name' => 'Consommation mixte', 'type' => 'text', 'unit' => 'L/100km'],
            ['name' => 'Émissions CO2', 'type' => 'number', 'unit' => 'g/km'],
            ['name' => 'Volume du coffre', 'type' => 'number', 'unit' => 'L'],
            ['name' => 'Garantie', 'type' => 'select', 'unit' => null, 'options' => ['6 mois', '1 an', '2 ans', '3 ans', '5 ans']],
            ['name' => 'Première main', 'type' => 'boolean', 'unit' => null],
            ['name' => 'Dédouané', 'type' => 'boolean', 'unit' => null],
        ];

        foreach ($customAttrs as $attr) {
            CustomAttribute::create(array_merge($attr, [
                'slug' => Str::slug($attr['name']),
            ]));
        }

        // ── Vehicles ───────────────────────────────────────────────────
        $vehiclesData = [
            [
                'brand' => 'Mercedes-Benz', 'model' => 'Classe C',
                'title' => 'Mercedes-Benz Classe C 220d AMG Line',
                'year' => 2023, 'price' => 480000, 'old_price' => 520000,
                'mileage' => 15000, 'fuel_type' => 'diesel', 'transmission' => 'automatic',
                'body_type' => 'sedan', 'color' => 'Noir Obsidienne', 'interior_color' => 'Cuir Noir',
                'engine_size' => 1993, 'horsepower' => 200, 'doors' => 4, 'seats' => 5,
                'condition' => 'used', 'status' => 'available', 'is_featured' => true,
                'short_description' => 'Mercedes Classe C en excellente condition, pack AMG Line complet.',
                'description' => "Cette Mercedes-Benz Classe C 220d incarne l'élégance et la performance. Équipée du pack AMG Line, elle offre un design sportif et raffiné. Le moteur diesel de 200 ch assure des performances remarquables tout en maintenant une consommation maîtrisée.\n\nÉquipements premium inclus : système MBUX avec écran tactile, sièges en cuir, climatisation automatique bi-zone, phares LED intelligents, et bien plus encore.",
            ],
            [
                'brand' => 'BMW', 'model' => 'Série 3',
                'title' => 'BMW 320i M Sport 2024',
                'year' => 2024, 'price' => 520000, 'old_price' => null,
                'mileage' => 5000, 'fuel_type' => 'gasoline', 'transmission' => 'automatic',
                'body_type' => 'sedan', 'color' => 'Blanc Alpin', 'interior_color' => 'Cuir Vernasca Noir',
                'engine_size' => 1998, 'horsepower' => 184, 'doors' => 4, 'seats' => 5,
                'condition' => 'certified', 'status' => 'available', 'is_featured' => true,
                'short_description' => 'BMW Série 3 quasiment neuve, pack M Sport, toutes options.',
                'description' => "BMW 320i M Sport en état exceptionnel avec seulement 5 000 km. Cette berline sportive allie performance et confort avec son moteur 4 cylindres turbo de 184 ch.\n\nLe pack M Sport comprend : boucliers M Sport, jantes alliage 18\", suspension sport, volant M gainé cuir, et sellerie sport.",
            ],
            [
                'brand' => 'Audi', 'model' => 'Q5',
                'title' => 'Audi Q5 2.0 TDI S-Line Quattro',
                'year' => 2022, 'price' => 450000, 'old_price' => 490000,
                'mileage' => 35000, 'fuel_type' => 'diesel', 'transmission' => 'automatic',
                'body_type' => 'suv', 'color' => 'Gris Daytona', 'interior_color' => 'Cuir Noir',
                'engine_size' => 1968, 'horsepower' => 204, 'doors' => 5, 'seats' => 5,
                'condition' => 'used', 'status' => 'available', 'is_featured' => true,
                'short_description' => 'Audi Q5 S-Line avec transmission Quattro intégrale.',
                'description' => "L'Audi Q5 combine polyvalence et élégance. Avec sa transmission intégrale Quattro et son moteur diesel performant, il est parfait pour tous les terrains.\n\nPack S-Line extérieur et intérieur, Virtual Cockpit, MMI Navigation Plus, toit panoramique.",
            ],
            [
                'brand' => 'Toyota', 'model' => 'RAV4',
                'title' => 'Toyota RAV4 Hybrid 2023',
                'year' => 2023, 'price' => 380000, 'old_price' => null,
                'mileage' => 20000, 'fuel_type' => 'hybrid', 'transmission' => 'automatic',
                'body_type' => 'suv', 'color' => 'Gris Métallisé', 'interior_color' => 'Tissu Noir',
                'engine_size' => 2487, 'horsepower' => 222, 'doors' => 5, 'seats' => 5,
                'condition' => 'used', 'status' => 'available', 'is_featured' => true,
                'short_description' => 'Toyota RAV4 Hybrid, faible consommation et fiabilité légendaire.',
                'description' => "Le Toyota RAV4 Hybrid offre le meilleur des deux mondes : la puissance d'un moteur thermique et l'efficacité d'un moteur électrique.\n\nConsommation mixte réduite, système Toyota Safety Sense, écran tactile 8\", caméra de recul, et la fiabilité légendaire Toyota.",
            ],
            [
                'brand' => 'Volkswagen', 'model' => 'Golf',
                'title' => 'Volkswagen Golf 8 R-Line 2023',
                'year' => 2023, 'price' => 320000, 'old_price' => 350000,
                'mileage' => 12000, 'fuel_type' => 'gasoline', 'transmission' => 'automatic',
                'body_type' => 'hatchback', 'color' => 'Bleu Atlantique', 'interior_color' => 'Tissu/Alcantara Noir',
                'engine_size' => 1498, 'horsepower' => 150, 'doors' => 5, 'seats' => 5,
                'condition' => 'used', 'status' => 'available', 'is_featured' => true,
                'short_description' => 'Golf 8 R-Line, la référence des compactes premium.',
                'description' => "La Volkswagen Golf 8 R-Line combine le style sportif avec le confort et la technologie de pointe. Son design R-Line distinctif la démarque sur la route.\n\nDigital Cockpit Pro, Discover Pro Navigation, IQ.LIGHT phares matriciels LED, et une conduite agile grâce à sa direction progressive.",
            ],
            [
                'brand' => 'Renault', 'model' => 'Clio',
                'title' => 'Renault Clio 5 Intens 1.0 TCe',
                'year' => 2022, 'price' => 165000, 'old_price' => null,
                'mileage' => 28000, 'fuel_type' => 'gasoline', 'transmission' => 'manual',
                'body_type' => 'hatchback', 'color' => 'Rouge Flamme', 'interior_color' => 'Tissu Gris',
                'engine_size' => 999, 'horsepower' => 100, 'doors' => 5, 'seats' => 5,
                'condition' => 'used', 'status' => 'available', 'is_featured' => false,
                'short_description' => 'Clio 5 finition Intens, très bon rapport qualité-prix.',
                'description' => "La Renault Clio 5 Intens offre un équipement généreux dans un format compact et élégant. Idéale pour la ville et les trajets quotidiens.\n\nÉcran EASY LINK 9.3\", instrumentation numérique 7\", climatisation automatique, caméra de recul, et radar de stationnement arrière.",
            ],
            [
                'brand' => 'Peugeot', 'model' => '3008',
                'title' => 'Peugeot 3008 GT Line 1.5 BlueHDi',
                'year' => 2023, 'price' => 350000, 'old_price' => 380000,
                'mileage' => 18000, 'fuel_type' => 'diesel', 'transmission' => 'automatic',
                'body_type' => 'suv', 'color' => 'Noir Perla Nera', 'interior_color' => 'Cuir/Alcantara Noir',
                'engine_size' => 1499, 'horsepower' => 130, 'doors' => 5, 'seats' => 5,
                'condition' => 'used', 'status' => 'available', 'is_featured' => true,
                'short_description' => 'Peugeot 3008 GT Line, le SUV français par excellence.',
                'description' => "Le Peugeot 3008 GT Line séduit par son i-Cockpit et son design avant-gardiste. Un SUV compact qui allie confort, technologie et style.\n\nI-Cockpit avec écran 10\", Night Vision, grip control, toit panoramique, et full LED.",
            ],
            [
                'brand' => 'Dacia', 'model' => 'Duster',
                'title' => 'Dacia Duster Prestige 1.5 dCi 4x4',
                'year' => 2023, 'price' => 220000, 'old_price' => null,
                'mileage' => 10000, 'fuel_type' => 'diesel', 'transmission' => 'manual',
                'body_type' => 'suv', 'color' => 'Vert Cèdre', 'interior_color' => 'Tissu/TEP Noir',
                'engine_size' => 1461, 'horsepower' => 115, 'doors' => 5, 'seats' => 5,
                'condition' => 'used', 'status' => 'available', 'is_featured' => false,
                'short_description' => 'Duster 4x4 Prestige, le SUV adventurier accessible.',
                'description' => "Le Dacia Duster 4x4 Prestige offre une transmission intégrale fiable et un équipement complet à un prix défiant toute concurrence.\n\nNavigation MediaNav, caméra de recul multiview, climatisation automatique, et une capacité tout-terrain remarquable.",
            ],
            [
                'brand' => 'Hyundai', 'model' => 'Tucson',
                'title' => 'Hyundai Tucson Creative 1.6 CRDi',
                'year' => 2022, 'price' => 350000, 'old_price' => 370000,
                'mileage' => 40000, 'fuel_type' => 'diesel', 'transmission' => 'automatic',
                'body_type' => 'suv', 'color' => 'Bleu Teal', 'interior_color' => 'Cuir Noir',
                'engine_size' => 1598, 'horsepower' => 136, 'doors' => 5, 'seats' => 5,
                'condition' => 'used', 'status' => 'available', 'is_featured' => false,
                'short_description' => 'Tucson nouvelle génération avec design révolutionnaire.',
                'description' => "Le Hyundai Tucson nouvelle génération impressionne par son design futuriste et ses équipements high-tech. La finition Creative offre le meilleur de la gamme.\n\nÉcran tactile 10.25\", instrumentation numérique 10.25\", sièges ventilés, charge sans fil smartphone, et caméra 360°.",
            ],
            [
                'brand' => 'BMW', 'model' => 'X3',
                'title' => 'BMW X3 xDrive20d Luxury Line',
                'year' => 2021, 'price' => 420000, 'old_price' => 460000,
                'mileage' => 55000, 'fuel_type' => 'diesel', 'transmission' => 'automatic',
                'body_type' => 'suv', 'color' => 'Mineral White', 'interior_color' => 'Cuir Dakota Cognac',
                'engine_size' => 1995, 'horsepower' => 190, 'doors' => 5, 'seats' => 5,
                'condition' => 'used', 'status' => 'available', 'is_featured' => false,
                'short_description' => 'BMW X3 Luxury Line, SAV premium polyvalent.',
                'description' => "Le BMW X3 xDrive20d Luxury Line offre une conduite dynamique avec la traction intégrale intelligente xDrive. Finition Luxury Line avec chrome et élégance.\n\nSystème BMW iDrive, harman/kardon surround sound, affichage tête haute, et Driving Assistant Professional.",
            ],
            [
                'brand' => 'Mercedes-Benz', 'model' => 'GLA',
                'title' => 'Mercedes GLA 200 Progressive Line',
                'year' => 2023, 'price' => 400000, 'old_price' => null,
                'mileage' => 8000, 'fuel_type' => 'gasoline', 'transmission' => 'automatic',
                'body_type' => 'suv', 'color' => 'Blanc Polaire', 'interior_color' => 'Cuir Noir/Rouge',
                'engine_size' => 1332, 'horsepower' => 163, 'doors' => 5, 'seats' => 5,
                'condition' => 'certified', 'status' => 'available', 'is_featured' => true,
                'short_description' => 'GLA quasiment neuf, SUV compact premium Mercedes.',
                'description' => "Le Mercedes GLA 200 Progressive Line est le SUV compact idéal pour la ville et la route. Son moteur turbo offre une conduite vive et agréable.\n\nSystème MBUX avec commande vocale, sièges sport, pack lumière ambiance, et Advanced Sound System.",
            ],
            [
                'brand' => 'Kia', 'model' => 'Sportage',
                'title' => 'Kia Sportage GT Line 1.6 T-GDi',
                'year' => 2023, 'price' => 340000, 'old_price' => null,
                'mileage' => 15000, 'fuel_type' => 'gasoline', 'transmission' => 'automatic',
                'body_type' => 'suv', 'color' => 'Gris Gravity', 'interior_color' => 'Cuir/Suedine Noir',
                'engine_size' => 1598, 'horsepower' => 180, 'doors' => 5, 'seats' => 5,
                'condition' => 'used', 'status' => 'available', 'is_featured' => false,
                'short_description' => 'Nouveau Sportage GT Line avec écran panoramique.',
                'description' => "Le nouveau Kia Sportage GT Line redéfinit les standards du segment SUV compact. Design audacieux avec la face avant Tiger Nose et l'écran panoramique incurvé.\n\nDouble écran 12.3\", toit panoramique, sièges chauffants/ventilés, harman/kardon, et garantie 7 ans.",
            ],
            [
                'brand' => 'Toyota', 'model' => 'Corolla',
                'title' => 'Toyota Corolla Sedan 1.8 Hybrid',
                'year' => 2024, 'price' => 295000, 'old_price' => null,
                'mileage' => 2000, 'fuel_type' => 'hybrid', 'transmission' => 'automatic',
                'body_type' => 'sedan', 'color' => 'Gris Celestite', 'interior_color' => 'Tissu Noir',
                'engine_size' => 1798, 'horsepower' => 140, 'doors' => 4, 'seats' => 5,
                'condition' => 'new', 'status' => 'available', 'is_featured' => true,
                'short_description' => 'Corolla Hybrid neuve, la berline la plus vendue au monde.',
                'description' => "La Toyota Corolla Sedan Hybrid 2024 combine l'efficience hybride avec le confort d'une berline moderne. Quasiment neuve avec seulement 2000 km.\n\nToyota Safety Sense 3.0, écran multimédia 10.5\", instrumentation digitale 12.3\", et une fiabilité inégalée.",
            ],
            [
                'brand' => 'Ford', 'model' => 'Puma',
                'title' => 'Ford Puma ST-Line 1.0 EcoBoost Hybrid',
                'year' => 2023, 'price' => 280000, 'old_price' => 300000,
                'mileage' => 22000, 'fuel_type' => 'hybrid', 'transmission' => 'manual',
                'body_type' => 'suv', 'color' => 'Bleu Desert Island', 'interior_color' => 'Tissu/Cuir Noir',
                'engine_size' => 999, 'horsepower' => 155, 'doors' => 5, 'seats' => 5,
                'condition' => 'used', 'status' => 'available', 'is_featured' => false,
                'short_description' => 'Ford Puma ST-Line, le crossover fun et connecté.',
                'description' => "Le Ford Puma ST-Line allie un design sportif à une motorisation hybride efficiente. Un crossover compact avec un MegaBox unique dans le coffre.\n\nSYNC 3 avec écran 8\", B&O Sound System, technologie Ford Co-Pilot360, et le MegaBox de 80L dans le coffre.",
            ],
            [
                'brand' => 'Dacia', 'model' => 'Sandero',
                'title' => 'Dacia Sandero Stepway Confort 1.0 TCe',
                'year' => 2023, 'price' => 145000, 'old_price' => null,
                'mileage' => 8000, 'fuel_type' => 'gasoline', 'transmission' => 'manual',
                'body_type' => 'hatchback', 'color' => 'Orange Arizona', 'interior_color' => 'Tissu Gris/Orange',
                'engine_size' => 999, 'horsepower' => 90, 'doors' => 5, 'seats' => 5,
                'condition' => 'used', 'status' => 'available', 'is_featured' => false,
                'short_description' => 'Sandero Stepway, le meilleur rapport qualité-prix du marché.',
                'description' => "La Dacia Sandero Stepway Confort offre un style SUV et un équipement moderne à un prix imbattable. Idéale pour les premiers achats ou les budgets maîtrisés.\n\nMedia Display 8\", climatisation, radar de stationnement, et style Stepway avec protections latérales.",
            ],
            [
                'brand' => 'Audi', 'model' => 'A3',
                'title' => 'Audi A3 Sportback 35 TFSI S-Tronic',
                'year' => 2023, 'price' => 360000, 'old_price' => null,
                'mileage' => 12000, 'fuel_type' => 'gasoline', 'transmission' => 'automatic',
                'body_type' => 'hatchback', 'color' => 'Gris Nano', 'interior_color' => 'Cuir/Alcantara Noir',
                'engine_size' => 1498, 'horsepower' => 150, 'doors' => 5, 'seats' => 5,
                'condition' => 'used', 'status' => 'available', 'is_featured' => false,
                'short_description' => 'A3 Sportback avec boîte S-Tronic et équipement premium.',
                'description' => "L'Audi A3 Sportback 35 TFSI S-Tronic est la compacte premium par excellence. Design élégant, technologie avancée et motorisation efficiente.\n\nVirtual Cockpit Plus 12.3\", MMI Navigation Plus 10.1\", Audi Phone Box, et Matrix LED headlights.",
            ],
            [
                'brand' => 'Peugeot', 'model' => '208',
                'title' => 'Peugeot 208 Allure 1.2 PureTech',
                'year' => 2022, 'price' => 175000, 'old_price' => 195000,
                'mileage' => 30000, 'fuel_type' => 'gasoline', 'transmission' => 'manual',
                'body_type' => 'hatchback', 'color' => 'Jaune Faro', 'interior_color' => 'Tissu/TEP Noir',
                'engine_size' => 1199, 'horsepower' => 100, 'doors' => 5, 'seats' => 5,
                'condition' => 'used', 'status' => 'available', 'is_featured' => false,
                'short_description' => '208 Allure élue voiture de l\'année, i-Cockpit 3D.',
                'description' => "La Peugeot 208 Allure, élue voiture européenne de l'année, offre un style unique et un intérieur révolutionnaire avec le i-Cockpit 3D.\n\nI-Cockpit 3D holographique, écran tactile 10\", caméra de recul, phares LED, et un design primé.",
            ],
            [
                'brand' => 'Nissan', 'model' => 'Qashqai',
                'title' => 'Nissan Qashqai Tekna+ 1.3 DIG-T',
                'year' => 2022, 'price' => 310000, 'old_price' => 340000,
                'mileage' => 32000, 'fuel_type' => 'gasoline', 'transmission' => 'automatic',
                'body_type' => 'suv', 'color' => 'Gris Ceramic', 'interior_color' => 'Cuir Nappa Noir',
                'engine_size' => 1332, 'horsepower' => 158, 'doors' => 5, 'seats' => 5,
                'condition' => 'used', 'status' => 'available', 'is_featured' => false,
                'short_description' => 'Qashqai Tekna+ toutes options, le crossover star.',
                'description' => "Le Nissan Qashqai Tekna+ est le crossover référence dans sa catégorie. Design iconique et technologie ProPILOT pour une conduite semi-autonome.\n\nProPILOT avec Navi-link, affichage tête haute, toit panoramique, Bose Premium Sound, et sièges massants.",
            ],
            // 2 sold vehicles for variety
            [
                'brand' => 'Mercedes-Benz', 'model' => 'Classe E',
                'title' => 'Mercedes-Benz Classe E 300 Avantgarde',
                'year' => 2021, 'price' => 580000, 'old_price' => null,
                'mileage' => 45000, 'fuel_type' => 'gasoline', 'transmission' => 'automatic',
                'body_type' => 'sedan', 'color' => 'Noir Obsidienne', 'interior_color' => 'Cuir Beige',
                'engine_size' => 1991, 'horsepower' => 258, 'doors' => 4, 'seats' => 5,
                'condition' => 'used', 'status' => 'sold', 'is_featured' => false,
                'short_description' => 'Classe E Avantgarde, la berline de prestige.',
                'description' => "La Mercedes-Benz Classe E 300 Avantgarde est la berline de prestige par excellence. Puissance, confort et technologie au plus haut niveau.",
            ],
            [
                'brand' => 'BMW', 'model' => 'X5',
                'title' => 'BMW X5 xDrive30d M Sport',
                'year' => 2022, 'price' => 750000, 'old_price' => null,
                'mileage' => 30000, 'fuel_type' => 'diesel', 'transmission' => 'automatic',
                'body_type' => 'suv', 'color' => 'Phytonic Blue', 'interior_color' => 'Cuir Merino Noir',
                'engine_size' => 2993, 'horsepower' => 286, 'doors' => 5, 'seats' => 5,
                'condition' => 'used', 'status' => 'sold', 'is_featured' => false,
                'short_description' => 'BMW X5 M Sport, le SAV de luxe par excellence.',
                'description' => "Le BMW X5 xDrive30d M Sport combine puissance diesel 6 cylindres avec le luxe absolu. Un SUV premium sans compromis.",
            ],
        ];

        foreach ($vehiclesData as $vData) {
            $brand = $brands[$vData['brand']];
            $model = $carModels[$vData['brand'] . ' ' . $vData['model']];

            $vehicle = Vehicle::create([
                'brand_id' => $brand->id,
                'car_model_id' => $model->id,
                'title' => $vData['title'],
                'slug' => Str::slug($vData['title']) . '-' . Str::random(5),
                'year' => $vData['year'],
                'price' => $vData['price'],
                'old_price' => $vData['old_price'],
                'mileage' => $vData['mileage'],
                'fuel_type' => $vData['fuel_type'],
                'transmission' => $vData['transmission'],
                'body_type' => $vData['body_type'],
                'color' => $vData['color'],
                'interior_color' => $vData['interior_color'],
                'engine_size' => $vData['engine_size'],
                'horsepower' => $vData['horsepower'],
                'doors' => $vData['doors'],
                'seats' => $vData['seats'],
                'description' => $vData['description'],
                'short_description' => $vData['short_description'],
                'condition' => $vData['condition'],
                'status' => $vData['status'],
                'is_featured' => $vData['is_featured'],
                'published_at' => $vData['status'] === 'available' ? now()->subDays(rand(1, 30)) : null,
                'views_count' => rand(10, 500),
            ]);

            // Attach random features (8-15 per vehicle)
            $randomFeatures = collect($features)->random(rand(8, min(15, count($features))));
            $vehicle->features()->attach($randomFeatures->pluck('id'));
        }

        // ── Sample Inquiries ───────────────────────────────────────────
        $inquiries = [
            [
                'name' => 'Ahmed Bennani', 'email' => 'ahmed.bennani@gmail.com',
                'phone' => '+212 661-234567', 'type' => 'vehicle',
                'subject' => 'Disponibilité Mercedes Classe C',
                'message' => 'Bonjour, je suis intéressé par la Mercedes Classe C 220d AMG Line. Est-elle toujours disponible ? Serait-il possible d\'organiser un essai routier ?',
                'status' => 'new',
            ],
            [
                'name' => 'Fatima Zahra Alaoui', 'email' => 'fz.alaoui@yahoo.fr',
                'phone' => '+212 677-890123', 'type' => 'test_drive',
                'subject' => 'Demande d\'essai - BMW 320i',
                'message' => 'Bonjour, j\'aimerais essayer la BMW 320i M Sport. Je suis disponible ce weekend. Merci de me contacter.',
                'status' => 'read',
            ],
            [
                'name' => 'Youssef El Idrissi', 'email' => 'y.elidrissi@hotmail.com',
                'phone' => '+212 655-456789', 'type' => 'financing',
                'subject' => 'Financement Audi Q5',
                'message' => 'Bonjour, je souhaiterais des informations sur les options de financement pour l\'Audi Q5 S-Line. Quel serait le montant de la mensualité sur 48 mois ?',
                'status' => 'replied',
            ],
            [
                'name' => 'Khadija Mouline', 'email' => 'k.mouline@gmail.com',
                'phone' => null, 'type' => 'general',
                'subject' => 'Horaires d\'ouverture',
                'message' => 'Bonjour, quels sont vos horaires d\'ouverture le weekend ? Je souhaiterais passer voir vos véhicules en exposition.',
                'status' => 'new',
            ],
        ];

        $availableVehicles = Vehicle::where('status', 'available')->get();

        foreach ($inquiries as $inqData) {
            Inquiry::create(array_merge($inqData, [
                'vehicle_id' => in_array($inqData['type'], ['vehicle', 'test_drive', 'financing'])
                ? $availableVehicles->random()->id
                : null,
                'read_at' => $inqData['status'] !== 'new' ? now()->subDays(rand(1, 5)) : null,
                'replied_at' => $inqData['status'] === 'replied' ? now()->subDays(rand(0, 3)) : null,
            ]));
        }
    }
}
