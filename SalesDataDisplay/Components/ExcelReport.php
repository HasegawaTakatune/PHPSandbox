<?php

use PhpOffice\PhpSpreadsheet\Writer\Xlsx AS Writer;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx AS Reader;
use PhpOffice\PhpSpreadsheet\IOFactory AS IOFactory;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\Cell;

class ExcelReport{

    private static array $reports;
    private static int $year;
    private static string $branch;
    private static string $category;
    private static int $age_from;
    private static int $age_to;
    private static array $gender;

    // 初期化
    public static function init($reports, $year, $branch, $category, $age_from, $age_to, $gender){
        self::$reports = $reports;
        self::$year = $year;
        self::$branch = $branch;
        self::$category = $category;
        self::$age_from = $age_from;
        self::$age_to = $age_to;
        self::$gender = $gender;
    }

    // サンプル出力
    public static function outSample(){

        $reader = new Reader();
        $spreadsheet = $reader->load('.\Reports\sample.xlsx');
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

    // 帳票出力
    public static function OutputReport(){

        if(!isset(self::$reports) || !is_array(self::$reports)) return -1;

        $del_sheet = array();

        $reader = new Reader();
        $spreadsheet = $reader->load('.\Reports\template.xlsx');

        if(in_array(ORDER_INFO, self::$reports)){
            self::ReportOrderInfo($spreadsheet);
        }else{
            array_push($del_sheet, ORDER_INFO);
        }

        if(in_array(BRANCH_ORDER, self::$reports)){
            self::ReportOrderInfoByBranch($spreadsheet);
        }else{
            array_push($del_sheet, BRANCH_ORDER);
        }

        $spreadsheet->getSheet(0);

        // バッファをクリア
        ob_end_clean();

        $const = function($c){ return $c;};
        $file_name = "{$const(self::$year)}年度_帳票.xlsx";
 
        // ダウンロード
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$file_name.'"');
        header('Cache-Control: max-age=0');
        header('Pragma:no-cache');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer = new Writer($spreadsheet);
        $writer->save('php://output');

    }

    // 注文情報出力
    public static function ReportOrderInfo(Spreadsheet $spreadsheet){

        $rows = Model::getOrderInfo(self::$year);
        $sheet = $spreadsheet->getSheet(ORDER_INFO);
        self::setHeader($sheet, "注文情報");

        $header1 = 'X2:AQ2';
        $header2 = 'X3:AQ3';
        $detail = 'X4:AQ4';
        
        $column = 6;
        $old_order_id = "";
        $old_customer_id = "";

        $total_column = 0;
        $total = 0;

        while($row = $rows->fetch(PDO::FETCH_ASSOC)){

            if($old_order_id != $row['order_id']){
                $column++;
                self::copyRange($sheet, $header1, "C{$column}");
                $sheet->setCellValue("B{$column}", "注ID {$row['order_id']}");
                $sheet->setCellValue("F{$column}", "支ID {$row['branch_id']}");
                $sheet->setCellValue("J{$column}", $row['branch_name']);

                $old_order_id = $row['order_id'];
                $old_customer_id = "";
                $column++;
            }

            if($old_customer_id != $row['customer_id']){
                self::copyRange($sheet, $header2, "C{$column}");
                $sheet->setCellValue("B{$column}", $row['order_date']);
                $sheet->setCellValue("G{$column}", "顧ID {$row['customer_id']}");
                $sheet->setCellValue("K{$column}", "{$row['last_name']} {$row['first_name']}");
            
                $old_customer_id = $row['customer_id'];
                $total_column = $column;
                $total = 0;
                $column++;
            }

            self::copyRange($sheet, $detail, "C{$column}");
            $sheet->setCellValue("B{$column}", "品ID {$row['product_id']}");
            $sheet->setCellValue("F{$column}", $row['category']);
            $sheet->setCellValue("J{$column}", $row['product_name']);

            $price = intval($row['price']);
            $price_format = number_format($price);
            $sheet->setCellValue("R{$column}", "\\{$price_format}");
            
            $total += $price;
            $total_format = number_format($total);
            $sheet->setCellValue("R{$total_column}", "計 \\{$total_format}");

            $column++;
        }

        // 行の削除
        $sheet->unmergeCells('X2:AA2');
        $sheet->unmergeCells('AB2:AE2');
        $sheet->unmergeCells('AF2:AQ2');

        $sheet->unmergeCells('X3:AB3');
        $sheet->unmergeCells('AC3:AF3');
        $sheet->unmergeCells('AG3:AM3');
        $sheet->unmergeCells('AN3:AQ3');

        $sheet->unmergeCells('X4:AA4');
        $sheet->unmergeCells('AB4:AE4');
        $sheet->unmergeCells('AF4:AM4');
        $sheet->unmergeCells('AN4:AQ4');

        $sheet->removeColumn('W',21);

        foreach($sheet->getColumnIterator() as $it){
            $index = $it->getColumnIndex();
            $sheet->getColumnDimension($index)->setWidth(3);
        }
        
        $sheet->setSelectedCell('A1');
    }

