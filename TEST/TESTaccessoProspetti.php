<?php
require_once(dirname(__DIR__) . '/utils/AccessoProspetti.php');
class TESTaccessoProspetti{
    public function test(){
        $val = new AccessoProspetti();
        if($val->fornisciAccesso() != 'data\pdf_generati\prospettoCommissione.pdf' )
            echo "invio file errato";
        else
            echo "AccessoProspetti : TEST SUPERATI";
    }
}
?>