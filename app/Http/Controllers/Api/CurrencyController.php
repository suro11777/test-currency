<?php


namespace App\Http\Controllers\Api;


use App\Services\Api\CurrencyService;

class CurrencyController extends BaseController
{
    /**
     * CurrencyController constructor.
     * @param CurrencyService $currencyService
     */
    public function __construct(CurrencyService $currencyService)
    {
        $this->baseApiService = $currencyService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrencies()
    {
        $currencies = $this->baseApiService->getCurrencies();
        if (!$currencies) {
            return response()->json(['status' => 404, 'message' => 'currencies not found', 'data' => []]);
        }
        return response()->json(['status' => 200, 'message' => 'currencies found', 'data' => $currencies]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrencyById($id)
    {
        $currency = $this->baseApiService->getCurrencyById($id);

        if (!$currency) {
            return response()->json(['status' => 404, 'message' => 'currency not found this id:'.$id, 'data' => []]);
        }
        return response()->json(['status' => 200, 'message' => 'currency found', 'data' => $currency]);
    }
}
