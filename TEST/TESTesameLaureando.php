<?php
require_once(dirname(__DIR__) . '/utils/EsameLaureando.php');

// Verifica che la classe EsameLaureando funzioni correttamente
class TESTesameLaureando{
    public function test(){
        $ausiliaria = new EsameLaureando();
        $ausiliaria->_nomeEsame = "STATISTICA";
        $ausiliaria->_votoEsame = 28;
        $aux = $ausiliaria->_nomeEsame;
        $aux1 = $ausiliaria->_votoEsame;

        // Verifica che i valori vengano aggiornati correttamente
        if($aux != "STATISTICA") {
            echo "errore nome esame su esameLaureando2";
        } 
        if($aux1 != 28) {
            echo "errore voto esame su esameLaureando2";
            echo "EsameLaureando : TEST SUPERATI";
        }
    }
}
?>