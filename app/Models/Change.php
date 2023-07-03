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

    protected $fillable = ['site_id', 'page_id'];

    /**
     * Связь `changes` с таблицей `sites`
     */
    public function site() {
        return $this->belongsTo(Site::class, 'site_id');
    }

    /**
     * Связь `changes` с таблицей `sites`
     */
    public function page() {
        return $this->belongsTo(Page::class, 'page_id');
    }

    /**
     * Получение всех sit+e в которых есть изменения
     */
    static function changedSites() {
        $changesBySite = Change::with('site')->groupBy('site_id')->get();

        $sites = [];
        foreach ($changesBySite as $change)
            $sites[] = $change->sites;

        return $sites;
    }

    /**
     * Добалвение в историю изменений
     */
    public function addInHistory() {
        $historyChange = HistoryChange::create(['site_id' => $this->site_id, 'page_id' => $this->page_id]);
        $this->delete();

        return $historyChange;
    }
}
