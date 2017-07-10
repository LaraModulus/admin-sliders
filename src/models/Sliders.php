<?php

namespace LaraMod\Admin\Sliders\Models;

use LaraMod\Admin\Core\Scopes\AdminCoreOrderByCreatedAtScope;
use LaraMod\Admin\Core\Scopes\AdminCoreOrderByPosScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaraMod\Admin\Core\Traits\HelpersTrait;
use LaraMod\Admin\Files\Models\Files;

class Sliders extends Model
{
    public $timestamps = true;
    protected $table = 'sliders';

    use SoftDeletes, HelpersTrait;
    protected $guarded = ['id'];

    protected $casts = [
        'viewable' => 'boolean'
    ];

    protected $dates = ['deleted_at', 'from_date', 'to_date'];

    protected $appends = ['image_id'];


    protected $fillable = [
        'viewable',
        'from_date',
        'to_date',
        'pos'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        foreach (config('app.locales', [config('app.fallback_locale', 'en')]) as $locale) {
            $this->fillable = array_merge($this->fillable, [
                'title_'.$locale,
                'sub_title_'.$locale,
                'description_'.$locale,
                'link_'.$locale,
                'image_'.$locale,
            ]);
        }
    }

    public function scopeVisible($q)
    {
        return $q->whereViewable(true);
    }

    public function getTitleAttribute()
    {
        return $this->{'title_' . config('app.locale', 'en')};
    }

    public function getSubTitleAttribute()
    {
        return $this->{'sub_title_' . config('app.locale', 'en')};
    }

    public function getDescriptionAttribute()
    {
        return $this->{'description_' . config('app.locale', 'en')};
    }

    public function getLinkAttribute()
    {
        return $this->{'link_' . config('app.locale', 'en')};
    }

    public function getImageIdAttribute()
    {
        return $this->{'image_' . config('app.locale', 'en')};
    }

    public function image()
    {
        return $this->belongsTo(Files::class);
    }

    public function setFromDateAttribute($value)
    {
        $this->attributes['from_date'] = $value ?: \Carbon\Carbon::now();
    }

    public function setToDateAttribute($value)
    {
        $this->attributes['to_date'] = $value ?: null;
    }

    protected function bootIfNotBooted()
    {
        parent::boot();
        static::addGlobalScope(new AdminCoreOrderByPosScope());
        static::addGlobalScope(new AdminCoreOrderByCreatedAtScope());
    }


}