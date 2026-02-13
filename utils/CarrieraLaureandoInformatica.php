<?php
require_once(__DIR__ . '/CarrieraLaureando.php');

// Estensione per gestire la carriera degli informatici
class CarrieraLaureandoInformatica extends CarrieraLaureando{
    private $dataImmatricolazione;
    private $dataLaurea;
    private $mediaEsamiInformatici;
    private $bonus;

    // Costruttore ereditato ed esteso
    public function __construct($matricola, $cdl_in, $dataLaurea){
        parent::__construct($matricola, $cdl_in);
        $this->dataLaurea = $dataLaurea;
        $this->bonus = "NO";

        $gcs = new GestioneCarrieraStudente();
        $carriera_json = $gcs->restituisciCarrieraStudente($matricola);
        $carriera = json_decode($carriera_json, true);

        // Il bonus viene assegnato entro i quattro anni dall'immatricolazione
        $this->dataImmatricolazione = $carriera["Esami"]["Esame"][0]["ANNO_IMM"];
        $fine_bonus = ($this->dataImmatricolazione + 4) . ("-05-01");
        if ($dataLaurea < $fine_bonus) {
            $this->bonus = "SI";
            $this->applicaBonus();
        }

        // Itera sugli esami e li setta come informatici se lo sono
        $e_info = file_get_contents(dirname(__DIR__) . '/data/json/esami_informatici.json');
        $esami_info = json_decode($e_info, true);
        for ($i = 0; $i < sizeof($this->_esami); $i++) {
            if (in_array($this->_esami[$i]->_nomeEsame, $esami_info["nomi_esami"])) {
                $this->_esami[$i]->_informatico = 1;
            }
        }
        $this->mediaEsamiInformatici = number_format($this->calcolaMediaEsamiInformatici(), 3);
        $this->calcola_media();
    }

    // Restituisce la media degli informatici
    public function getMediaEsamiInformatici()
    {
        return $this->mediaEsamiInformatici;
    }

    // Calcola la media degli esami informatici
    private function calcolaMediaEsamiInformatici()
    {
        $somma = 0;
        $numero = 0;
        for ($i = 0; $i < sizeof($this->_esami); $i++) {
            if ($this->_esami[$i]->_faMedia == 1 && $this->_esami[$i]->_informatico == 1) {
                $voto = $this->_esami[$i]->_votoEsame;

                // Calcola con il valore giusto della lode
                if (preg_replace('/\s+/', '', $voto) == "30elode") {
                    $voto = strval(30 + $this->_valore_lode);
                }
                
                // Converte voto a intero
                $somma += intval($voto);
                $numero++;
            }
        }
        return $somma / $numero;
    }

    // Restituisce "SI" oppure "NO"
    public function getBonus()
    {
        return $this->bonus;
    }

    // Applica il bonus
    private function applicaBonus(){

        $voto_min = 30 + $this->_valore_lode;
        $indice_min = 0;

        for ($i = 0; $i < sizeof($this->_esami); $i++) {
            $esame = $this->_esami[$i];
            if ($esame->_faMedia == 1 && $esame->_votoEsame <= $voto_min) {
                $voto_min = $esame->_votoEsame;
                $indice_min = $i;
            }
        }
        $this->_esami[$indice_min]->_faMedia = 0;
    }
}
?>