<?php

namespace App\Models;

use Framework\Core\Model;

class Attachment extends Model
{
    public int $id;
    public ?int $lead_id;
    public int $user_id;
    public string $filename;
    public string $path;
    public string $created_at;

    protected static ?string $tableName = 'attachments';
}
