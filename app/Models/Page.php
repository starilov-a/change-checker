<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Связь `pages` с таблицей `sites`
     */
    public function sites() {
        return $this->belongsTo(Site::class);
    }
}
