<?php

namespace App\Models;

use Framework\Core\Model;

class Script extends Model
{
    public int $id;
    public string $name;
    public string $body;
    public int $is_default;

    protected static ?string $tableName = 'scripts';

    public static function getDefault(): ?Script
    {
        $defaults = self::getAll("is_default = 1", [], "id DESC", 1);
        return !empty($defaults) ? $defaults[0] : null;
    }
}
