<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once(__DIR__ . '/ProspettoPDFLaureando.php');
require_once(dirname(__DIR__) . '/PHPMailer/src/Exception.php');
require_once(dirname(__DIR__) . '/PHPMailer/src/PHPMailer.php');
require_once(dirname(__DIR__) . '/PHPMailer/src/SMTP.php');

// Classe per inviare il PDF del prospetto
class InvioPDFLaureando {
    /**
     * @AttributeType int[]
     */
    private $_matricole;
    /**
     * @AssociationType ProspettoPDFLaureando
     */
    private $_cdl;
    private $_dataLaurea;

    // Costruttore
    public function __construct() {
        // Legge il file JSON unificato
        $json_content = file_get_contents(dirname(__DIR__) . '/data/json/ausiliario.json');
        $dati = json_decode($json_content, true);

        // Estrae i campi dalla struttura unificata
        $this->_matricole = $dati['matricole'];
        $this->_cdl = $dati['cdl'];
        $this->_dataLaurea = $dati['data_laurea'];
    }

    // Invia i prospetti via mail
    public function invioProspetti() {
        $emails_inviate = [];
        $emails_fallite = [];
        
        // Itera su tutte le matricola alle quali inviare la mail
        for ($j = 0; $j < sizeof($this->_matricole); $j++) {
            $prospetto = new ProspettoPDFLaureando($this->_matricole[$j], $this->_cdl, $this->_dataLaurea);
            $risultato = $this->inviaProspetto($prospetto->_carrieraLaureando);
            
            // Memorizza risultato
            if ($risultato['success']) {
                $emails_inviate[] = $risultato['email'];
            } else {
                $emails_fallite[] = [
                    'email' => $risultato['email'],
                    'errore' => $risultato['errore']
                ];
            }
        }
        
        // Costruisce messaggio finale
        $totale = sizeof($this->_matricole);
        $inviati = count($emails_inviate);
        $falliti = count($emails_fallite);
        
        if ($falliti == 0) {
            $messaggio = "Email inviate con successo a tutti i destinatari!";
        } else if ($inviati == 0) {
            $messaggio = "Errore: Nessuna email inviata. Tutti gli invii sono falliti.";
        } else {
            $messaggio = "Email inviate a $inviati destinatari su $totale. $falliti invii falliti.";
        }
        
        // Ritorna il JSON con i risultati dell'invio
        return [
            'message' => $messaggio,
            'inviati' => $emails_inviate,
            'falliti' => $emails_fallite
        ];
    }

    // Invia il rispettivo prospetto a un singolo laureato
    private function inviaProspetto($studente_carriera) {
        try {
            // Creazione di un PHPMailer con eccezioni abilitate
            $messaggio = new \PHPMailer\PHPMailer\PHPMailer(true);
            
            // Abilita SMTP
            $messaggio->isSMTP();
            $messaggio->clearAddresses();
            $messaggio->clearAttachments();
            
            // Configura server SMTP
            $messaggio->Host = 'mixer.unipi.it';
            $messaggio->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // CORREZIONE 3: Usa costante
            $messaggio->SMTPAuth = false;
            $messaggio->Port = 25;
            // $messaggio->Host = 'sandbox.smtp.mailtrap.io';
            // $messaggio->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            // $messaggio->SMTPAuth = true;  // ← Abilita auth
            // $messaggio->Username = '665f56acd98eb0';  // ← Aggiungi
            // $messaggio->Password = '0f2959a9a12f9f';  // ← Aggiungi
            // $messaggio->Port = 587;  // o 2525

            // Mittente
            $messaggio->setFrom('no-reply-laureandosi@ing.unipi.it', 'Sistema Laureandosi');
            
            // Destinatario
            $messaggio->addAddress($studente_carriera->_email);
            
            // Oggetto e corpo
            $messaggio->Subject = 'Appello di Laurea in Ingegneria - Indicatori per Voto di Laurea';
            $messaggio->isHTML(false);
            $messaggio->Body = 'Gentile laureando/laureanda,

Allego un prospetto contenente la sua carriera, gli indicatori e la formula che la commissione adopera per determinare il voto di laurea.
La prego di prendere visione dei dati relativi agli esami.

Alcune spiegazioni:
- Gli esami che non hanno un voto in trentesimi, hanno voto nominale zero, in quanto non contribuiscono al calcolo della media ma solo al numero di crediti curriculari
- Gli esami che non fanno media (pur contribuendo ai crediti curriculari) non hanno la spunta nella relativa colonna
- Il voto di tesi (T) appare nominalmente a zero in quanto viene determinato in sede di laurea

Cordiali saluti
DII';

            // Se il PDF è presente lo attacca
            $pdf_path = dirname(__DIR__) . '/data/pdf/' . $studente_carriera->_matricola . '-prospetto.pdf';
            if (file_exists($pdf_path)) {
                $messaggio->addAttachment($pdf_path);
            } else {
                throw new Exception("File PDF non trovato: " . $pdf_path);
            }

            // Invia la mail
            $messaggio->send();
            
            // Restituisce successo
            return [
                'success' => true,
                'email' => $studente_carriera->_email
            ];

        } catch (Exception $e) {
            // Restituisce errore
            return [
                'success' => false,
                'email' => $studente_carriera->_email,
                'errore' => $messaggio->ErrorInfo
            ];
        }
    }
}
?>