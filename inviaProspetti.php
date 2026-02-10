<?php
require_once(__DIR__ . '/utils/InvioPDFLaureando2.php');

// Verifica che la richiesta sia POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $invio = new InvioPDFLaureando2();
    $invio->invioProspetti();
    echo "i prospetti sono stati inviati";
} 
else {
    die("Errore: Richiesta non valida. Usa il form per inviare i prospetti.");
}
?>