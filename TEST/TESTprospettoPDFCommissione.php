<?php
require_once(dirname(__DIR__) . '/utils/ProspettoPDFCommissione.php');
class TESTprospettoPDFCommissione{
    public function test(){
        $valore = array(123456);
        $vecchio = file_get_contents(dirname(__DIR__) . '/data/pdf_generati/prospettoCommissione.pdf');
        $val = new ProspettoPDFCommissione($valore,"2024_01_05", "T. Ing. Informatica");
        $val->generaProspettiCommissione();
        $aux = file_get_contents(dirname(__DIR__) . '/data/pdf_generati/prospettoCommissione.pdf');
        file_put_contents(dirname(__DIR__) . '/data/pdf_generati/prospettoCommissione.pdf',$vecchio);

        $vecchio1 = file_get_contents(dirname(__DIR__) . '/data/pdf_generati/prospettoCommissione.pdf');
        $var = new ProspettoPDFCommissione($valore,"2018_01_05", "T. Ing. Informatica");
        $var->generaProspettiCommissione();
        $aux1 = file_get_contents(dirname(__DIR__) . '/data/pdf_generati/prospettoCommissione.pdf');
        file_put_contents(dirname(__DIR__) . '/data/pdf_generati/prospettoCommissione.pdf',$vecchio1);

        if($aux == $aux1)
        echo "prospetti non generati correttamente";
        else
        echo "ProspettoPDFCommissione : TEST SUPERATI";
    }
}
?>