    // 支店別注文情報出力
    public static function ReportOrderInfoByBranch(Spreadsheet $spreadsheet){

        $rows = Model::getOrderInfoByBranch(self::$year, self::$branch);
        $sheet = $spreadsheet->getSheet(BRANCH_ORDER);
        self::setHeader($sheet, "支店別注文情報");

        $header = 'X2:AM2';
        $detail = 'X3:BK3';
        
        $column = 6;
        $old_order_id = "";
        $old_branch_id = "";

        $total_column = 0;
        $total = 0;

        while($row = $rows->fetch(PDO::FETCH_ASSOC)){

            if($old_branch_id != $row['branch_id']){
                $column++;
                self::copyRange($sheet, $header, "C{$column}");
                $sheet->setCellValue("B{$column}", "支ID {$row['branch_id']}");
                $sheet->setCellValue("F{$column}", $row['branch_name']);

                $old_branch_id = $row['branch_id'];
                $column++;
            }

            // if($old_customer_id != $row['customer_id']){
            //     self::copyRange($sheet, $detail, "C{$column}");
            //     $sheet->setCellValue("B{$column}", $row['order_date']);
            //     $sheet->setCellValue("G{$column}", "顧ID {$row['customer_id']}");
            //     $sheet->setCellValue("K{$column}", "{$row['last_name']} {$row['first_name']}");
            
            //     $old_customer_id = $row['customer_id'];
            //     $total_column = $column;
            //     $total = 0;
            //     $column++;
            // }

            self::copyRange($sheet, $detail, "C{$column}");
            $sheet->setCellValue("B{$column}", $row['order_date']);
            $sheet->setCellValue("G{$column}", "注ID {$row['order_id']}");
            $sheet->setCellValue("K{$column}", $row['category']);
            $sheet->setCellValue("O{$column}", "品ID {$row['product_id']}");
            $sheet->setCellValue("S{$column}", $row['product_name']);
            
            $price = intval($row['price']);
            $price_format = number_format($price);
            $sheet->setCellValue("AA{$column}", "\\{$price_format}");

            // $price = intval($row['price']);
            // $price_format = number_format(intval($row['price']));
            // $sheet->setCellValue("R{$column}", "\\{$price_format}");
            
            $total += $price;
            $total_format = number_format($total);
            $sheet->setCellValue("R{$total_column}", "計 \\{$total_format}");

            $column++;
        }

        // 行の削除
        $sheet->unmergeCells('X2:AA2');
        $sheet->unmergeCells('AB2:AE2');
        $sheet->unmergeCells('AF2:AQ2');

        $sheet->unmergeCells('X3:AB3');
        $sheet->unmergeCells('AC3:AF3');
        $sheet->unmergeCells('AG3:AM3');
        $sheet->unmergeCells('AN3:AQ3');

        $sheet->unmergeCells('X4:AA4');
        $sheet->unmergeCells('AB4:AE4');
        $sheet->unmergeCells('AF4:AM4');
        $sheet->unmergeCells('AN4:AQ4');

        $sheet->removeColumn('W',21);

        foreach($sheet->getColumnIterator() as $it){
            $index = $it->getColumnIndex();
            $sheet->getColumnDimension($index)->setWidth(3);
        }
        
        $sheet->setSelectedCell('A1');
    }

    // ヘッダー生成
    public static function setHeader(Worksheet $sheet, $title){

        $const = function($c){ return $c;};
        $sheet->setCellValue('B2', "{$const(self::$year)}年度");
        $sheet->setCellValue('B3', "{$title}");
    }

