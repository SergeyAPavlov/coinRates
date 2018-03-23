<?php

require_once "../vendor/autoload.php";
require_once "../env.php";
use coinRates\Prices;
use coinRates\CoinAPI;

    $qual = '';
    $date = '';
    $submit = '';
    $rate = 0;
    $diff = '';
    $priceOld = '';
    $priceCurr = '';
    $err = '';
    $doIt = false;

    if (isset($_GET['qual']))$qual = $_GET['qual'];
    if (isset($_GET['date']))$date = $_GET['date'];
    if (isset($_GET['Submit'])) $submit = $_GET['Submit'];
    if ($submit AND $qual AND $date){
        $doIt = true;
        $api = new CoinAPI($coinapiKey);
        $base = Prices::BINANCE;
        $prices = new Prices($api, $base);
        $priceOld = $prices->getPrice($date);
        $priceCurr = $prices->getCurrentPrice();
        if (is_null($priceOld) OR is_null($priceCurr)) {
            $err = $prices->error->getMessage();
        }else {
            $rate = ($priceCurr/$priceOld - 1)*100 ;
            $diff = $priceCurr - $priceOld;
        }

    }
    $message = "Ошибка:" . $err.'<br> Параметры: количество'.$qual.' дата '.$date."<br> submit $submit Обработка запроса: $doIt";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Доход в биткойнах по бирже Бинанс</title>
</head>
<body>
<h2>Доход в биткойнах по бирже Бинанс</h2>
<form name="this" action="" method="GET">
    <table border="1">
        <tr>
            <th>Количество купленных биткойнов</th>
            <th>Дата</th>
            <th>Курс</th>
            <th>Текущий курс</th>
            <th>Доходность в %</th>
            <th>Доходность в долларах</th>
        </tr>
        <tr>
            <td><input type="text" name="qual" value="<?= $qual ?>"></td>
            <td><input type="text" name="date" value="<?= $date ?>"></td>
            <td><?= $priceOld ?></td>
            <td><?= $priceCurr ?></td>
            <td><?= number_format($rate , 1 )?>%</td>
            <td><?= $diff ?></td>
        </tr>
    </table>
    <input type="hidden" value="<?= $message ?>">
    <input type="submit" name="Submit" value="Submit">
</form>

<?php
    if ($err) {
        echo $message;
    }
?>
</body>
</html>