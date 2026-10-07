<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Books Controller
|--------------------------------------------------------------------------
|
| Routes:
|   ?pg=books                       -> view.php    (table)
|   ?pg=books&details=ID            -> details.php
|   ?pg=books&action=add            -> form.php
|   ?pg=books&action=edit&id=ID     -> form.php
|   ?pg=books&action=delete&id=ID   -> form.php   (soft delete later)
|
| Controllers fetch data and set variables. Views only display them.
|
*/

$books = new Books();


/*
 * Details request
 */
if (isset($_GET['details'])) {

    $id   = (int) $_GET['details'];
    $book = $books->get($id);

    if ($book === null) {
        http_response_code(404);
        die('Book not found.');
    }

    require __DIR__ . '/details.php';
    return;
}


/*
 * Action request
 */
if (isset($_GET['action'])) {

    switch ($_GET['action']) {

        case 'add':
            $book = null;
            require __DIR__ . '/form.php';
            break;

        case 'edit':
        case 'delete':
            $book = $books->get((int) ($_GET['id'] ?? 0));

            if ($book === null) {
                http_response_code(404);
                die('Book not found.');
            }

            require __DIR__ . '/form.php';
            break;

        default:
            http_response_code(400);
            die('Invalid book action.');
    }

    return;
}


/*
 * Default: table view
 */
$pageHeading = 'Books';
$rows        = $db->listForTable();

require __DIR__ . '/view.php';