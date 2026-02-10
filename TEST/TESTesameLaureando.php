<?php
require_once(dirname(__DIR__) . '/utils/EsameLaureando.php');
class TESTesameLaureando{
    public function test(){
        $ausiliaria = new EsameLaureando();
        $ausiliaria->_nomeEsame = "STATISTICA";
        $ausiliaria->_votoEsame = 28;
        $aux = $ausiliaria->_nomeEsame;
        if($aux != "STATISTICA")
            echo "errore nome esame su esameLaureando2";
        $aux1 = $ausiliaria->_votoEsame;
        if($aux1 != 28)
            echo "errore voto esame su esameLaureando2";
        echo "EsameLaureando : TEST SUPERATI";
    }
}
?>
