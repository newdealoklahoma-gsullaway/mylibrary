<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Books Table View
|--------------------------------------------------------------------------
|
| Expects from controller.php:
|   $rows  array of books (see Books::listForTable)
|
| DataTables-ready: plain <table> with a <thead> and <tbody>. Later:
|   new DataTable('#booksTable', { columnDefs: [{ targets: 'no-sort', orderable: false }] });
|
| Notes for DataTables:
|   - Do NOT output a "no records" row with colspan; DataTables shows its own.
|   - data-order gives DataTables a clean sort value when the display text differs.
|   - Every <tr> must have the same number of <td> as there are <th>.
|
*/

$h = static fn(mixed $v): string => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
?>

<div class="d-flex justify-content-between align-items-center mb-3">

    <h1 class="mb-0"><?= $h($pageHeading) ?></h1>

    <a href="index.php?pg=books&amp;action=add" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        Add Book
    </a>

</div>


<div class="table-responsive">

    <table
        id="booksTable"
        class="table table-striped table-hover align-middle w-100"
    >

        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Publisher</th>
                <th>Type</th>
                <th data-type="num">Year</th>
                <th class="no-sort text-end">Actions</th>
            </tr>
        </thead>

        <tbody>
            <?php if(!empty($rows)){
                foreach ($rows as $row): ?>

                <tr>

                    <td>
                        <a href="index.php?pg=books&amp;details=<?= (int) $row['id'] ?>">
                            <?= $h($row['title']) ?>
                        </a>
                    </td>

                    <td><?= $h($row['author']) ?></td>

                    <td><?= $h($row['publisher']) ?></td>

                    <td><?= $h($row['type']) ?></td>

                    <td data-order="<?= (int) $row['published_year'] ?>">
                        <?= $row['published_year'] ? (int) $row['published_year'] : '' ?>
                    </td>

                    <td class="text-end text-nowrap">

                        <a
                            href="index.php?pg=books&amp;details=<?= (int) $row['id'] ?>"
                            class="btn btn-sm btn-outline-secondary"
                            title="Details"
                        >
                            <i class="bi bi-eye"></i>
                        </a>

                        <a
                            href="index.php?pg=books&amp;action=edit&amp;id=<?= (int) $row['id'] ?>"
                            class="btn btn-sm btn-outline-primary"
                            title="Edit"
                        >
                            <i class="bi bi-pencil"></i>
                        </a>

                        <a
                            href="index.php?pg=books&amp;action=delete&amp;id=<?= (int) $row['id'] ?>"
                            class="btn btn-sm btn-outline-danger"
                            title="Delete"
                        >
                            <i class="bi bi-trash"></i>
                        </a>

                    </td>

                </tr>

            <?php endforeach; 
            }else{
            ?>
            <td colspan="6">No Records found</td>
            <?php } ?>
        </tbody>

    </table>

</div>