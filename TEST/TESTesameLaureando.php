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
        echo "<h2>Test EsameLaureando</h2>";
        if ($aux === "STATISTICA" && $aux1 === 28) {
            echo "<p style='color: green; font-weight: bold;'>TUTTI I TEST SUPERATI!</p>";
        } else {
            echo "<p style='color: red; font-weight: bold;'>TEST NON SUPERATI!</p>";
        }
    }
}
?>