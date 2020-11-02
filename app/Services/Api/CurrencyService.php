<?php


namespace App\Services\Api;


use App\Models\Api\Currency;

class CurrencyService extends BaseService
{

    /**
     * @return mixed
     */
    public function getCurrencies()
    {
        $currencies = Currency::paginate(15, $this->columns);
        return $currencies;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getCurrencyById($id)
    {
        $currency = Currency::find($id, $this->columns);
        return $currency;
    }
}
