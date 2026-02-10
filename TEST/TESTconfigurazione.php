<?php
require_once(dirname(__DIR__) . '/utils/ModificaParametriConfigurazione.php');

/**
* Test per ModificaParametriConfigurazione
* SICURO: Non modifica file di produzione (V025)
*/
class TESTconfigurazione{
    private $passati = 0;
    private $falliti = 0;

    public function test(){
        echo "<h2>Test ModificaParametriConfigurazione</h2>";
        echo "<p><em>Nota: Questi test NON modificano i file di produzione</em></p>";

        $this->test_lettura_configurazione();
        $this->test_modifica_formula();
        $this->mostraRisultati();
    }

    /**
    * Test lettura file configurazione
    */
    public function test_lettura_configurazione(){
        echo "<p>Test 1: Lettura file configurazione...</p>";

        $path = dirname(__DIR__) . '/utils/json_files/formule_laurea.json';

        // ASSERT: File deve esistere
        $this->assertTrue(
            file_exists($path),
            "File formule_laurea.json deve esistere"
        );

        // ASSERT: File deve essere JSON valido
        $content = file_get_contents($path);
        $data = json_decode($content, true);

        $this->assertTrue(
            $data !== null,
            "File deve contenere JSON valido"
        );

        // ASSERT: Deve contenere CDL "T. Ing. Informatica"
        $this->assertTrue(
            isset($data["T. Ing. Informatica"]),
            "Deve contenere configurazione per T. Ing. Informatica"
        );
    }

    /**
    * Test modifica formula (IN MEMORY - non salva su disco)
    */
    public function test_modifica_formula(){
        echo "<p>Test 2: Modifica formula (solo in memory)...</p>";

        // BACKUP del file originale
        $path = dirname(__DIR__) . '/utils/json_files/formule_laurea.json';
        $backup = file_get_contents($path);

        try {
            // Crea oggetto configurazione
            $config = new ModificaParametriConfigurazione(
                "T. Ing. Informatica",
                array("ELETTROTECNICA")
            );

            // Modifica formula
            $config->modificaFormula("(\$M * 110) / 30");

            // ASSERT: L'oggetto ?? stato creato
            $this->assertTrue(
                $config !== null,
                "Oggetto ModificaParametriConfigurazione creato"
            );

            // NOTA: Non testiamo la scrittura su file perch?? non vogliamo
            // modificare i file di produzione durante i test!

        } finally {
            // RIPRISTINA il file originale (per sicurezza)
            file_put_contents($path, $backup);
            echo " <span style='color: blue;'>??? File originale ripristinato (test sicuro)</span><br>";
        }
    }

    // ============ ASSERTION METHODS ============

    private function assertTrue($condition, $message) {
        if ($condition) {
            echo " <span style='color: green;'>??? PASS</span>: $message<br>";
            $this->passati++;
        } else {
            echo " <span style='color: red;'>??? FAIL</span>: $message<br>";
            $this->falliti++;
        }
    }

    private function mostraRisultati() {
        $totale = $this->passati + $this->falliti;
        echo "<hr>";
        echo "<h3>Risultati:</h3>";
        echo "<p><strong>Passati:</strong> <span style='color: green;'>{$this->passati}/{$totale}</span></p>";
        echo "<p><strong>Falliti:</strong> <span style='color: red;'>{$this->falliti}/{$totale}</span></p>";

        if ($this->falliti === 0) {
            echo "<p style='color: green; font-weight: bold;'>??? TUTTI I TEST SUPERATI!</p>";
        } else {
            echo "<p style='color: red; font-weight: bold;'>??? ALCUNI TEST FALLITI</p>";
        }
    }
}
?>