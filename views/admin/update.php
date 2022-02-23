<div class="container">
    <div class="header">Update tasks</div>
    <div class="content">
        <form action="<?= Router::to('admin/update', ['id' => $todo->id]) ?>" method="post">
            <div class="form-group">
                <label for="body">Body</label>
                <textarea id="body" name="content" cols="30" rows="10" required minlength="10"
                          maxlength="1024"><?= $todo->content ?></textarea>
            </div>
            <div class="form-group">
                <label for="status">
                    <input type="checkbox" name="status" id="status" value="1" <?= $todo->status == true ? "checked" : null ?>>
                    Status
                </label>
            </div>
            <input type="submit" class="button">
        </form>
    </div>
</div>