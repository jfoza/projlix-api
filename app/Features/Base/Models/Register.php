<?php
declare(strict_types=1);

namespace App\Features\Base\Models;

use App\Shared\Utils\Auth;
use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    public $hidden = [
        'creator_id',
        'updater_id'
    ];

    protected static function booted(): void
    {
        if ($id = Auth::getId())
        {
            static::creating(fn ($register) => $register->creator_id = $register->creator_id ?? $id ?? null);
            static::updating(fn ($register) => $register->updater_id = $register->updater_id ?? $id ?? null);
        }
    }

    public static function tableName(): string
    {
        return (new static)->getTable();
    }

    public static function tableField(string $field): string
    {
        return self::tableName() . '.' . $field;
    }
}
