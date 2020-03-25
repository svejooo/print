<?php


namespace frontend\controllers;


use Com\Tecnick\Barcode\Barcode;
use kartik\mpdf\Pdf;
use yii\web\Controller;

class BarcodeController extends Controller
{




    public function actionIndex(){

        $content = $this->testBarcode();
        $content.= $this->testBarcode();
        $content.= $this->testBarcode();

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_BLANK,
            // A4 paper format
            //'format' => Pdf::FORMAT_LEGAL,
            'format' => ['50','50'],
            // portrait orientation


            // stream to browser inline
            //'destination' => Pdf::DEST_DOWNLOAD,
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,

        ]);

        $pdf->marginLeft = 0;
        $pdf->marginRight = 0;
        $pdf->marginTop = 0;
        $pdf->marginBottom = 0;
        $pdf->marginHeader = 0;
        $pdf->marginFooter = 0;

        // return the pdf output as per the destination setting
        return $pdf->render();
    }


    public function testBarcode(){
        $dataStr = "
        BEGIN:VCARD
        VERSION:3.0
        N:Василий Иванович Пупкин
        TEL;HOME;VOICE:0043-7252-72720
        TEL;WORK;VOICE:0043-7252-72720
        EMAIL:email@example.com
        ORG:TEC-IT
        URL:http://www.example.com
        END:VCARD";

        $barcode = new Barcode();
        // generate a barcode
        $bobj = $barcode->getBarcodeObj(
            'QRCODE',                     // barcode type and additional comma-separated parameters
            $dataStr,          // data string to encode
            -4,                             // bar width (use absolute or negative value as multiplication factor)
            -4,                            // bar height (use absolute or negative value as multiplication factor)
            'black',                        // foreground color
            array(-2, -2, -2, -2)                 // padding (use absolute or negative values as multiplication factors)
        )->setBackgroundColor('white');     // background color


        //return $bobj->getSvgCode();
        return $bobj->getSvgCodeForPdf();
    }







    public function actionBarcode(){

        $command = 'ls '.Yii::getAlias('@frontend') . '/web/svg/*.csv';
        exec($command, $arrCSV);
        $this->csvFile =  $arrCSV[0];  ///app/frontend/web/svg/codes.csv
        $this->svgPwd =  Yii::getAlias('@frontend') . '/web/svg/' . basename($this->csvFile, '.csv' ) ;

        // Создаем папочку по имени файла
        exec('mkdir '. $this->svgPwd );

        // Читаем файл
        $handle = fopen($this->csvFile,'r') or die('Не открывается - нет СТРОК');
        $i = 1;
        while ( ($data = fgetcsv($handle) ) !== FALSE ) {
            // Имя файла по номеру строки
            $this->createSvgBarcode( $data[0] , $i) ;
            // имя файла по значению баркода
            //$this->createSvgBarcode( $data[0] , $i) ;
            $i++;
        }

        $this->createZip();
        echo 'Отрубилось на ' . $i  ;
    }


    private function createZip()
    {
        $command = 'cd '.$this->svgPwd.'/ &&  zip -9 '.$this->svgPwd.'.zip  *' ;
        exec($command);
    }




    private function createSvgBarcode($str, $filename = false)
    {

        // instantiate the barcode class
        $barcode = new Barcode();
        // generate a barcode
        $bobj = $barcode->getBarcodeObj(
            'DATAMATRIX',                     // barcode type and additional comma-separated parameters
            $str,          // data string to encode
            -8,                             // bar width (use absolute or negative value as multiplication factor)
            -8,                            // bar height (use absolute or negative value as multiplication factor)
            'black',                        // foreground color
            array(-2, -2, -2, -2)                 // padding (use absolute or negative values as multiplication factors)
        )->setBackgroundColor('white');     // background color


        // output the barcode as HTML div (see other output formats in the documentation and examples)
        //        echo $bobj->getHtmlDiv();
        //        echo $bobj->getExtendedCode();
        if(!$filename)
            file_put_contents($this->svgPwd .  '/' . $str . '.svg', $bobj->getSvgCode() ) ;
        else
            file_put_contents($this->svgPwd .  '/' . $filename.'.svg', $bobj->getSvgCode() ) ;

        //$filename = $this->svgFileName;
        echo  $filename . '<br>';
    }

}