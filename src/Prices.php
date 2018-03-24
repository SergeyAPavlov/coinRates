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

    const BINANCE = 'BINANCE_SPOT_BTC_USDT';

    public $format = "Y/m/d";
    public $oldestDate = '2017/12/18';

    public $log = [];
    /** @var  \Throwable */
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
            $today =  new DateTime("now");
            $diff = ($today <= $tt);
            if ($diff) Throw new \Exception('Запрошенная дата не ранее сегодняшней');
            $oldday = DateTime::createFromFormat($this->format, $this->oldestDate);
            $diff1 = ($oldday > $tt);
            if ($diff1) Throw new \Exception('Запрошенная дата слишком ранняя для данного АПИ');
            $hist = current($this->api->GetOHLCVHistory($this->baseId, '1DAY', $tt, null, 1));
            $res = $hist->price_open;
            return $res;
        } catch (\Exception $t){
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