    <!-- Footer -->
    <footer class="site-footer">
        <p>&copy; <?= e(SITE_NAME) ?> <?= date('Y') ?></p>
        <p>Powered by: &copy; newznab</p>
        <p><a href="#">terms and conditions</a></p>
    </footer>

    <script>
    function toggleDropdown(el) {
        var dd = el.querySelector('.nav-dropdown');
        // Close all other dropdowns and remove open state from nav-items
        document.querySelectorAll('.nav-item').forEach(function(item) {
            if (item !== el) {
                item.classList.remove('open');
                var d = item.querySelector('.nav-dropdown');
                if (d) d.classList.remove('open');
            }
        });
        if (dd) {
            dd.classList.toggle('open');
            el.classList.toggle('open');
        }
    }
    function toggleSubmenu(el) {
        var sub = el.querySelector('.nav-submenu');
        if (sub) sub.classList.toggle('open');
    }
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.nav-item')) {
            document.querySelectorAll('.nav-dropdown.open').forEach(function(d) {
                d.classList.remove('open');
            });
            document.querySelectorAll('.nav-item.open').forEach(function(item) {
                item.classList.remove('open');
            });
        }
    });
    </script>
</body>
</html>
