<?php
require_once(__DIR__ . '/utils/InvioPDFLaureando.php');

// Verifica che la richiesta sia POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Crea oggetto per invio email
        $invio = new InvioPDFLaureando();
        
        // Invia i prospetti e ottiene il risultato
        $risultato = $invio->invioProspetti();
        
        // Restituisce JSON di successo
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => $risultato['message'],
            'emails_inviate' => $risultato['inviati'],
            'errori' => $risultato['falliti']
        ]);
        
    } catch (Exception $e) {
        // Restituisce JSON di errore
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Errore durante l\'invio: ' . $e->getMessage()
        ]);
    }
} else {
    // Errore se il metodo non è POST (l'utente non ha usato il form)
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Errore: Richiesta non valida. Usa il form per inviare i prospetti.'
    ]);
}
?>