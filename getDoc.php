<?php
/**
 * Created by PhpStorm.
 * User: mihailnilov
 * Date: 29.04.2020
 * Time: 19:38
 */
require_once 'vendor/autoload.php';

use Service\MysqlSelect;
use Service\WayBill;


$mysql = new MysqlSelect();
$wayBill = new WayBill();

$data = $_POST['data'];
$routes = $_POST['routes'];
$row[0] = ['list1' => $data];
foreach ($routes as $route) {
    $row[0]['list2'][] = $mysql->getRoutesToDoc($route);
}
$wayBill->creteWayBill($row);

$param = [
    'balance' => $data['BT38'],
    'last_date' => $_POST['dateInputOil'],
    'mileage' => $data['BT45']
];

$mysql->updateBalance($param);

die;
$row2 = [
    0 => [
        'list1' => [
            'BX4' => 1,  //номер документа
            'AD5' => 25, //дата день
            'AI5' => 03, //дата месяц
            'AU5' => 3030, //дата год
            'BU19' => 123321, //пробег старт
            'BT45' => 321213, //пробег финиш
            'BT34' => 1, //заправлено
            'BT37' => 00, //остаток при выезде
            'BT38' => 20, //остаток при возвращении
            'BT39' => 20, //расход норма
            'BT40' => 20, //расход фактический
        ],
        'list2' =>[[2,3,4], [3,4,5], [4,3,2]]
    ],
    1 => [
        'list1' => [
            'FF4' => 1,  //номер документа
            'DL5' => 25, //дата день
            'DQ5' => 03, //дата месяц
            'EC5' => 2020, //дата год
            'FC19' => 123321, //пробег старт
            'FB45' => 321213, //пробег финиш
            'FB34' => 1, //заправлено
            'FB37' => 00, //остаток при выезде
            'FB38' => 20, //остаток при возвращении
            'FB39' => 20, //расход норма
            'FB40' => 20, //расход фактический
        ],
        'list2' =>[[2,3,4], [3,4,5], [4,3,2]]
    ],
];

die;


