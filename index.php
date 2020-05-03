<?php
/**
 * Created by PhpStorm.
 * User: mihailnilov
 * Date: 28.04.2020
 * Time: 14:55
 */

 require_once 'vendor/autoload.php';


 use Service\MysqlSelect;

 $mysql = new MysqlSelect();
 $routes = $mysql->getRoutes();
 $balance = $mysql->getBalance();


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
<script src="http://code.jquery.com/jquery-3.5.0.js"></script>
<script src="js/ajax.js"></script>
<script src="js/script.js"></script>
<script src="js/Math.js"></script>
<script>
    window.onload = function() {
        new Info()
    }
</script>

<h1>Сервис посчитаем бензин</h1>
<div class="wrapper">
    <div class="wrapper-items block-1">
    <div class="wrapper-item">
        <div class="petrol-list">
            Старый пробег <span data-value="BU19" class="oldMileage"><?=$balance['mileage']?></span>км.<br>
            Новый пробег <span data-value="BT45" class="newMileage"></span>км.<br>
            <br>
            Остаток <span class="oldBalance" data-value="BT37"><?=$balance['balance']?></span> л.<br>
            Дата последней заправки <span class="petrol lastOilDate"><?=$balance['last_date']?></span><br>
            Всего бензина <span class="sumBalance" data-value="BT38"><?=$balance['balance']?></span> л.<br>
            Осталось километров <span class="sumKm"><?=round($balance['balance']/(11/100))?></span> км.<br>
        </div>
    </div>
    

    <div class="wrapper-item">
        <div class="selectRoutes">
        <span class="title">Первый путевой лист</span>
        <br><br>
        <input type="number" class="input-selectRoutes input-oil" data-value="BT34"> Бензина залил <br><br>
        <input type="date" class="input-selectRoutes input-oil-date"> Дата заправки<br>
        <br>
        <input type="date" class="input-selectRoutes new-date" data-value="date"> Дата путевого листа<br><br>
        <div class="checkbox-list">
            <select>
            <option data-id="0">не выбрано</option>
            <?php foreach ($routes AS $route):?>
            <option data-id="<?=$route[0]?>" data-km="<?=$route[3]?>"><?=$route[1]?> - <?=$route[2]?> (<?=$route[3]?>км)</option>
            <?php endforeach;?>
            </select> <input type="checkbox"> в обратную сторону
        </div>
        <div class="checkbox-list">
        <select>
            <option data-id="0">не выбрано</option>
            <?php foreach ($routes AS $route):?>
                <option data-id="<?=$route[0]?>" data-km="<?=$route[3]?>"><?=$route[1]?> - <?=$route[2]?> (<?=$route[3]?>км)</option>
            <?php endforeach;?>
        </select> <input type="checkbox"> в обратную сторону
        </div>
        <div class="checkbox-list">
            <select>
                <option data-id="0">не выбрано</option>
                <?php foreach ($routes AS $route):?>
                    <option data-id="<?=$route[0]?>" data-km="<?=$route[3]?>"><?=$route[1]?> - <?=$route[2]?> (<?=$route[3]?>км)</option>
                <?php endforeach;?>
            </select> <input type="checkbox"> в обратную сторону
        </div>
        <div class="checkbox-list">
            <select>
                <option data-id="0">не выбрано</option>
                <?php foreach ($routes AS $route):?>
                    <option data-id="<?=$route[0]?>" data-km="<?=$route[3]?>"><?=$route[1]?> - <?=$route[2]?> (<?=$route[3]?>км)</option>
                <?php endforeach;?>
            </select> <input type="checkbox"> в обратную сторону
        </div>
        <div class="checkbox-list">
            <select>
                <option data-id="0">не выбрано</option>
                <?php foreach ($routes AS $route):?>
                    <option data-id="<?=$route[0]?>" data-km="<?=$route[3]?>"><?=$route[1]?> - <?=$route[2]?> (<?=$route[3]?>км)</option>
                <?php endforeach;?>
            </select> <input type="checkbox"> в обратную сторону
        </div>
            <button>сохранить</button>
            <button class="btnGetDoc1">получить документ</button>
     </div>
    </div>

</div>

<div class="wrapper-items">
    <div class="wrapper-item">
        <div class="petrol-list">
            Старый пробег <span></span> км.<br>
            Новый прбег <span></span> км.<br>
            <br>
            Остаток <span class="balance"><?=$balance['balance']?></span> л.<br>
            Дата последней заправки <span class="petrol"><?=$balance['last_date']?></span><br>
            Всего бензина <span class="sumBalance"><?=$balance['balance']?></span> л.<br>
            Осталось километров <span class="sumKm"><?=round($balance['balance']/(11/100))?></span> км.<br>
        </div>
    </div>
    

    <div class="wrapper-item">
        <div class="selectRoutes">
        <span class="title">Первый путевой лист</span>
        <br><br>
        <input type="number" class="input-selectRoutes" value=""> Бензина залил <br><br>
        <input type="date" class="input-selectRoutes"> Дата заправки<br>
        <br>
        <input type="date" class="input-selectRoutes"> Дата путевого листа<br><br>
        <div class="checkbox-list">
            <select>
            <option data-id="0">не выбрано</option>
            <?php foreach ($routes AS $route):?>
            <option data-id="<?=$route[0]?>" data-km="<?=$route[3]?>"><?=$route[1]?> - <?=$route[2]?> (<?=$route[3]?>км)</option>
            <?php endforeach;?>
            </select> <input type="checkbox"> в обратную сторону
        </div>
        <div class="checkbox-list">
        <select>
            <option data-id="0">не выбрано</option>
            <?php foreach ($routes AS $route):?>
                <option data-id="<?=$route[0]?>" data-km="<?=$route[3]?>"><?=$route[1]?> - <?=$route[2]?> (<?=$route[3]?>км)</option>
            <?php endforeach;?>
        </select> <input type="checkbox"> в обратную сторону
        </div>
        <div class="checkbox-list">
            <select>
                <option data-id="0">не выбрано</option>
                <?php foreach ($routes AS $route):?>
                    <option data-id="<?=$route[0]?>" data-km="<?=$route[3]?>"><?=$route[1]?> - <?=$route[2]?> (<?=$route[3]?>км)</option>
                <?php endforeach;?>
            </select> <input type="checkbox"> в обратную сторону
        </div>
        <div class="checkbox-list">
            <select>
                <option data-id="0">не выбрано</option>
                <?php foreach ($routes AS $route):?>
                    <option data-id="<?=$route[0]?>" data-km="<?=$route[3]?>"><?=$route[1]?> - <?=$route[2]?> (<?=$route[3]?>км)</option>
                <?php endforeach;?>
            </select> <input type="checkbox"> в обратную сторону
        </div>
        <div class="checkbox-list">
            <select>
                <option data-id="0">не выбрано</option>
                <?php foreach ($routes AS $route):?>
                    <option data-id="<?=$route[0]?>" data-km="<?=$route[3]?>"><?=$route[1]?> - <?=$route[2]?> (<?=$route[3]?>км)</option>
                <?php endforeach;?>
            </select> <input type="checkbox"> в обратную сторону
        </div>
     </div>   
    </div>

</div>
</div>


</body>
</html>




