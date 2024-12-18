<?php

require __DIR__ . '/../../config/bootstrap.php';

use Core\Database\Database;
use Database\Populate\AccountantsPopulate;
use Database\Populate\ClientsPopulate;

Database::migrate();

AccountantsPopulate::populate();
ClientsPopulate::populate();