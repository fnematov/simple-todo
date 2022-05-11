<?php

use App\Core\Router;

?>
<div class="container">
    <div class="header">Create tasks</div>
    <div class="content">
        <form action="<?= Router::to('site/create') ?>" method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="body">Body</label>
                <textarea id="body" name="content" cols="30" rows="10" required minlength="10" maxlength="1024"></textarea>
            </div>
            <input type="submit" class="button">
        </form>
    </div>
</div>