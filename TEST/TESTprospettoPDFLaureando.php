<?php
require_once(dirname(__DIR__) . '/utils/ProspettoPDFLaureando.php');

// Verifica che la classe ProspettoPDFLaureando funzioni correttamente
class TESTprospettoPDFLaureando{
    public function test(){
        $val = new ProspettoPDFLaureando(123456,"T. Ing. Informatica","2024-01-05");
        $vecchio = file_get_contents(dirname(__DIR__) . '/data/pdf/123456-prospetto.pdf');
        $val->generaProspetto();
        $aux = file_get_contents(dirname(__DIR__) . '/data/pdf/123456-prospetto.pdf');
        file_put_contents(dirname(__DIR__) . '/data/pdf/123456-prospetto.pdf',$vecchio);

        $vecchio1 = file_get_contents(dirname(__DIR__) . '/data/pdf/123456-prospetto.pdf');
        $val1 = new ProspettoPDFLaureando(123456, "T. Ing. Informatica", "2018-01-05");
        $val1->generaProspetto();
        $aux1 = file_get_contents(dirname(__DIR__) . '/data/pdf/123456-prospetto.pdf');
        file_put_contents(dirname(__DIR__) . '/data/pdf/123456-prospetto.pdf',$vecchio1);

        // Verifica che i prospetti siano diversi in funzione della data di laurea
        echo "<h2>Test ProspettoPDFLaureando</h2>";
        if ($aux1 != $aux) {
            echo "<p style='color: green; font-weight: bold;'>TUTTI I TEST SUPERATI!</p>";
        } else {
            echo "<p style='color: red; font-weight: bold;'>TEST NON SUPERATI!</p>";
        }
    }
}
?>