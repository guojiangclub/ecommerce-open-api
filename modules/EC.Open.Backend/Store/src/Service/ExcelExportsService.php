<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Service;
use Excel;

class ExcelExportsService
{

    /**批量生成excel
     * @param $name
     * @param $date
     * @param array $title
     * @return string
     */

    public  function createExcelExport($name,$date,$title=array()){

        $nameNew=$name.date('Y_m_d_H_i_s', time());
        $excel=Excel::create($nameNew, function ($excel) use ($date,$title) {
            $excel->sheet('SheetnameLee', function ($sheet) use ($date,$title){

                $sheet->prependRow(1,$title);
                $sheet->rows($date);
            });
        })->store('xls');

        return "$nameNew.xls";

    }


    public  function createExcelExport2($name,$dates,$title=array(), $num){

        $nameNew=$name.date('Y_m_d_H_i_s', time());

        $excel=Excel::create($nameNew, function ($excel) use ($dates, $title, $num) {

            $pages = ceil(count($dates) / $num);
            for ($i = 0; $i < $pages; $i ++)
            {

                $data = array_slice($dates, $i * $num, $num);
                $excel->sheet('shell-'. $i, function ($sheet) use ($data,$title){
                    $sheet->prependRow(1,$title);
                    $sheet->rows($data);
                });
                unset($data);
            }


        })->store('xlsx');

        return "$nameNew.xlsx";

    }

}

