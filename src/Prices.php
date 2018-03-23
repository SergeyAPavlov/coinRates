<?php
namespace coinRates;
use DateTime;
use DateInterval;

/**
 * Created by PhpStorm.
 * User: Sergey Pavlov
 * Date: 23.03.2018
 * Time: 10:06
 */
class Prices
{
    /** @var  CoinAPI */
    public $api;
    public $baseId;

    public $format = "Y/m/d";
    public $log = [];
    public $error;

    /**
     * Prices constructor.
     * @param CoinAPI $api
     * @param $base
     */
    public function __construct(CoinAPI $api, $base)
    {
        $this->api = $api;
        $this->baseId = $base;
    }

    public function formatTime($time)
    {

        return DateTime::createFromFormat($this->format, $time);
    }

    public function getPrice ($time)
    {
        try {
            $tt = $this->formatTime($time);
            $hist = current($this->api->GetOHLCVHistory($this->baseId, '1DAY', $tt, null, 1));
            $res = $hist->price_open;
            return $res;
        } catch (\Throwable $t){
            $this->log[] = $t->getMessage();
            $this->error = $t;
            return null;
        }
    }

    public function getCurrentPrice ()
    {
        try{
            $tt = new DateTime();
            $tB = $tt->sub(new DateInterval('P1D'));
            $hist = current($this->api->GetOHLCVHistory($this->baseId, '1DAY', $tB, null, 1));
            $res = $hist->price_close;
            return $res;
        } catch (\Throwable $t){
            $this->log[] = $t->getMessage();
            $this->error = $t;
            return null;
        }
    }

    public function rate ($date)
    {
        $priceOld = $this->getPrice($date);
        $priceCurr = $this->getCurrentPrice();
        if (is_null($priceOld) OR is_null($priceCurr)) return null;
        return $priceCurr/$priceOld;
    }
}