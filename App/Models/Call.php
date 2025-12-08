<?php

namespace App\Models;

use Framework\Core\Model;

class Call extends Model
{
    public int $id;
    public int $lead_id;
    public int $user_id;
    public string $outcome; // e.g., 'interested', 'no_answer'
    public ?string $notes;
    public string $created_at;

    public function __construct()
    {
        $this->created_at = date('Y-m-d H:i:s');
    }

    protected static ?string $tableName = 'calls';
}
