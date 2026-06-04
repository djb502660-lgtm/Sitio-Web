<?php

declare(strict_types=1);

/**
 * Punto de entrada legacy (subcarpeta). Preferir URL raíz: /Sitio-Web/
 */
require_once __DIR__ . '/router.php';
cafeesquina_dispatch(trim((string) ($_GET['route'] ?? ''), '/'));
