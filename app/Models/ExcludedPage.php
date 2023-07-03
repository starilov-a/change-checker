<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcludedPage extends Model
{
    use HasFactory;

    protected $fillable = ['site_id', 'page_id'];

    /**
     * Связь `excluded_pages` с таблицей `Pages`
     */
    public function page() {
        return $this->belongsTo(Page::class);
    }

    /**
     * Связь `excluded_pages` с таблицей `sites`
     */
    public function site() {
        return $this->belongsTo(Site::class);
    }
}
