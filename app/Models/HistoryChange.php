<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryChange extends Model
{
    use HasFactory;

    protected $hidden = [
        'updated_at',
    ];

    protected $fillable = ['site_id', 'page_id'];

    /**
     * Связь `history_changes` с таблицей `Changes`
     */
    public function page() {
        return $this->belongsTo(Page::class);
    }

    /**
     * Связь `sites` с таблицей `Sites`
     */
    public function site() {
        return $this->belongsTo(Site::class);
    }
}
