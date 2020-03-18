<?php

namespace frontend\controllers\shop;


use backend\forms\Shop\CategorySearch;
use backend\forms\Shop\ProductSearch;
use Com\Tecnick\Barcode\Barcode;
use shop\entities\Shop\Category;
use shop\entities\shop\Product\Modification;
use shop\entities\shop\Product\Product;
use shop\forms\shop\Product\PhotosForm;
use shop\services\manage\Shop\ProductManageService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class CatalogController extends Controller
{

    private $service;
    public $svgPwd;
    public $csvFileName;
    public $csvFile;

    public $layout = 'catalog';


    public function __construct($id, $module, ProductManageService $service,  $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
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

    public function actionQr(){
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
            -8,                             // bar width (use absolute or negative value as multiplication factor)
            -8,                            // bar height (use absolute or negative value as multiplication factor)
            'black',                        // foreground color
            array(-2, -2, -2, -2)                 // padding (use absolute or negative values as multiplication factors)
        )->setBackgroundColor('white');     // background color

         echo $bobj->getHtmlDiv();
    }

    private function createSvgBarcode($str, $filename = false)
    {
        $dataStr = "
        BEGIN:VCARD
        VERSION:2.1
        N:Василий Иванович Пупкин
        TEL;HOME;VOICE:0043-7252-72720
        TEL;WORK;VOICE:0043-7252-72720
        EMAIL:email@example.com
        ORG:TEC-IT
        URL:http://www.example.com
        END:VCARD";

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

    public function  actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Product::find()->active(),
            //'query' => Product::find()->active()->all(),
            //'query' => $product->getModifications()->orderBy('name'),
            'sort' => [
               'defaultOrder' => ['id' => SORT_DESC],
               'attributes' =>[
                   'id',
                   'name',
                   'price' => [
                       'asc' => ['price_new' => SORT_ASC],
                       'desc' => ['price_new' => SORT_DESC],
                   ]
               ]
            ],
            'pagination' => false,
        ]);

        $cat = Category::find()->roots()->one();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'category'     => $cat,
        ]);

        //$searchModel = Product::find()->active()->all();
        //var_dump( $searchModel[0]);
    }


//    public function  actionIndex()
//    {
//
//        $searchModel = new CategorySearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//
//        return $this->render('index', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//        ]);
//    }


    public function actionView($id)
    {
        $product = $this->findModel($id);

        $modificationsProvider = new ActiveDataProvider([
            //'query' => $product->getModifications()->orderBy('name'),
            'query' => $product->getModifications()->orderBy('name'),
            'key' => function (Modification $modification) use ($product) {
                return [
                    'product_id' => $product->id,
                    'id' => $modification->id,
                ];
            },
            'pagination' => false,
        ]);


        $photosForm = new PhotosForm();
        if ($photosForm->load(Yii::$app->request->post()) && $photosForm->validate()) {
            try {
                $this->service->addPhotos($product->id, $photosForm);
                return $this->redirect(['view', 'id' => $product->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('view', [
            'product' => $product,
            'modificationsProvider' => $modificationsProvider,
            'photosForm' => $photosForm,
        ]);
    }


    #########################################################################
    protected function findModel($id): Product
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }


    private function getZipFile()
    {
        /* TODO не работает   */
        $file_name = basename($this->svgPwd . '.zip');

        header("Content-Type: application/zip");
        header("Content-Disposition: attachment; filename=$file_name");
        header("Content-Length: " . filesize( $this->svgPwd . '.zip' ));

        readfile($this->svgPwd . '.zip');
        echo $this->svgPwd . '.zip';
    }




}