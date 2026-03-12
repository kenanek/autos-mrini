<?php
$files = [
    'resources/views/admin/vehicles/index.blade.php' => [
        'Gestion des Véhicules' => 'Gestión de Vehículos',
        'Véhicules / Liste' => 'Vehículos / Lista',
        'Ajouter un véhicule' => 'Añadir vehículo',
        'Filtres' => 'Filtros',
        'Tous les véhicules' => 'Todos los vehículos',
        'Titre' => 'Título',
        'Prix' => 'Precio',
        'Kilométrage' => 'Kilometraje',
        'Statut' => 'Estado',
        'Actions' => 'Acciones',
        'Éditer' => 'Editar',
        'Supprimer' => 'Eliminar',
        'Supprimer ce véhicule ?' => '¿Eliminar este vehículo?',
        'Disponible' => 'Disponible',
        'Vendu' => 'Vendido',
        'Réservé' => 'Reservado',
        'Brouillon' => 'Borrador',
        'Aucun véhicule trouvé' => 'No se encontraron vehículos',
        'Ajouter' => 'Añadir'
    ],
    'resources/views/admin/brands/index.blade.php' => [
        'Gestion des Marques' => 'Gestión de Marcas',
        'Gestion / Marques' => 'Gestión / Marcas',
        'Marques' => 'Marcas',
        'Ajouter' => 'Añadir',
        'Nom' => 'Nombre',
        'Pays' => 'País',
        'Statut' => 'Estado',
        'Actions' => 'Acciones',
        'Actif' => 'Activo',
        'Inactif' => 'Inactivo',
        'Éditer' => 'Editar',
        'Supprimer' => 'Eliminar',
        'Supprimer?' => '¿Eliminar?'
    ],
    'resources/views/admin/models/index.blade.php' => [
        'Gestion des Modèles' => 'Gestión de Modelos',
        'Gestion / Modèles' => 'Gestión / Modelos',
        'Modèles' => 'Modelos',
        'Ajouter' => 'Añadir',
        'Marque' => 'Marca',
        'Nom' => 'Nombre',
        'Actions' => 'Acciones',
        'Éditer' => 'Editar',
        'Supprimer' => 'Eliminar',
        'Supprimer?' => '¿Eliminar?'
    ],
    'resources/views/admin/features/index.blade.php' => [
        'Gestion des Caractéristiques' => 'Gestión de Características',
        'Gestion / Caractéristiques' => 'Gestión / Características',
        'Caractéristiques' => 'Características',
        'Ajouter' => 'Añadir',
        'Catégorie' => 'Categoría',
        'Nom' => 'Nombre',
        'Icone' => 'Icono',
        'Actions' => 'Acciones',
        'Éditer' => 'Editar',
        'Supprimer' => 'Eliminar',
        'Supprimer?' => '¿Eliminar?'
    ],
    'resources/views/admin/inquiries/index.blade.php' => [
        'Gestion des Demandes' => 'Gestión de Consultas',
        'Demandes / Liste' => 'Consultas / Lista',
        'Consultas' => 'Consultas',
        'Client' => 'Cliente',
        'Sujet' => 'Asunto',
        'Type' => 'Tipo',
        'Statut' => 'Estado',
        'Date' => 'Fecha',
        'Actions' => 'Acciones',
        'Voir' => 'Ver',
        'Supprimer' => 'Eliminar',
        'Nouveau' => 'Nuevo',
        'Lu' => 'Leído',
        'Répondu' => 'Respondido',
        'Général' => 'General',
        'Véhicule' => 'Vehículo',
        'Essai' => 'Prueba',
        'Financement' => 'Financiación'
    ],
    'resources/views/admin/users/index.blade.php' => [
        'Gestion des Utilisateurs' => 'Gestión de Usuarios',
        'Paramètres / Utilisateurs' => 'Ajustes / Usuarios',
        'Utilisateurs' => 'Usuarios',
        'Ajouter' => 'Añadir',
        'Nom' => 'Nombre',
        'Email' => 'Email',
        'Role' => 'Rol',
        'Actions' => 'Acciones',
        'Éditer' => 'Editar',
        'Supprimer' => 'Eliminar',
        'Supprimer cet utilisateur ?' => '¿Eliminar este usuario?'
    ]
];

foreach ($files as $path => $translations) {
    if (file_exists($path)) {
        $content = file_get_contents($path);
        foreach ($translations as $from => $to) {
            $content = str_replace($from, $to, $content);
        }
        file_put_contents($path, $content);
        echo "Translated $path\n";
    }
}
echo "Spanish translations complete.\n";
