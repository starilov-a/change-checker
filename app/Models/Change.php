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

    protected $fillable = ['checked', 'url'];

    /**
     * Получение всех site в которых есть изменения
     */
    static function changedSites() {
        $changesBySite = Change::with('sites')->where('checked', '=', '0')->groupBy('site_id')->get();

        $sites = [];
        foreach ($changesBySite as $change)
            $sites[] = $change->sites;

        return $sites;
    }

    /**
     * Связь `changes` с таблицей `sites`
     */
    public function sites() {
        return $this->belongsTo(Site::class, 'site_id');
    }
}
