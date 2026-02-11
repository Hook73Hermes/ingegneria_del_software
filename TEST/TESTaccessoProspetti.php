<?php
require_once(dirname(__DIR__) . '/utils/AccessoProspetti.php');

// Verifica che la classe AccessoProspetti funzioni correttamente
class TESTaccessoProspetti{
    public function test(){
        $val = new AccessoProspetti();

        // Il percorso di accesso ai dati deve essere corretto
        if($val->fornisciAccesso() != 'data\pdf_generati\prospettoCommissione.pdf' ) {
            echo "invio file errato";
        } else {
            echo "AccessoProspetti : TEST SUPERATI";
        }
    }
}
?>