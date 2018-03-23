<?php
/**
 * Created by PhpStorm.
 * User: Sergey Pavlov
 * Date: 23.03.2018
 * Time: 10:04
 */
require_once "../vendor/autoload.php";
use PHPUnit\Framework\TestCase;
use coinRates\Prices;
use coinRates\CoinAPI;

class testTest extends TestCase
{

    public function test()
    {
        $this->assertTrue(true);
    }

    public function testPaths()
    {
        $api = new CoinAPI('94D597CF-CA9F-46EF-B3A1-B582F6C90917');
        $pr = new Prices($api, 'BINANCE_SPOT_BTC_USDT', '2017/12/22' );

        $this->assertTrue(is_object($pr));
    }

    public function testCoinApi()

    {
        $api = new CoinAPI('94D597CF-CA9F-46EF-B3A1-B582F6C90917');

        $asset_id_base = 'BTC';
        $asset_id_qoute = "USD";
        $timestr = '2018/01/01';
        $format = "Y/m/d";
        $time = DateTime::createFromFormat($format, $timestr);

        $hist = $api->GetOHLCVHistory('BINANCE_SPOT_BTC_USDT', '1DAY', $time);
    }

    public function testPrice()
    {
        $api = new CoinAPI('94D597CF-CA9F-46EF-B3A1-B582F6C90917');
        $pr = new Prices($api, 'BINANCE_SPOT_BTC_USDT' );

        //$price1 = $pr->getPrice('2018/01/01');
        $price2 = $pr->getCurrentPrice();

        $this->assertTrue(is_object($pr));
    }


}
