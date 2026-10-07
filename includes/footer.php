    </div><!-- /.content-wrapper -->

</main><!-- /.app-main -->


</div><!-- /.app-wrapper -->


<!-- =====================================================
     Footer
     ===================================================== -->

<footer class="app-footer">

    <div class="container-fluid">

        <span>
            MyLibrary
        </span>

        <span class="footer-right">
            Digital Library
        </span>

    </div>

</footer>


<!-- =====================================================
     Bootstrap JavaScript
     ===================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
></script>


<!-- =====================================================
     Sidebar Toggle
     ===================================================== -->

<script>

document.addEventListener('DOMContentLoaded', function () {

    const toggle = document.getElementById('sidebarToggle');

    if (toggle) {

        toggle.addEventListener('click', function () {

            document.body.classList.toggle('sidebar-collapsed');

        });

    }

});

</script>


</body>
</html>