<?php

require __DIR__ . '/../../config/bootstrap.php';

use Core\Database\Database;
use Database\Populate\UsersPopulate;
use Database\Populate\CampaignsPopulate;

Database::migrate();
UsersPopulate::populate();
CampaignsPopulate::populate();
