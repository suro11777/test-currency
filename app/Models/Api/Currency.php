<?php


namespace App\Models\Api;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Currency
 * @package App\Models\Api
 */
class Currency extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'english_name',
        'alphabetic_code',
        'digit_code',
        'rate',
    ];
}
