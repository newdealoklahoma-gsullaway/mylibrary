<?php
declare(strict_types=1);

/*
 * Development Error Reporting
 *
 * Move this to config.php when the application
 * configuration is established.
 */

require_once __DIR__ . '/config/config.php';



$pg = $_GET['pg'] ?? 'books';

$pg = basename($pg);

$controller = __DIR__ . '/pages/' . $pg . '/controller.php';

if (!is_file($controller)) {
    http_response_code(404);
    die('Page not found.');
}

/*
 * Page title
 */
$pageTitle = ucfirst(str_replace('_', ' ', $pg));

/*
 * Common header
 */
require __DIR__ . '/includes/header.php';

/*
 * Sidebar
 */
require __DIR__ . '/includes/sidebar.php';

/*
 * Requested page controller
 */
require $controller;

/*
 * Common footer
 */
require __DIR__ . '/includes/footer.php';