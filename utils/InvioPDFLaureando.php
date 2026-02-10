<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once(__DIR__ . '/ProspettoPDFLaureando.php');
require_once(dirname(__DIR__) . '/PHPMailer/src/Exception.php');
require_once(dirname(__DIR__) . '/PHPMailer/src/PHPMailer.php');
require_once(dirname(__DIR__) . '/PHPMailer/src/SMTP.php');

/**
 * @access public
 * @author franc
 */
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

    /**
     * @access public
     * @param int[] aMatricole
     * @ParamType aMatricole int[]
     */
    public function __construct() {
        // Legge il file JSON unificato (V024)
        $json_content = file_get_contents(dirname(__DIR__) . '/data/ausiliario.json');
        $dati = json_decode($json_content, true);

        // Estrae i campi dalla struttura unificata
        $this->_matricole = $dati['matricole'];
        $this->_cdl = $dati['cdl'];
        $this->_dataLaurea = $dati['data_laurea'];
    }

    public function invioProspetti() {
        for ($j = 0; $j < sizeof($this->_matricole); $j++) {
            $prospetto = new ProspettoPDFLaureando($this->_matricole[$j], $this->_cdl, $this->_dataLaurea);
            $this->inviaProspetto($prospetto->_carrieraLaureando);
        }
    }

    /**
     * @access public
     * @return void
     * @ReturnType void
     */
    public function inviaProspetto($studente_carriera) {
        try {
            // CORREZIONE 1: Aggiungi 'true' per abilitare eccezioni
            $messaggio = new \PHPMailer\PHPMailer\PHPMailer(true);
            
            // CORREZIONE 2: Abilita SMTP mode
            $messaggio->isSMTP();
            
            // Configurazione server SMTP
            $messaggio->Host = 'mixer.unipi.it';
            $messaggio->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // CORREZIONE 3: Usa costante
            $messaggio->SMTPAuth = false;
            $messaggio->Port = 25;

            // CORREZIONE 4: Usa setFrom() invece di From
            $messaggio->setFrom('no-reply-laureandosi@ing.unipi.it', 'Sistema Laureandosi');
            
            // CORREZIONE 5: Usa addAddress() (lowercase)
            $messaggio->addAddress($studente_carriera->_email);
            
            // Oggetto e corpo
            $messaggio->Subject = 'Appello di laurea in Ing. TEST - indicatori per voto di laurea';
            $messaggio->isHTML(false);  // CORREZIONE 6: Specifica tipo contenuto
            
            // CORREZIONE 7: Rimuovi stripslashes (non necessario) e correggi encoding
            $messaggio->Body = 'Gentile laureando/laureanda,

Allego un prospetto contenente: la sua carriera, gli indicatori e la formula che la commissione adoperera\' per determinare il voto di laurea.
La prego di prendere visione dei dati relativi agli esami.
In caso di dubbi scrivere a: ...

Alcune spiegazioni:
- gli esami che non hanno un voto in trentesimi, hanno voto nominale zero al posto di giudizio o idoneita\', in quanto non contribuiscono al calcolo della media ma solo al numero di crediti curriculari;
- gli esami che non fanno media (pur contribuendo ai crediti curriculari) non hanno la spunta nella colonna MED;
- il voto di tesi (T) appare nominalmente a zero in quanto verra\' determinato in sede di laurea, e va da 18 a 30.

Cordiali saluti
Unita\' Didattica DII';

            // CORREZIONE 8: Path corretto con forward slash
            $pdf_path = dirname(__DIR__) . '/data/pdf_generati/' . $studente_carriera->_matricola . '-prospetto.pdf';
            
            if (file_exists($pdf_path)) {
                $messaggio->addAttachment($pdf_path);
            } else {
                throw new Exception("File PDF non trovato: " . $pdf_path);
            }

            // CORREZIONE 9: send() invece di Send()
            $messaggio->send();
            echo "Email inviata correttamente a " . $studente_carriera->_email . "<br>";

        } catch (Exception $e) {
            // CORREZIONE 10: Gestione errori con eccezioni
            echo "Errore nell'invio email: {$messaggio->ErrorInfo}<br>";
            error_log("PHPMailer Error: " . $e->getMessage());
        }
    }
}
?>