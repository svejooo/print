<?php

namespace shop\repositories\asystem;



class Asystem
{

    public $asystemForm;
    public $tpl;
    public $ip = '192.168.6.22:8088';

    public function getForm($tpl)
    {
        // Production
        // $table = file_get_contents("http://91.200.224.132:56088/api/".$tpl."/?table");
        $this->tpl = $tpl;

        if ( $this->asystemForm = file_get_contents("http://192.168.6.22:8088/api/".$tpl."/?table") )
            return true;
        else
            return false;

//       if ( file_get_contents("http://192.168.6.22:8088/api/".$tpl."/?table") )
//         $this->asystemForm = '555';
//       else
//           return false;
    }

}