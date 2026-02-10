<?php
require_once(dirname(__DIR__) . '/utils/CarrieraLaureando.php');
class TESTcarrieraLaureando{
    public function test(){
        $this->test_costruttore();
        $this->test_media();
        echo "carrieraLaureando2 : TEST SUPERATI";
    }
    public function test_costruttore(){
          $val = new CarrieraLaureando(123456, "T. Ing. Informatica");
          $primo_esame = "ELETTROTECNICA";
          if($val->_esami[0]->_nomeEsame != $primo_esame)
              echo "esami non inseriti correttamente";
    }
    public function test_media(){
        $val = new CarrieraLaureando(123456, "T. Ing. Informatica");
        if ($val->restituisciMedia() < 23 || $val->restituisciMedia() > 24)
            echo "media non calcolata correttamente";
    }
}
?>