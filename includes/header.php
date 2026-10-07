<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title><?= htmlspecialchars($pageTitle ?? 'MyLibrary') ?></title>


    <!-- Bootstrap 5 -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- Bootstrap Icons -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >


    <!-- MyLibrary Theme -->

    <link
        rel="stylesheet"
        href="assets/css/app.css"
    >

</head>


<body>


<!-- =====================================================
     Application Header
     ===================================================== -->

<header class="app-header">

    <nav class="navbar navbar-expand navbar-dark">


        <!-- Brand -->

        <a
            href="index.php"
            class="navbar-brand app-brand"
        >

            <span class="brand-icon">
                <i class="bi bi-book-half"></i>
            </span>

            <span class="brand-text">
                MyLibrary
            </span>

        </a>


        <!-- Sidebar Toggle -->

        <button
            type="button"
            class="btn btn-link sidebar-toggle"
            id="sidebarToggle"
            aria-label="Toggle sidebar"
            title="Toggle Sidebar"
        >

            <i class="bi bi-list"></i>

        </button>


        <!-- Right Navigation -->

        <ul class="navbar-nav ms-auto">

            <li class="nav-item">

                <a
                    class="nav-link"
                    href="index.php"
                    title="Home"
                >

                    <i class="bi bi-house"></i>

                </a>

            </li>


            <li class="nav-item">

                <a
                    class="nav-link"
                    href="#"
                    title="Help"
                >

                    <i class="bi bi-question-circle"></i>

                </a>

            </li>

        </ul>


    </nav>

</header>


<!-- =====================================================
     Application Wrapper
     ===================================================== -->

<div class="app-wrapper">