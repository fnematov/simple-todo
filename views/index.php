<?php

use App\Core\Request;
use App\Core\Router;

?>
<div class="container">
    <div class="header">
        <div class="title">
            All tasks
            <a href="<?= Router::to('site/create') ?>" class="button ml-10">Create task</a>
        </div>
        <div class="sort-block">
            <span>Sort by: </span>
            <a href="<?= Router::to('site/index', ['sort' => 'name', 'direction' => Request::get('direction') == 'desc' ? 'asc' : 'desc']) ?>"
               class="<?= (Request::get('sort') == 'name' ? 'active' : '') . (Request::get('direction') == 'desc' ? ' asc' : ' desc') ?>"
            >
                Username
            </a>
            <a href="<?= Router::to('site/index', ['sort' => 'email', 'direction' => Request::get('direction') == 'desc' ? 'asc' : 'desc']) ?>"
               class="<?= (Request::get('sort') == 'email' ? 'active' : '') . (Request::get('direction') == 'desc' ? ' asc' : ' desc') ?>"
            >
                Email
            </a>
            <a href="<?= Router::to('site/index', ['sort' => 'status', 'direction' => Request::get('direction') == 'desc' ? 'asc' : 'desc']) ?>"
               class="<?= (Request::get('sort') == 'status' ? 'active' : '') . (Request::get('direction') == 'desc' ? ' asc' : ' desc') ?>"
            >
                Status
            </a>
        </div>
    </div>
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