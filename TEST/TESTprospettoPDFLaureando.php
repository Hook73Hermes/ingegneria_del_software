<?php
require_once(dirname(__DIR__) . '/utils/ProspettoPDFLaureando.php');
class TESTprospettoPDFLaureando{
    public function test(){
        $val = new ProspettoPDFLaureando(123456,"T. Ing. Informatica","2024_01_05");
        $vecchio = file_get_contents(dirname(__DIR__) . '/data/pdf_generati/123456-prospetto.pdf');
        $val->generaProspetto();
        $aux = file_get_contents(dirname(__DIR__) . '/data/pdf_generati/123456-prospetto.pdf');
        file_put_contents(dirname(__DIR__) . '/data/pdf_generati/123456-prospetto.pdf',$vecchio);

        $vecchio1 = file_get_contents(dirname(__DIR__) . '/data/pdf_generati/123456-prospetto.pdf');
        $var = new ProspettoPDFLaureando(123456, "T. Ing. Informatica", "2018_01_05");
        $var->generaProspetto();
        $aux1 = file_get_contents(dirname(__DIR__) . '/data/pdf_generati/123456-prospetto.pdf');
        file_put_contents(dirname(__DIR__) . '/data/pdf_generati/123456-prospetto.pdf',$vecchio1);

        if($aux == $aux1)
            echo "prospetti non generati correttamente";
        else
            echo "ProspettoPDFLaureando : TEST SUPERATI";
    }
}
?>