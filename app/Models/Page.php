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

    static public function withoutExcluded($siteId = false) {
        if ($siteId === false)
            return self::doesnthave('excludedPage');
        else
            return self::where('site_id', '=' , $siteId)->doesnthave('excludedPage');
    }

    static public function onlyExcluded($siteId = false) {
        if ($siteId === false)
            return self::has('excludedPage');
        else
            return self::where('site_id', '=' , $siteId)->has('excludedPage');
    }

    /**
     * Связь `pages` с таблицей `sites`
     */
    public function site() {
        return $this->belongsTo(Site::class);
    }

    /**
     * Связь `pages` с таблицей `excluded_pages`
     */
    public function excludedPage() {
        return $this->hasOne(ExcludedPage::class);
    }

    /**
     * Связь `pages` с таблицей `history_changes`
     */
    public function historyChanges() {
        return $this->hasMany(HistoryChange::class);
    }
}
