<?php

// Classe per la cancellazione dei dati relativi all'appello di laurea
class CancellazioneDatiAppello {
    
    /**
     * Cancella tutti i PDF generati nella cartella data/pdf/
     * @access public
     * @return array Risultato con conteggio file eliminati
     */
    public function cancellaPDF() {
        $pdf_dir = dirname(__DIR__) . '/data/pdf/';
        $files_eliminati = 0;
        $errori = [];
        
        // Verifica che la directory esista
        if (!is_dir($pdf_dir)) {
            return [
                'success' => false,
                'message' => 'Directory PDF non trovata',
                'files_eliminati' => 0
            ];
        }
        
        // Scansiona la directory
        $files = glob($pdf_dir . '*.pdf');
        
        // Elimina ogni file PDF
        foreach ($files as $file) {
            if (is_file($file)) {
                if (unlink($file)) {
                    $files_eliminati++;
                } else {
                    $errori[] = basename($file);
                }
            }
        }
        
        return [
            'success' => count($errori) == 0,
            'message' => $files_eliminati > 0 
                ? "Eliminati $files_eliminati file PDF" 
                : "Nessun file PDF da eliminare",
            'files_eliminati' => $files_eliminati,
            'errori' => $errori
        ];
    }
    
    /**
     * Cancella il file ausiliario.json
     * @access public
     * @return array Risultato dell'operazione
     */
    public function cancellaAusiliario() {
        $ausiliario_path = dirname(__DIR__) . '/data/json/ausiliario.json';
        
        // Verifica che il file esista
        if (!file_exists($ausiliario_path)) {
            return [
                'success' => false,
                'message' => 'File ausiliario.json non trovato'
            ];
        }
        
        // Elimina il file
        if (unlink($ausiliario_path)) {
            return [
                'success' => true,
                'message' => 'File ausiliario.json eliminato con successo'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Errore durante l\'eliminazione di ausiliario.json'
            ];
        }
    }
    
    /**
     * Cancella tutti i dati dell'appello di laurea (PDF + ausiliario.json)
     * @access public
     * @return array Risultato complessivo dell'operazione
     */
    public function cancellaTuttiDati() {
        $risultato_pdf = $this->cancellaPDF();
        $risultato_ausiliario = $this->cancellaAusiliario();
        
        $successo = $risultato_pdf['success'] && $risultato_ausiliario['success'];
        
        // Costruisce messaggio riepilogativo
        $messaggi = [];
        if ($risultato_pdf['files_eliminati'] > 0) {
            $messaggi[] = $risultato_pdf['message'];
        }
        if ($risultato_ausiliario['success']) {
            $messaggi[] = $risultato_ausiliario['message'];
        }
        
        if (empty($messaggi)) {
            $messaggi[] = "Nessun dato da cancellare";
        }
        
        return [
            'success' => $successo,
            'message' => implode('. ', $messaggi),
            'dettagli' => [
                'pdf' => $risultato_pdf,
                'ausiliario' => $risultato_ausiliario
            ]
        ];
    }
}
?>