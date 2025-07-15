<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class MaterialSource extends Model
{

    use SoftDeletes;
    protected $dates =['deleted_at'];
    use HasFactory;
        protected $fillable = [
        'material_source_name',
        'access_road',
        'directional_flow',
        'source_type',
        'potential_uses',
        'future_use_recommendation',
        'province',
        'municipality',
        'barangay',
        'renewability',
        'processing_plant_info',
        'observations',
        'quarry_permit',
        'quarry_permit_date',
        'permittee_name',
        'quality_test_attachment',
        'quality_test_date',
        'quality_test_result',
        'prepared_by',
        'user_id',
        'user_id_validation',
        'region',
        'latitude',
        'longitude',
    ];
}
