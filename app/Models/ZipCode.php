<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;


class ZipCode extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $collection = 'zip_codes';

    protected $primaryKey = 'zip_code';

    protected $fillable = [
        'id',
        'zip_code',
        'locality',
        'federal_entity',
        'settlements',
        'municipality',
    ];

    protected $casts = [
        'id' => 'string',
        'zip_code' => 'string',
        'locality' => 'string',
        'federal_entity' => 'array',
        'settlements' => 'array',
        'municipality' => 'array',
    ];

    protected $hidden = [
        '_id',
        'updated_at'
    ];
}
