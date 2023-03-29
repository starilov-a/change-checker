<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Change extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Связь `changes` с таблицей `sites`
     */
    public function sites() {
        return $this->belongsTo(Site::class);
    }
}
