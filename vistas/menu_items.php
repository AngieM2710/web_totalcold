<?php
$menuItems = [];

if (!empty($_SESSION['administrador']) && $_SESSION['administrador'] == 1) {
    $ruta = "../files/usuarios/";
    $menuItems = [
        [
            'title' => 'Gestión de Personal',
            'icon' => 'fa-users-cog',
            'pages' => ['usuarios.php', 'tecnicos.php', 'clientes.php'],
            'submenu' => [
                ['href' => 'usuarios.php', 'icon' => 'fa-user-shield', 'text' => 'Administradores'],
                ['href' => 'tecnicos.php', 'icon' => 'fa-tools', 'text' => 'Técnicos'],
                ['href' => 'clientes.php', 'icon' => 'fa-user-tie', 'text' => 'Clientes'],
            ],
        ],
        [
            'title' => 'Gestión de Recursos',
            'icon' => 'fa-boxes',
            'pages' => ['items_cobro.php', 'categorias.php', 'equipos.php'],
            'submenu' => [
                ['href' => 'items_cobro.php', 'icon' => 'fa-wind', 'text' => 'Item de cobros'],
                ['href' => 'categorias.php', 'icon' => 'fa-layer-group', 'text' => 'Servicios'],
                ['href' => 'equipos.php', 'icon' => 'fa-wind', 'text' => 'Equipos/Aires'],
            ],
        ],
        [
            'title' => 'Gestión Operativa',
            'icon' => 'fa-calendar-alt',
            'pages' => ['agenda.php', 'ordenes.php'],
            'submenu' => [
                ['href' => 'agenda.php', 'icon' => 'fa-calendar-day', 'text' => 'Planificación'],
                ['href' => 'Agendar.php', 'icon' => 'fa-user-clock', 'text' => 'Ordenes de Servicio'],
            ],
        ],
        [
            'title' => 'Reportes',
            'icon' => 'fa-chart-bar',
            'pages' => ['dashboard.php'],
            'submenu' => [
                ['href' => 'dashboard.php', 'icon' => 'fa-analytics', 'text' => 'Panel General'],
            ],
        ],
    ];
} elseif (!empty($_SESSION['tecnico']) && $_SESSION['tecnico'] == 1) {
    $ruta = "../files/usuarios/tecnicos/";
    $menuItems = [
        [
            'title' => 'Inicio',
            'icon' => 'fa-home',
            'href' => 'usuarios.php',
            'pages' => ['usuarios.php'],
            'submenu' => [],
        ],
        [
            'title' => 'Mis Agendas',
            'icon' => 'fa-calendar-check',
            'pages' => ['agenda_tecnico.php', 'historial_ordenes.php','ver_orden.php'],
            'submenu' => [
                ['href' => 'agenda_tecnico.php', 'icon' => 'fa-calendar-day', 'text' => 'Agenda del Día'],
                ['href' => 'historial_ordenes.php', 'icon' => 'fa-list-check', 'text' => 'Historial de Órdenes'],
            ],
        ],
        [
            'title' => 'Reportes Técnicos',
            'icon' => 'fa-file-alt',
            'pages' => ['reportes_tecnico.php'],
            'submenu' => [
                ['href' => 'reportes_tecnico.php', 'icon' => 'fa-file-lines', 'text' => 'Reportes Realizados'],
            ],
        ],
    ];
}
