<aside class="app-sidebar" id="appSidebar">

    <div class="sidebar-content">


        <nav class="sidebar-nav">


            <!-- =================================================
                 DASHBOARD
                 ================================================= -->

            <div class="nav-section">

                <a
                    href="index.php"
                    class="nav-link <?= empty($pg) ? 'active' : '' ?>"
                >

                    <i class="bi bi-speedometer2"></i>

                    <span>
                        Dashboard
                    </span>

                </a>

            </div>


            <!-- =================================================
                 LIBRARY
                 ================================================= -->

            <div class="nav-section">

                <div class="nav-section-title">
                    LIBRARY
                </div>


                <a
                    href="index.php?pg=books"
                    class="nav-link <?= $pg === 'books' ? 'active' : '' ?>"
                >

                    <i class="bi bi-book"></i>

                    <span>
                        Books
                    </span>

                </a>


                <a
                    href="index.php?pg=authors"
                    class="nav-link <?= $pg === 'authors' ? 'active' : '' ?>"
                >

                    <i class="bi bi-person"></i>

                    <span>
                        Authors
                    </span>

                </a>


                <a
                    href="index.php?pg=publishers"
                    class="nav-link <?= $pg === 'publishers' ? 'active' : '' ?>"
                >

                    <i class="bi bi-building"></i>

                    <span>
                        Publishers
                    </span>

                </a>


                <a
                    href="index.php?pg=book_types"
                    class="nav-link <?= $pg === 'book_types' ? 'active' : '' ?>"
                >

                    <i class="bi bi-tags"></i>

                    <span>
                        Book Types
                    </span>

                </a>


                <a
                    href="index.php?pg=readers"
                    class="nav-link <?= $pg === 'readers' ? 'active' : '' ?>"
                >

                    <i class="bi bi-people"></i>

                    <span>
                        Readers
                    </span>

                </a>

            </div>


            <!-- =================================================
                 FILES
                 ================================================= -->

            <div class="nav-section">

                <div class="nav-section-title">
                    FILES
                </div>


                <a
                    href="#"
                    class="nav-link"
                >

                    <i class="bi bi-folder"></i>

                    <span>
                        File Manager
                    </span>

                </a>


                <a
                    href="#"
                    class="nav-link"
                >

                    <i class="bi bi-arrow-repeat"></i>

                    <span>
                        Scan Library
                    </span>

                </a>


                <a
                    href="#"
                    class="nav-link"
                >

                    <i class="bi bi-exclamation-triangle"></i>

                    <span>
                        File Errors
                    </span>

                </a>

            </div>


            <!-- =================================================
                 ADMINISTRATION
                 ================================================= -->

            <div class="nav-section">

                <div class="nav-section-title">
                    ADMINISTRATION
                </div>


                <a
                    href="admin/"
                    class="nav-link"
                >

                    <i class="bi bi-gear"></i>

                    <span>
                        Administration
                    </span>

                </a>

            </div>


        </nav>

    </div>

</aside>


<!-- =====================================================
     Main Content
     ===================================================== -->

<main class="app-main">

    <div class="content-wrapper">