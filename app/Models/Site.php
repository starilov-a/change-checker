<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'url', 'page_count'];

    /**
     * Атрибуты, которые должны быть преобразованы в дату
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Связь `sites` с таблицей `pages`
     */
    public function pages() {
        return $this->hasMany(Page::class);
    }

    /**
     * Связь `sites` с таблицей `change`
     */
    public function changes() {
        return $this->hasMany(Change::class);
    }
}
