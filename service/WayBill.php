<?php
/**
 * Created by PhpStorm.
 * User: mihailnilov
 * Date: 28.04.2020
 * Time: 17:43
 */

namespace Service;

use PhpOffice\PhpSpreadsheet\IOFactory;

class WayBill{

    public $fileName = 'waybill.xlsx';

    public function __construct()
    {

    }

    public function creteWayBill($row){

        $excel = IOFactory::load($this->fileName);
        $excel->setActiveSheetIndex(0);
        if(isset($row[0])){
            $items = $row[0]['list1'];
            foreach ($items AS $i => $item){
                $excel->getActiveSheet()->setCellValue($i, $item);
            }

            $excel->setActiveSheetIndex(1);
            $items = $row[0]['list2'];
            $summ = 0;
            foreach ($items AS $i => $item){
                $string = $i+5;
                $excel->getActiveSheet()->setCellValue("B{$string}", $i+1);
                $excel->getActiveSheet()->setCellValue("E{$string}", $item[0]);
                $excel->getActiveSheet()->setCellValue("H{$string}", $item[1]);
                $excel->getActiveSheet()->setCellValue("O{$string}", $item[2]);
                $summ += $item[2];
            }
            $excel->getActiveSheet()->setCellValue("E35", $summ);
        }else{
            return false;
        }

        if(isset($row[1])){
            $excel->setActiveSheetIndex(0);
            $items = $row[1]['list1'];
            foreach ($items AS $i => $item){
                $excel->getActiveSheet()->setCellValue($i, $item);
            }

            $excel->setActiveSheetIndex(1);
            $items = $row[1]['list2'];
            $summ = 0;
            foreach ($items AS $i => $item){
                $string = $i+5;
                $excel->getActiveSheet()->setCellValue("T{$string}", $i+1);
                $excel->getActiveSheet()->setCellValue("W{$string}", $item[0]);
                $excel->getActiveSheet()->setCellValue("Z{$string}", $item[1]);
                $excel->getActiveSheet()->setCellValue("AG{$string}", $item[2]);
                $summ += $item[2];
            }
            $excel->getActiveSheet()->setCellValue("W35", $summ);
        }

        $objWriter = IOFactory::createWriter($excel, 'Xlsx');
        $objWriter->save('doc.xlsx');
        return true;
    }

}