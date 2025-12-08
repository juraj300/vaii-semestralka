<?php

namespace App\Models;

use Framework\Core\Model;

class Lead extends Model
{
    public int $id;
    public string $company;
    public string $contact_name;
    public ?string $email;
    public string $phone;
    public string $status;
    public ?int $owner_id;
    public string $created_at;
    public string $updated_at;

    public function __construct()
    {
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
    }

    protected static ?string $tableName = 'leads';

    public const STATUS_NEW = 'new';
    public const STATUS_CONTACTED = 'contacted';
    public const STATUS_INTERESTED = 'interested';
    public const STATUS_CLOSED_LOST = 'closed_lost';
    public const STATUS_CLOSED_WON = 'closed_won';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_NEW,
            self::STATUS_CONTACTED,
            self::STATUS_INTERESTED,
            self::STATUS_CLOSED_LOST,
            self::STATUS_CLOSED_WON
        ];
    }

    /**
     * Get the owner (User) of this lead.
     */
    public function getOwner(): ?User
    {
        if ($this->owner_id) {
            return User::getOne($this->owner_id);
        }
        return null;
    }
}
