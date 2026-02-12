<?php
require_once(__DIR__ . '/EsameLaureando.php');
require_once(__DIR__ . '/ProspettoPDFLaureando.php');
require_once(__DIR__ . '/GestioneCarrieraStudente.php');

// Classe per la gestione della carriera di un laureando
class CarrieraLaureando {

    public $_matricola;
    public $_nome;
    public $_cognome;
    public $_cdl;
    public $_email;
    public $_esami;
    private $_media;
    private $_formulaVotoLaurea;

    public function __construct($matricola, $cdl_in){
        $this->_matricola = $matricola;

        // Viene usata la classe GestioneCarrieraStudente per ottenere le informazioni
        $gcs = new GestioneCarrieraStudente();

        // Informazioni restituite dall'anagrafica
        $anagrafica_json = $gcs->restituisciAnagraficaStudente($matricola);
        $anagrafica = json_decode($anagrafica_json, true);
        $this->_nome = $anagrafica["Entries"]["Entry"]["nome"];
        $this->_cognome = $anagrafica["Entries"]["Entry"]["cognome"];
        $this->_email = $anagrafica["Entries"]["Entry"]["email_ate"];

        // Formula relativa al corso di laurea
        $con_s = file_get_contents(dirname(__DIR__) . '/data/json/formule_laurea.json');
        $configurazione_json = json_decode($con_s, true);
        $this->_formulaVotoLaurea = $configurazione_json[$this->_cdl]["formula"];

        // Carriera per poter iterare sugli esami
        $carriera_json = $gcs->restituisciCarrieraStudente($matricola);
        $carriera = json_decode($carriera_json, true);

        // Popolazione dei campi rimanenti
        $this->_cdl = $cdl_in;
        $this->_esami = array();
        for ($i = 0; $i < sizeof($carriera["Esami"]["Esame"]); $i++) {
            $esame = $this-> inserisci_esame($carriera["Esami"]["Esame"][$i]["DES"], $carriera["Esami"]["Esame"][$i]["VOTO"], $carriera["Esami"]["Esame"][$i]["PESO"], 1, 1);
            if ($esame != null && is_string($esame->_nomeEsame)) {
                array_push($this->_esami, $esame);
            }
        }

        $this->calcola_media();
    }

    // Calcola la media degli esami in carriera
    public function calcola_media()
    {
        $esami = $this->_esami;
        $somma_voto_cfu = 0;
        $somma_cfu_tot = 0;

        for ($i = 0; $i < sizeof($esami); $i++) {
            if ($esami[$i]->_faMedia == 1) {
                // Conversione del voto a intero
                $somma_voto_cfu += intval($esami[$i]->_votoEsame) * $esami[$i]->_cfu;
                $somma_cfu_tot += $esami[$i]->_cfu;
            }
        }
        $this->_media = $somma_voto_cfu / $somma_cfu_tot;
        return $this->_media;
    }

    // Restituisce la media
    public function restituisciMedia(){
        return $this->_media;
    }

    // Restituisce la somma dei crediti curricolari conseguiti
    public function creditiCurricolariConseguiti() {
        $crediti = 0;
        for ($i = 0; sizeof($this->_esami) > $i; $i++) {
            if ($this->_esami[$i]->_nomeEsame != "PROVA FINALE" && $this->_esami[$i]->_nomeEsame != "LIBERA SCELTA PER RICONOSCIMENTI") {
                $crediti += ($this->_esami[$i]->_curricolare == 1) ? $this->_esami[$i]->_cfu : 0;
            }
        }
        return $crediti;
    }

    // Restituisce la formula 
    public function restituisciFormula() {
        return $this->_formulaVotoLaurea;
    }

    // Restituisce la somma dei crediti che fanno media
    public function creditiCheFannoMedia()
    {
        $crediti = 0;

        for ($i = 0; sizeof($this->_esami) > $i; $i++) {
            $crediti += ($this->_esami[$i]->_curricolare == 1 && $this->_esami[$i]->_faMedia == 1) ? $this->_esami[$i]->_cfu : 0;
        }
        return $crediti;
    }

    // Inserisce un esame in carriera
    private function inserisci_esame($nome, $voto, $cfu, $faMedia, $curricolare)
    {
        // Alcuni esami non fanno media
        if ($nome == "LIBERA SCELTA PER RICONOSCIMENTI" || $nome == "PROVA FINALE" || $nome == "TEST DI VALUTAZIONE DI INGEGNERIA" || $nome == "PROVA DI LINGUA INGLESE B2" || $voto == 0) {
            $faMedia = 0;
        }

        // Esami con parametri malformati non vengono inseriti
        if ($nome != "TEST DI VALUTAZIONE DI INGEGNERIA" && $nome != null) {
            if ($voto == "30 e lode" || $voto == "30 e lode " || $voto == "30 e lode") {
                // -_- ci hanno messo 2 spazi
                $voto = "33";
            }

            // Rimuove gli spazi bianchi
            $voto = $voto !== null ? trim($voto) : '';

            // Creazione della classe esame da ritornare
            $esame = new EsameLaureando();
            $esame->_nomeEsame = $nome;
            $esame->_votoEsame = $voto;
            $esame->_cfu = $cfu;
            $esame->_faMedia = $faMedia;
            $esame->_curricolare = $curricolare;
            return $esame;
        } 
        else {
            return null;
        }
    }

    // Restituisce il corso di laurea
    public function get_class(){
        return $this->_cdl;
    }
    
    // Restituisce il bonus
    public function getBonus() {
        return "NO";  // Default: nessun bonus per i non informatici
    }

    // Restituisce la media degli esami informatici
    public function getMediaEsamiInformatici() {
        return $this->restituisciMedia();  // Default: media standard per i non informatici
    }
}
?>