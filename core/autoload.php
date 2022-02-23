<?php

require_once __DIR__ . '/../config/index.php';

require_once 'Request.php';
require_once 'Router.php';
require_once 'Controller.php';
require_once 'Database.php';
require_once 'Model.php';

require_once __DIR__ . '/../app/Traits/Authenticate.php';

require_once __DIR__ . '/../app/Controllers/SiteController.php';
require_once __DIR__ . '/../app/Controllers/AdminController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';

require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Models/ToDo.php';
