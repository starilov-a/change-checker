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

    protected $fillable = ['site_id', 'url', 'size', 'created_at'];
    /**
     * Связь `pages` с таблицей `sites`
     */
    public function sites() {
        return $this->belongsTo(Site::class);
    }

    public function addPage($param) {
        return $this->create($param);
    }
}
