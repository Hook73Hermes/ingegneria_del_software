<?php
require_once(dirname(__DIR__) . '/utils/ProspettoPDFCommissione.php');

// Verifica che la classe ProspettoPDFCommissione funzioni correttamente
class TESTprospettoPDFCommissione{
    public function test(){
        $valore = array(123456);

        $vecchio = file_get_contents(dirname(__DIR__) . '/data/pdf_generati/prospettoCommissione.pdf');
        $val = new ProspettoPDFCommissione($valore,"2024-01-05", "T. Ing. Informatica");
        $val->generaProspettiCommissione();
        $aux = file_get_contents(dirname(__DIR__) . '/data/pdf_generati/prospettoCommissione.pdf');
        file_put_contents(dirname(__DIR__) . '/data/pdf_generati/prospettoCommissione.pdf',$vecchio);

        $vecchio1 = file_get_contents(dirname(__DIR__) . '/data/pdf_generati/prospettoCommissione.pdf');
        $val1 = new ProspettoPDFCommissione($valore,"2018-01-05", "T. Ing. Informatica");
        $val1->generaProspettiCommissione();
        $aux1 = file_get_contents(dirname(__DIR__) . '/data/pdf_generati/prospettoCommissione.pdf');
        file_put_contents(dirname(__DIR__) . '/data/pdf_generati/prospettoCommissione.pdf',$vecchio1);

        // Verifica che i prospetti siano diversi in funzione della data di laurea
        if($aux == $aux1) {
            echo "prospetti non generati correttamente";
        } else {
            echo "ProspettoPDFCommissione : TEST SUPERATI";
        }
    }
}
?>