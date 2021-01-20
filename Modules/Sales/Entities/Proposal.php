<?php


namespace Modules\Sales\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

use  Modules\Sales\Entities\ProposalItemTax;
use \DateTimeInterface;

class Proposal extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait;

    public $table = 'proposals';

    const EMAILED_SELECT = [
        'yes' => 'Yes',
        'no'  => 'No',
    ];

    const CONVERT_SELECT = [
        'yes' => 'Yes',
        'no'  => 'No',
    ];

    const SHOW_CLIENT_SELECT = [
        'yes' => 'Yes',
        'no'  => 'No',
    ];

    const PROPOSAL_DELETED_SELECT = [
        'yes' => 'Yes',
        'no'  => 'No',
    ];

    protected $dates = [
        'proposal_date',
        'expire_date',
        'date_sent',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'id',
        'reference_no',
        'subject',
        'module',
        'proposal_date',
        'expire_date',
        'alert_overdue',
        'currency',
        'notes',
        'total_tax',
        'total_cost_price',
        'tax',
        'status',
        'date_sent',
        'proposal_deleted',
        'emailed',
        'show_client',
        'convert',
        'convert_module',
        'module_id',
        'convert_module_id',
        'converted_date',
        'discount_type',
        'discount_percent',
        'after_discount',
        'discount_total',
        'adjustment',
        'show_quantity_as',
        'allowed_cmments',
        'proposal_validity',
        'materials_supply_delivery',
        'warranty',
        'prices',
        'user_id',
        'payment_terms',
        'maintenance_service_contract',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getProposalDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setProposalDateAttribute($value)
    {
        $this->attributes['proposal_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getExpireDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setExpireDateAttribute($value)
    {
        $this->attributes['expire_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getDateSentAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateSentAttribute($value)
    {
        $this->attributes['date_sent'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    // public function permissions()
    // {
    //     return $this->belongsToMany(Permission::class);
    // }
    public function items(){

        return $this->belongsToMany('Modules\Sales\Entities\ProposalsItem',
            'item_porposal_relations','proposals_id','item_id') ->withPivot(
                'item_name',
                'item_desc',
                'group_name',
                'brand',
                'delivery',
                'part',
                'quantity',
                'unit_cost',
                'margin',
                'selling_price',
                'total_cost_price',
                'tax_rate',
                'tax_name',
                'tax_total',
                'tax_cost',
                'order',
                'unit',
                'hsn_code',
            )->orderBy('order','asc');

    }

        public function itemtaxs()
        {
            return $this->hasMany(ProposalItemTax::class, 'proposals_id', 'id');
        }
}
