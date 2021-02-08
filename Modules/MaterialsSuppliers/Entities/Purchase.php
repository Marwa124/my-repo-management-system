<?php

namespace Modules\MaterialsSuppliers\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use \DateTimeInterface;
use Modules\Sales\Entities\ProposalsItem;

class Purchase extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait;

    public $table = 'purchases';

    const EMAILED_RADIO = [
        'yes' => 'Yes',
        'no'  => 'No',
    ];

    const UPDATE_STOCK_RADIO = [
        'yes' => 'Yes',
        'no'  => 'No',
    ];

    const DISCOUNT_TYPE_SELECT = [
        'before_tax' => 'Before Tax',
        'after_tax'  => 'After Tax',
    ];

    protected $dates = [
        'date_sent',
        'purchase_date',
        'due_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $guarded = [];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items() {
        return $this->belongsToMany(ProposalsItem::class, 'item_purchase', 'purchase_id', 'item_id')->withPivot(
            'item_name',
            'item_description',
            'quantity',
            'price',
            'total'
        );
    }

    public function getDateSentAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateSentAttribute($value)
    {
        $this->attributes['date_sent'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getPurchaseDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setPurchaseDateAttribute($value)
    {
        $this->attributes['purchase_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getDueDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }
}