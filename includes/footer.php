    <!-- Footer -->
    <footer class="site-footer">
        <p>&copy; <?= e(SITE_NAME) ?> <?= date('Y') ?></p>
        <p>Powered by: &copy; newznab</p>
        <p><a href="#">terms and conditions</a></p>
    </footer>

    <script>
    function toggleDropdown(el) {
        var dd = el.querySelector('.nav-dropdown');
        // Close all other dropdowns
        document.querySelectorAll('.nav-dropdown.open').forEach(function(d) {
            if (d !== dd) d.classList.remove('open');
        });
        if (dd) dd.classList.toggle('open');
    }
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.nav-item')) {
            document.querySelectorAll('.nav-dropdown.open').forEach(function(d) {
                d.classList.remove('open');
            });
        }
    });
    </script>
</body>
</html>
