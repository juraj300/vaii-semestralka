<?php

namespace App\Models;

use Framework\Core\Model;

class Appointment extends Model
{
    public int $id;
    public int $user_id;
    public ?int $lead_id;
    public string $title;
    public string $start_at;
    public string $end_at;
    public ?string $description;
    public string $created_at;

    protected static ?string $tableName = 'appointments';

    public function __construct()
    {
        $this->created_at = date('Y-m-d H:i:s');
    }
}
