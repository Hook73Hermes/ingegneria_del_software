<?php
header('Content-Type: application/json');

// Verifica che il metodo utilizzato sia POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Errore: Richiesta non valida."
    ]);
    exit();
}

// Path di ricerca locale e url per il browser differiscono
$pdf_path_fisico = __DIR__ . '/data/pdf/prospettoCommissione.pdf';
$pdf_url_web = 'data/pdf/prospettoCommissione.pdf';

// Controlla che sia già stato generato il prospetto PDF
if (file_exists($pdf_path_fisico)) {
    // SUCCESSO: Restituisce il path per aprirlo
    echo json_encode([
        "success" => true,
        "message" => "Prospetto trovato. Apertura in corso...",
        "pdf_url" => $pdf_url_web
    ]);
} else {
    // ERRORE: Il file non c'è (magari è stato cancellato o la generazione è fallita)
    echo json_encode([
        "success" => false,
        "message" => "Errore: Il file PDF del prospetto non è stato trovato sul server."
    ]);
}
?>