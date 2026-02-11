<?php
require_once(dirname(__DIR__) . '/utils/AccessoProspetti.php');

// Verifica che la classe AccessoProspetti funzioni correttamente
class TESTaccessoProspetti{
    public function test(){
        $val = new AccessoProspetti();

        // Il percorso di accesso ai dati deve essere corretto
        echo "<h2>Test AccessoProspetti</h2>";
        if ($val->fornisciAccesso() === 'data\pdf_generati\prospettoCommissione.pdf') {
            echo "<p style='color: green; font-weight: bold;'>TUTTI I TEST SUPERATI!</p>";
        } else {
            echo "<p style='color: red; font-weight: bold;'>TEST NON SUPERATI!</p>";
        }
    }
}
?>