    // セルコピー
    // https://stackoverflow.com/questions/34590622/copy-style-and-data-in-phpexcel
    private static function copyRange( Worksheet $sheet, $srcRange, $dstCell) {
        // Validate source range. Examples: A2:A3, A2:AB2, A27:B100
        if( !preg_match('/^([A-Z]+)(\d+):([A-Z]+)(\d+)$/', $srcRange, $srcRangeMatch) ) {
            // Wrong source range
            return;
        }
        // Validate destination cell. Examples: A2, AB3, A27
        if( !preg_match('/^([A-Z]+)(\d+)$/', $dstCell, $destCellMatch) ) {
            // Wrong destination cell
            return;
        }
    
        $srcColumnStart = $srcRangeMatch[1];
        $srcRowStart = $srcRangeMatch[2];
        $srcColumnEnd = $srcRangeMatch[3];
        $srcRowEnd = $srcRangeMatch[4];
    
        $destColumnStart = $destCellMatch[1];
        $destRowStart = $destCellMatch[2];
    
        // For looping purposes we need to convert the indexes instead
        // Note: We need to subtract 1 since column are 0-based and not 1-based like this method acts.
    
        $srcColumnStart = Coordinate::columnIndexFromString($srcColumnStart) - 1;
        $srcColumnEnd = Coordinate::columnIndexFromString($srcColumnEnd) - 1;
        $destColumnStart = Coordinate::columnIndexFromString($destColumnStart) - 1;
    
        $rowCount = 0;
        for ($row = $srcRowStart; $row <= $srcRowEnd; $row++) {
            $colCount = 0;
            for ($col = $srcColumnStart; $col <= $srcColumnEnd; $col++) {
                $cell = $sheet->getCellByColumnAndRow($col, $row);
                $style = $sheet->getStyleByColumnAndRow($col, $row);
                $dstCell = Coordinate::stringFromColumnIndex($destColumnStart + $colCount) . (string)($destRowStart + $rowCount);
                $sheet->setCellValue($dstCell, $cell->getValue());
                $sheet->duplicateStyle($style, $dstCell);
    
                // Set width of column, but only once per row
                if ($rowCount === 0) {
                    $w = $sheet->getColumnDimensionByColumn($col)->getWidth();
                    $sheet->getColumnDimensionByColumn ($destColumnStart + $colCount)->setAutoSize(false);
                    $sheet->getColumnDimensionByColumn ($destColumnStart + $colCount)->setWidth($w);
                }
    
                $colCount++;
            }
    
            $h = $sheet->getRowDimension($row)->getRowHeight();
            $sheet->getRowDimension($destRowStart + $rowCount)->setRowHeight($h);
    
            $rowCount++;
        }
    
        foreach ($sheet->getMergeCells() as $mergeCell) {
            $mc = explode(":", $mergeCell);
            $mergeColSrcStart = Coordinate::columnIndexFromString(preg_replace("/[0-9]*/", "", $mc[0])) - 1;
            $mergeColSrcEnd = Coordinate::columnIndexFromString(preg_replace("/[0-9]*/", "", $mc[1])) - 1;
            $mergeRowSrcStart = ((int)preg_replace("/[A-Z]*/", "", $mc[0]));
            $mergeRowSrcEnd = ((int)preg_replace("/[A-Z]*/", "", $mc[1]));
    
            $relativeColStart = $mergeColSrcStart - $srcColumnStart;
            $relativeColEnd = $mergeColSrcEnd - $srcColumnStart;
            $relativeRowStart = $mergeRowSrcStart - $srcRowStart;
            $relativeRowEnd = $mergeRowSrcEnd - $srcRowStart;
    
            if (0 <= $mergeRowSrcStart && $mergeRowSrcStart >= $srcRowStart && $mergeRowSrcEnd <= $srcRowEnd && 0 < ($destColumnStart + $relativeColStart) && 0 < ($destColumnStart + $relativeColEnd)) {
                $targetColStart = Coordinate::stringFromColumnIndex($destColumnStart + $relativeColStart);
                $targetColEnd = Coordinate::stringFromColumnIndex($destColumnStart + $relativeColEnd);
                $targetRowStart = $destRowStart + $relativeRowStart;
                $targetRowEnd = $destRowStart + $relativeRowEnd;

                var_dump($targetColStart . ":" . $targetColEnd . ":" . $targetRowStart . ":" . $targetRowEnd);

                $merge = (string)$targetColStart . (string)($targetRowStart) . ":" . (string)$targetColEnd . (string)($targetRowEnd);
                //Merge target cells
                $sheet->mergeCells($merge);
            }
        }
    } 
}

?>