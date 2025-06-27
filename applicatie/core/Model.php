<?php

namespace Core;

use Core\Database;
use PDO;

abstract class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }
}
