<?php

namespace shop\repositories\asystem;

use yii\httpclient\Client;


class Asystem
{
    // public $ctx = stream_context_create(array('http'=>
    //     array(
    //         'timeout' => 5,  //1200 Seconds is 20 Minutes
    //     )
    // ));
    //public $ctx = stream_context_create( [['http', ['timeout','50']]] );
    public $asystemForm;
    public $tpl;
    public $asystemHost = 'http://91.200.227.138:56088';
    //public $asystemHost = '192.168.6.22:8088';



    public function getPrice($template,$postData)
    {
        $client = new Client();
        try {
            $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($this->asystemHost . '/api/'.$template.'/')
                ->setData($postData)
                //->setData(http_build_query($_POST))
                //->setData(['test'=>'555'])
                ->send();
        } catch (InvalidConfigException $e) {
            throw new \DomainException('huy taam');
        }
        return  $response->content;
        //var_dump($response);

    }

    public function getForm($tpl)
    {
        
        // Ставим лимит по времени
        $this->tpl = $tpl;
        $ctx = stream_context_create(array('http'=>
            array(
                'timeout' => 5,  //1200 Seconds is 20 Minutes
            )
        ));
        if ( $this->asystemForm = file_get_contents($this->asystemHost."/api/".$tpl."/?table",false,$ctx) )
            return true;
        else
            return false;

    }


    // POST file_get_contents 
    // $postdata = http_build_query(
    // array(
    //     'var1' => 'some content',
    //     'var2' => 'doh'
    //     )
    // );
    // $opts = array('http' =>
    //     array(
    //         'method'  => 'POST',
    //         'header'  => 'Content-type: application/x-www-form-urlencoded',
    //         'content' => $postdata
    //     )
    // );
    // $context  = stream_context_create($opts);
     
    // $result = file_get_contents('http://example.com/submit.php', false, $context);



    // BASIC AUTORISATION
    // $context = stream_context_create(array(
    //       'http' => array(
    //           'header'  => "Authorization: Basic " . base64_encode("$username:$password")
    //       )
    //   ));
      
    //   $data = @file_get_contents($url, false, $context);
    //   if ($data !== false) {
    //       //ok
    //   } else {
    //       //bad
    //   }
}