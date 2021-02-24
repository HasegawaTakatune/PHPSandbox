<?php

require_once 'vendor\autoload.php';
use PhpOffice\PhpSpreadsheet\Writer\Xlsx AS Writer;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx AS Reader;
use PhpOffice\PhpSpreadsheet\IOFactory AS IOFactory;

class ExcelReport{

    public static function outSample(){

        $reader = new Reader();
        $spreadsheet = $reader->load('..\Reports\sample.xlsx');
        $sheet = $spreadsheet->getActiveSheet()->freezePane('B2');

        $sheet->setCellValue('C2', '英語');
        $sheet->setCellValue('D2', '数学');
        $sheet->setCellValue('B3', 'Aさん');
        $sheet->setCellValue('B4', 'Bさん');
        $sheet->setCellValue('C3', '90');
        $sheet->setCellValue('C4', '80');
        $sheet->setCellValue('D3', '70');
        $sheet->setCellValue('D4', '95');
        
        // バッファをクリア
        ob_end_clean();

        $fileName = "sample.xlsx";
 
        // ダウンロード
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$fileName.'"');
        header('Cache-Control: max-age=0');
        header('Pragma:no-cache');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer = new Writer($spreadsheet);
        $writer->save('php://output');
        
    }

    public static function ReportOrderInfo(){

        $headerLine1 = array('X2', 'AQ2');
        $headerLine2 = array('X3', 'AQ3');
        $detailLine = array('X4', 'AQ4');

        $reader = new Reader();
        $spreadsheet = $reader->load('..\Reports\sample.xlsx');
        $sheet = $spreadsheet->getActiveSheet(0)->freezePane('B2');

        $column = 6;

        $sheet->setCellValue('C2', '英語');
        $sheet->setCellValue('D2', '数学');
        $sheet->setCellValue('B3', 'Aさん');
        $sheet->setCellValue('B4', 'Bさん');
        $sheet->setCellValue('C3', '90');
        $sheet->setCellValue('C4', '80');
        $sheet->setCellValue('D3', '70');
        $sheet->setCellValue('D4', '95');
        
        // バッファをクリア
        ob_end_clean();

        $fileName = "sample.xlsx";
 
        // ダウンロード
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$fileName.'"');
        header('Cache-Control: max-age=0');
        header('Pragma:no-cache');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer = new Writer($spreadsheet);
        $writer->save('php://output');
        
    }
}
?>