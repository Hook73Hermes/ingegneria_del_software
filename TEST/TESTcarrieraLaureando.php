<?php
require_once(dirname(__DIR__) . '/utils/CarrieraLaureando.php');

/**
 * Test per la classe CarrieraLaureando
 * Migliorato con assertion reali (V025)
 */
class TESTcarrieraLaureando{
    private $passati = 0;
    private $falliti = 0;
    
    public function test(){
        echo "<h2>Test CarrieraLaureando</h2>";
        $this->test_costruttore();
        $this->test_media();
        $this->test_crediti();
        $this->mostraRisultati();
    }
    
    /**
     * Test costruttore e caricamento esami
     */
    public function test_costruttore(){
        echo "<p>Test 1: Costruttore e caricamento esami...</p>";
        
        try {
            $carriera = new CarrieraLaureando(123456, "T. Ing. Informatica");
            
            // ASSERT: Deve avere esami
            $this->assertTrue(
                count($carriera->_esami) > 0,
                "La carriera deve contenere esami"
            );
            
            // ASSERT: Primo esame deve essere ELETTROTECNICA
            $primo_esame = "ELETTROTECNICA";
            $this->assertEquals(
                $carriera->_esami[0]->_nomeEsame,
                $primo_esame,
                "Il primo esame deve essere ELETTROTECNICA"
            );
            
        } catch (Exception $e) {
            $this->fail("Costruttore ha lanciato eccezione: " . $e->getMessage());
        }
    }
    
    /**
     * Test calcolo media
     */
    public function test_media(){
        echo "<p>Test 2: Calcolo media esami...</p>";
        
        $carriera = new CarrieraLaureando(123456, "T. Ing. Informatica");
        $media = $carriera->restituisciMedia();
        
        // ASSERT: Media deve essere un numero
        $this->assertTrue(
            is_numeric($media),
            "La media deve essere un numero"
        );
        
        // ASSERT: Media deve essere nel range valido (18-30)
        $this->assertTrue(
            $media >= 18 && $media <= 30,
            "La media deve essere tra 18 e 30, ottenuto: " . $media
        );
    }
    
    /**
     * Test conteggio crediti
     */
    public function test_crediti(){
        echo "<p>Test 3: Conteggio crediti...</p>";
        
        $carriera = new CarrieraLaureando(123456, "T. Ing. Informatica");
        $crediti_media = $carriera->creditiCheFannoMedia();
        $crediti_totali = $carriera->creditiCurricolariConseguiti();
        
        // ASSERT: Crediti devono essere positivi
        $this->assertTrue(
            $crediti_media > 0,
            "Crediti che fanno media devono essere > 0"
        );
        
        $this->assertTrue(
            $crediti_totali >= $crediti_media,
            "Crediti totali devono essere >= crediti che fanno media"
        );
    }
    
    // ============ ASSERTION METHODS ============
    
    private function assertTrue($condition, $message) {
        if ($condition) {
            echo "  <span style='color: green;'>✓ PASS</span>: $message<br>";
            $this->passati++;
        } else {
            echo "  <span style='color: red;'>✗ FAIL</span>: $message<br>";
            $this->falliti++;
        }
    }
    
    private function assertEquals($actual, $expected, $message) {
        if ($actual === $expected) {
            echo "  <span style='color: green;'>✓ PASS</span>: $message<br>";
            $this->passati++;
        } else {
            echo "  <span style='color: red;'>✗ FAIL</span>: $message<br>";
            echo "    Atteso: '$expected', Ottenuto: '$actual'<br>";
            $this->falliti++;
        }
    }
    
    private function fail($message) {
        echo "  <span style='color: red;'>✗ FAIL</span>: $message<br>";
        $this->falliti++;
    }
    
    private function mostraRisultati() {
        $totale = $this->passati + $this->falliti;
        echo "<hr>";
        echo "<h3>Risultati:</h3>";
        echo "<p><strong>Passati:</strong> <span style='color: green;'>{$this->passati}/{$totale}</span></p>";
        echo "<p><strong>Falliti:</strong> <span style='color: red;'>{$this->falliti}/{$totale}</span></p>";
        
        if ($this->falliti === 0) {
            echo "<p style='color: green; font-weight: bold;'>✓ TUTTI I TEST SUPERATI!</p>";
        } else {
            echo "<p style='color: red; font-weight: bold;'>✗ ALCUNI TEST FALLITI</p>";
        }
    }
}
?>
