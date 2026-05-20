<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

#[Fillable(['key', 'value', 'type', 'group', 'label', 'hint'])]
class Setting extends Model
{
    use HasFactory;

    public function getValueAttribute($value): mixed
    {
        return match($this->type) {
            'boolean'   => (bool) $value,
            'integer'   => (int) $value,
            'encrypted' => $value ? Crypt::decryptString($value) : null,
            default     => $value,
        };
    }

    public function setValueAttribute($value): void
    {
        $this->attributes['value'] = $this->type === 'encrypted'
            ? Crypt::encryptString($value)
            : $value;
    }
}