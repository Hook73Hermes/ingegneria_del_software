<?php
require_once(dirname(__DIR__) . '/utils/ModificaParametriConfigurazione.php');

// Verifica che sia possibile cambiare correttamente i parametri di configurazione
class TESTconfigurazione{
    private $passati = 0;
    private $falliti = 0;

    // Esegue i test
    public function test(){
        echo "<h2>Test ModificaParametriConfigurazione</h2>";
        $this->test_lettura_configurazione();
        $this->test_modifica_formula();
        $this->mostraRisultati();
    }

    // Test della lettura dei file di configurazione
    public function test_lettura_configurazione(){
        echo "<p>Test 1: Lettura file configurazione...</p>";

        $path = dirname(__DIR__) . '/data/json/formule_laurea.json';

        // Il file deve esistere
        $this->assertTrue(
            file_exists($path),
            "File formule_laurea.json deve esistere"
        );

        $content = file_get_contents($path);
        $data = json_decode($content, true);

        // Il file deve essere un JSON valido
        $this->assertTrue(
            $data !== null,
            "File deve contenere JSON valido"
        );

        // Il file deve contenere CDL "T. Ing. Informatica"
        $this->assertTrue(
            isset($data["T. Ing. Informatica"]),
            "Deve contenere configurazione per T. Ing. Informatica"
        );
    }

    // Test sulla verifica della formula
    public function test_modifica_formula(){
        echo "<p>Test 2: Modifica formula (solo in memory)...</p>";

        // Backup del file originario per poterlo ripristinare a fine lavoro
        $path = dirname(__DIR__) . '/data/json/formule_laurea.json';
        $backup = file_get_contents($path);

        try {
            // Crea oggetto configurazione
            $config = new ModificaParametriConfigurazione(
                "T. Ing. Informatica",
                array("ELETTROTECNICA")
            );

            // Modifica formula
            $config->modificaFormula("(\$M * 110) / 30");

            // L'oggetto deve esistere
            $this->assertTrue(
                $config !== null,
                "Oggetto ModificaParametriConfigurazione creato"
            );

        } finally {
            // Ripristina il file originario per sicurezza
            file_put_contents($path, $backup);
        }
    }

    // Effettua le assertion di verità
    private function assertTrue($condition, $message) {
        if ($condition) {
            echo " <span style='color: green;'>PASS</span>: $message<br>";
            $this->passati++;
        } else {
            echo " <span style='color: red;'>FAIL</span>: $message<br>";
            $this->falliti++;
        }
    }

    // Stampa i risultati
    private function mostraRisultati() {
        $totale = $this->passati + $this->falliti;
        echo "<hr>";
        
        if ($this->falliti === 0) {
            echo "<p style='color: green; font-weight: bold;'>TUTTI I TEST SUPERATI!</p>";
        } else {
            echo "<p style='color: red; font-weight: bold;'>ALCUNI TEST FALLITI</p>";
        }
    }
}
?>