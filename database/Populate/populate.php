<?php

require __DIR__ . '/../../config/bootstrap.php';

use Core\Database\Database;
use Database\Populate\AccountantsPopulate;
use Database\Populate\ClientsPopulate;
use Database\Populate\DeclarationsPopulate;

Database::migrate();

AccountantsPopulate::populate();
ClientsPopulate::populate();
DeclarationsPopulate::populate();