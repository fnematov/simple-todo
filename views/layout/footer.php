<div class="container footer">
    <a href="<?= Router::to('admin/index') ?>">Go to admin</a>
    <?php if (!User::guest()): ?>
        <a href="<?= Router::to('auth/logout') ?>">Logout</a>
    <?php endif; ?>
</div>
</body>
</html>