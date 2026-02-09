<?php
require_once('C:\Users\franc\Local Sites\genera-prospetti-laurea\app\public\utils\InvioPDFLaureando2.php');

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