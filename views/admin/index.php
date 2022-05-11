<?php
use App\Core\Router;
?>
<div class="container">
    <div class="header">Welcome to admin panel</div>
    <?php if (count($todos->items) > 0): ?>
        <div class="content">
            <ul class="tasks">
                <?php foreach ($todos->items as $todo): ?>
                    <li>
                        <div class="task-body">
                            <div class="task <?= $todo->status == true ? 'stroked' : null ?>">
                                <?= $todo->content ?>
                            </div>
                            <div class="creator">
                                <a href="mailto:<?= $todo->email ?>"><?= $todo->name ?></a>
                            </div>
                        </div>
                        <a href="<?= Router::to('admin/update', ['id' => $todo->id]) ?>">
                            Edit
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php if ($todos->total_pages > 1): ?>
            <div class="pagination">
                <ul>
                    <?php for ($i = 1; $i <= $todos->total_pages; $i++): ?>
                        <li>
                            <a href="<?= Router::current(['page' => $i]) ?>"
                               class="<?= $todos->current_page == $i ? 'active' : '' ?>">
                                <?= $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>