<?php
/**
 * Created by PhpStorm.
 * User: mihailnilov
 * Date: 04.05.2020
 * Time: 10:46
 */

namespace Service;

use Service\MysqlSelect;

class Validate
{
    static public function arrValidate($val){
        $arr[0] = [
            'BX4' => 'номер документа',
            'AD5' => 'дата день',
            'AI5' => 'дата месяц',
            'AU5' => 'дата год',
            'BU19' => 'пробег старт',
            'BT45' => 'пробег финиш',
            //'BT34' => 'заправлено',
            'BT37' => 'остаток при выезде',
            'BT38' => 'остаток при возвращении',
            'BT39' => 'расход норма',
            'BT40' => 'расход фактический',
        ];


        $arr[1] = [
            'FF4' => 'номер документа',
            'DL5' => 'дата день',
            'DQ5' => 'дата месяц',
            'EC5' => 'дата год',
            'FC19' => 'пробег старт',
            'FB45' => 'пробег финиш',
//            'FB34' => 'заправлено',
            'FB37' => 'остаток при выезде',
            'FB38' => 'остаток при возвращении',
            'FB39' => 'расход норма',
            'FB40' => 'расход фактический',
        ];

        $result1 = self::valValidate($val[0], $arr[0]);
        if(isset($val[1])){
            $result2 = self::valValidate($val[1], $arr[1]);
        }

        if($result1['status'] === 'success' && (!isset($result2) || $result2['status'] === 'success')){
            return ['status' => 'success'];
        }else{
            $error = [];
            if($result1['status'] === 'error') $error[] = $result1['error'];
            if(isset($result2) && $result2['status'] === 'error') $error[] = $result2['error'];
            return ['status' => 'error', 'error' => $error];
        }
    }

    static private function valValidate($val, $arr){
        $err = [];
        if(isset($val['list1'])){
            foreach ($arr AS $i => $item){
                if(!isset($val['list1'][$i]) || empty($val['list1'][$i])){
                    if(!isset($err['list1'])) $err['list1']['message'] = 'Не все поля заполнены';
                    $err['list1']['field'][] = $item;
                }
            }
            if(count($val['list2']) <= 0) $err['list2']['message'] = 'Маршруты не выбраны';
            else{
                foreach ($val['list2'] AS $i => $item){
                    if(count($item) !== 2){
                        if(!isset($err['list2'])) $err['list2']['message'] = 'Не все значение маршрутов';
                        $err['list2']['routes'][] = $i+1;
                    }
                }
            }
        }else{
            $err['list1']['message'] = 'Не создан блок значений';
        }

        return (empty($err))?['status' => 'success']:['status' => 'error', 'error' => $err];
    }
}