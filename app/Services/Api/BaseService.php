<?php


namespace App\Services\Api;


class BaseService
{
    /**
     * @var array
     */
    public $columns = [
        'name',
        'english_name',
        'alphabetic_code',
        'digit_code',
        'rate',
    ];
}
