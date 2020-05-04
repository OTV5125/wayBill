<?php
/**
 * Created by PhpStorm.
 * User: mihailnilov
 * Date: 28.04.2020
 * Time: 17:54
 */

namespace Service;


class MysqlSelect extends Mysql
{
    public function getBalance(){
        $sql = "SELECT *, date_format(last_date, '%d-%m-%Y') as last_date FROM balance WHERE id = 1";
        return $this->exec($sql, [])->fetchAll()[0];
    }

    public function getRoutes(){
        $sql = "SELECT id, route_id_1, route_id_2, distance FROM routes";
        $result = $this->exec($sql, [])->fetchAll();
        $arr = [];
        foreach ($result AS $item){
            $route1= $this->exec("SELECT name FROM route WHERE id = {$item['route_id_1']}", [])->fetchColumn();
            $route2= $this->exec("SELECT name FROM route WHERE id = {$item['route_id_2']}", [])->fetchColumn();
            $arr[] = [$item['id'], $route1, $route2, $item['distance']];
        }
        return $arr;
    }

    public function getRoutesToDoc($blocks){

        $error = [];
        $sql = "SELECT id, route_id_1, route_id_2, distance FROM routes WHERE id = :id";
        foreach ($blocks as $i => $block) {
            foreach ($block['list2'] AS $iBLock => $item){
                $result = $this->exec($sql, ['id' => $item[0]])->fetchAll()[0];
                if(empty($result)){
                    if(!isset($error['routes'])) $error['routes']['message'] = 'routes not found from database';
                    $error['routes']['id'][] = $item;
                }else{
                    $route1= $this->exec("SELECT name FROM route WHERE id = {$result['route_id_1']}", [])->fetchColumn();
                    $route2= $this->exec("SELECT name FROM route WHERE id = {$result['route_id_2']}", [])->fetchColumn();
                    $blocks[$i]['list2'][$iBLock]  = ($blocks[1] == 'false')?[$route1, $route2, $result['distance']]:[$route2, $route1, $result['distance']];
                }
            }
        }
        return (empty($error))?['status' => 'success', 'blocks' => $blocks]:['status' => 'error', 'error' => $error];
    }

    public function updateBalance($param){

        $sql = "UPDATE balance SET balance = :balance, mileage = :mileage";
        if(!empty($param['last_date'])) $sql .= ', last_date = :last_date';
        $sql .= ' WHERE id = 1';
//        var_dump($sql); die;
        return $this->exec($sql, $param);
    }
}