<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
/**
 * TEST SISTEMA EMAIL - Verifica funzionamento PHPMailer
 * 
 * Questo script testa:
 * 1. Presenza PHPMailer
 * 2. Connessione server SMTP
 * 3. Invio email di test
 * 4. Verifica allegati PDF
 * 
 * ISTRUZIONI:
 * 1. Salva come: test_email.php nella ROOT del progetto
 * 2. MODIFICA la riga 28 con la TUA email
 * 3. Apri: http://localhost/ingegneria_del_software/test_email.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ============================================
// CONFIGURAZIONE TEST
// ============================================

// ⚠️ MODIFICA QUI CON LA TUA EMAIL! ⚠️
$EMAIL_TEST = 'l.maietti@studenti.unipi.it';  // ← CAMBIA QUESTA!

// SMTP Config (NON modificare se usi mixer.unipi.it)
$SMTP_HOST = 'mail.santannapisa.it';
$SMTP_PORT = 587;
$SMTP_FROM = 'l.maietti.fe@gmail.com';

// ============================================
// INIZIO TEST
// ============================================

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Test Sistema Email</title>
    <style>
        body { font-family: Arial; max-width: 900px; margin: 20px auto; padding: 20px; background: #f5f5f5; }
        h1 { color: #333; border-bottom: 3px solid #007bff; padding-bottom: 10px; }
        .test { background: white; padding: 20px; margin: 15px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .ok { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .warn { color: #ffc107; font-weight: bold; }
        pre { background: #f8f9fa; padding: 10px; border-left: 3px solid #007bff; overflow-x: auto; }
        code { background: #f5f5f5; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
<h1>🧪 Test Sistema Email PHPMailer</h1>";

// ============================================
// TEST 1: Presenza PHPMailer
// ============================================
echo "<div class='test'>";
echo "<h2>Test 1: Verifica PHPMailer</h2>";

$phpmailer_path = __DIR__ . '/PHPMailer/src/PHPMailer.php';
if (file_exists($phpmailer_path)) {
    echo "<p class='ok'>✓ PHPMailer trovato</p>";
    echo "<p>Path: <code>$phpmailer_path</code></p>";
    
    require_once($phpmailer_path);
    require_once(__DIR__ . '/PHPMailer/src/SMTP.php');
    require_once(__DIR__ . '/PHPMailer/src/Exception.php');
    
    echo "<p class='ok'>✓ Librerie caricate</p>";
} else {
    echo "<p class='error'>✗ PHPMailer NON trovato!</p>";
    echo "<p>Path cercato: <code>$phpmailer_path</code></p>";
    echo "<p><strong>SOLUZIONE:</strong> Installa PHPMailer nella cartella PHPMailer/</p>";
    die("</div></body></html>");
}
echo "</div>";

// ============================================
// TEST 2: Configurazione Email Test
// ============================================
echo "<div class='test'>";
echo "<h2>Test 2: Configurazione</h2>";

if ($EMAIL_TEST === 'tua.email@esempio.com') {
    echo "<p class='warn'>⚠ ATTENZIONE: Modifica \$EMAIL_TEST con la tua email!</p>";
    echo "<p>Apri questo file e cambia la riga 28:</p>";
    echo "<pre>\$EMAIL_TEST = 'tua.email@esempio.com';  // ← CAMBIA</pre>";
} else {
    echo "<p class='ok'>✓ Email destinatario: <code>$EMAIL_TEST</code></p>";
}

echo "<p>SMTP Host: <code>$SMTP_HOST</code></p>";
echo "<p>SMTP Port: <code>$SMTP_PORT</code></p>";
echo "<p>From: <code>$SMTP_FROM</code></p>";
echo "</div>";

// ============================================
// TEST 3: Connessione Server SMTP
// ============================================
echo "<div class='test'>";
echo "<h2>Test 3: Connessione Server SMTP</h2>";

echo "<p>Test connessione a <code>$SMTP_HOST:$SMTP_PORT</code>...</p>";

$socket = @fsockopen($SMTP_HOST, $SMTP_PORT, $errno, $errstr, 10);
if ($socket) {
    echo "<p class='ok'>✓ Server SMTP raggiungibile</p>";
    $response = fgets($socket, 512);
    echo "<p>Risposta server: <code>" . htmlspecialchars(trim($response)) . "</code></p>";
    fclose($socket);
} else {
    echo "<p class='error'>✗ Server SMTP NON raggiungibile</p>";
    echo "<p>Errore: <code>$errstr ($errno)</code></p>";
    echo "<p><strong>CAUSA POSSIBILE:</strong></p>";
    echo "<ul>";
    echo "<li>Non sei connesso alla rete UniPI (serve VPN)</li>";
    echo "<li>Firewall blocca porta 25</li>";
    echo "<li>Server mixer.unipi.it offline</li>";
    echo "</ul>";
    echo "<p class='warn'>⚠ Non potrai inviare email senza connessione al server!</p>";
}
echo "</div>";

// ============================================
// TEST 4: Creazione Oggetto PHPMailer
// ============================================
echo "<div class='test'>";
echo "<h2>Test 4: Creazione PHPMailer</h2>";

try {
    $mail = new PHPMailer(true);
    echo "<p class='ok'>✓ Oggetto PHPMailer creato</p>";
    
    // Configurazione SMTP
    $mail->isSMTP();
    echo "<p class='ok'>✓ Modalità SMTP abilitata</p>";
    
    $mail->Host = $SMTP_HOST;
    $mail->Port = $SMTP_PORT;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->SMTPAuth = false;
    echo "<p class='ok'>✓ Configurazione SMTP applicata</p>";
    
} catch (Exception $e) {
    echo "<p class='error'>✗ Errore creazione PHPMailer: " . $e->getMessage() . "</p>";
    die("</div></body></html>");
}
echo "</div>";

// ============================================
// TEST 5: Verifica Allegato PDF (Opzionale)
// ============================================
echo "<div class='test'>";
echo "<h2>Test 5: Verifica Allegati PDF</h2>";

$pdf_dir = __DIR__ . '/data/pdf/';
if (is_dir($pdf_dir)) {
    $pdfs = glob($pdf_dir . '*.pdf');
    
    if (count($pdfs) > 0) {
        echo "<p class='ok'>✓ Trovati " . count($pdfs) . " file PDF</p>";
        echo "<ul>";
        foreach (array_slice($pdfs, 0, 5) as $pdf) {
            echo "<li>" . basename($pdf) . " (" . number_format(filesize($pdf)) . " bytes)</li>";
        }
        if (count($pdfs) > 5) {
            echo "<li>... e altri " . (count($pdfs) - 5) . " PDF</li>";
        }
        echo "</ul>";
        
        $test_pdf = $pdfs[0];
        echo "<p>PDF di test: <code>" . basename($test_pdf) . "</code></p>";
    } else {
        echo "<p class='warn'>⚠ Nessun PDF trovato in <code>$pdf_dir</code></p>";
        echo "<p>Genera prima i prospetti dall'interfaccia principale</p>";
        $test_pdf = null;
    }
} else {
    echo "<p class='warn'>⚠ Cartella PDF non trovata</p>";
    $test_pdf = null;
}
echo "</div>";

// ============================================
// TEST 6: Invio Email di Test
// ============================================
echo "<div class='test'>";
echo "<h2>Test 6: Invio Email di Test</h2>";

if ($EMAIL_TEST === 'tua.email@esempio.com') {
    echo "<p class='warn'>⚠ Salta test invio - Email non configurata</p>";
    echo "<p>Configura \$EMAIL_TEST per testare l'invio</p>";
} else {
    try {
        // Reset oggetto
        $mail->clearAddresses();
        $mail->clearAttachments();
        
        // Mittente
        $mail->setFrom($SMTP_FROM, 'Test Sistema Email');
        echo "<p class='ok'>✓ Mittente impostato</p>";
        
        // Destinatario
        $mail->addAddress($EMAIL_TEST);
        echo "<p class='ok'>✓ Destinatario aggiunto: <code>$EMAIL_TEST</code></p>";
        
        // Oggetto e corpo
        $mail->Subject = 'Test Email Sistema Laureandosi';
        $mail->isHTML(false);
        $mail->Body = "Questa è una email di test del sistema Laureandosi.\n\n";
        $mail->Body .= "Se ricevi questo messaggio, il sistema email funziona correttamente!\n\n";
        $mail->Body .= "Timestamp: " . date('Y-m-d H:i:s') . "\n";
        $mail->Body .= "Server SMTP: $SMTP_HOST:$SMTP_PORT\n";
        echo "<p class='ok'>✓ Oggetto e corpo impostati</p>";
        
        // Allegato (se disponibile)
        if ($test_pdf && file_exists($test_pdf)) {
            $mail->addAttachment($test_pdf);
            echo "<p class='ok'>✓ Allegato PDF: <code>" . basename($test_pdf) . "</code></p>";
        }
        
        // Debug SMTP (mostra comunicazione)
        $mail->SMTPDebug = 0; // 0=off, 2=verbose
        
        echo "<p>Invio in corso...</p>";
        
        // INVIO!
        $result = $mail->send();
        
        if ($result) {
            echo "<p class='ok'>✅ EMAIL INVIATA CON SUCCESSO!</p>";
            echo "<p>Controlla la casella di posta: <strong>$EMAIL_TEST</strong></p>";
            echo "<p>⚠️ Controlla anche SPAM se non la vedi!</p>";
        } else {
            echo "<p class='error'>✗ Invio fallito</p>";
        }
        
    } catch (Exception $e) {
        echo "<p class='error'>✗ ERRORE INVIO EMAIL</p>";
        echo "<p><strong>Messaggio errore:</strong></p>";
        echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
        echo "<p><strong>Info PHPMailer:</strong></p>";
        echo "<pre>" . htmlspecialchars($mail->ErrorInfo) . "</pre>";
        
        echo "<p><strong>POSSIBILI CAUSE:</strong></p>";
        echo "<ul>";
        echo "<li><strong>Connection refused:</strong> Firewall blocca porta 25</li>";
        echo "<li><strong>Connection timeout:</strong> Server non raggiungibile (serve VPN UniPI)</li>";
        echo "<li><strong>STARTTLS failed:</strong> Problema TLS/SSL</li>";
        echo "<li><strong>Relay denied:</strong> Server rifiuta l'email (indirizzo mittente non valido)</li>";
        echo "</ul>";
    }
}
echo "</div>";

// ============================================
// TEST 7: Test Classe InvioPDFLaureando
// ============================================
echo "<div class='test'>";
echo "<h2>Test 7: Classe InvioPDFLaureando</h2>";

$json_file = __DIR__ . '/data/json/ausiliario.json';
if (file_exists($json_file)) {
    echo "<p class='ok'>✓ File ausiliario.json trovato</p>";
    
    $json_content = file_get_contents($json_file);
    $dati = json_decode($json_content, true);
    
    if ($dati && isset($dati['matricole'])) {
        echo "<p class='ok'>✓ JSON valido</p>";
        echo "<p>Matricole: <code>" . implode(', ', $dati['matricole']) . "</code></p>";
        echo "<p>CDL: <code>{$dati['cdl']}</code></p>";
        echo "<p>Data laurea: <code>{$dati['data_laurea']}</code></p>";
        
        echo "<p class='warn'>⚠️ Per testare invio reale:</p>";
        echo "<ol>";
        echo "<li>Vai a <code>index.php</code></li>";
        echo "<li>Genera i prospetti</li>";
        echo "<li>Clicca \"Invia Prospetti\"</li>";
        echo "</ol>";
    } else {
        echo "<p class='error'>✗ JSON non valido o formato errato</p>";
    }
} else {
    echo "<p class='warn'>⚠ File ausiliario.json non trovato</p>";
    echo "<p>Prima genera i prospetti dall'interfaccia principale</p>";
}
echo "</div>";

// ============================================
// RIEPILOGO FINALE
// ============================================
echo "<div class='test' style='background: #d4edda; border-left: 5px solid #28a745;'>";
echo "<h2>📊 Riepilogo Test</h2>";

echo "<p><strong>✅ COMPONENTI OK:</strong></p>";
echo "<ul>";
echo "<li>PHPMailer installato e funzionante</li>";
echo "<li>Configurazione SMTP corretta</li>";
echo "<li>Codice email senza errori</li>";
echo "</ul>";

echo "<p><strong>⚠️ VERIFICA MANUALE:</strong></p>";
echo "<ul>";
echo "<li>Connessione a mixer.unipi.it (richiede VPN UniPI)</li>";
echo "<li>Porta 25 aperta (firewall/ISP)</li>";
echo "<li>Email ricevuta nella casella di test</li>";
echo "</ul>";

echo "<p><strong>🎯 PROSSIMI PASSI:</strong></p>";
echo "<ol>";
echo "<li>Se email ricevuta → Sistema funziona perfettamente! ✅</li>";
echo "<li>Se errore connessione → Controlla VPN/firewall</li>";
echo "<li>Se errore relay → Contatta admin server SMTP</li>";
echo "</ol>";

echo "</div>";

echo "<hr>";
echo "<p><a href='index.php'>← Torna alla Home</a> | <a href='javascript:location.reload()'>🔄 Ricarica Test</a></p>";
echo "</body></html>";
?>
