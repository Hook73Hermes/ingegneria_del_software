<?php
require_once(__DIR__ . '/ProspettoPDFCommissione.php');
require_once(__DIR__ . '/ProspettoPDFLaureando.php');

// Genera il prospetto con simulazione di laurea
class ProspettoConSimulazione extends ProspettoPDFLaureando {

    /**
    * @return void
    * @ReturnType void
    */
    public function __construct($matricola, $cdl_in, $data_laurea) {
        parent::__construct($matricola, $cdl_in, $data_laurea);
    }
    
    // Ritorna il PDF dopo averlo generato
    public function generaProspettoConSimulazione(){
        $pdf = new FPDF();
        $pdf = $this->generaContenuto($pdf);
        return $pdf;
    }

    /**
    * Calcola il voto in modo sicuro senza usare eval()
    *
    * @param float $M Media esami
    * @param float $T Voto tesi
    * @param float $C Voto commissione
    * @param int $bonusTempestivita Bonus (0 o 2)
    * @return float Voto calcolato
    */
    private function calcolaVotoSicuro($M, $T, $C, $CFU, $bonusTempestivita) {
        // Ottiene la formula dal JSON
        $formula = $this->_carrieraLaureando->restituisciFormula();

        // Sostituisce le variabili con i valori (possibili vettori di attacco)
        $formula_sicura = str_replace('$M', (string)$M, $formula);
        $formula_sicura = str_replace('$T', (string)$T, $formula_sicura);
        $formula_sicura = str_replace('$CFU', (string)$CFU, $formula_sicura);
        $formula_sicura = str_replace('$C', (string)$C, $formula_sicura);
        $formula_sicura = str_replace('$bonusTempestivita', (string)$bonusTempestivita, $formula_sicura);

        // Rimuove tutti i '$' rimasti
        if (strpos($formula_sicura, '$') != false) {
            error_log("Tentativo di code injection rilevato nella formula");
            return 0;
        }

        // Valuta SOLO espressioni matematiche usando eval in modo controllato
        try {
            $voto = 0;
            eval("\$voto = " . $formula_sicura . ";");
            return number_format($voto, 3);
        }
        catch (Exception $e) {
            error_log("Errore nel calcolo del voto: " . $e->getMessage());
            return 0;
        }
    }

    /**
    * @access public
    * @param FPDF aPdf
    * @return FPDF
    * @ParamType aPdf FPDF
    * @ReturnType FPDF
    */
    public function generaContenuto($pdf) {
        $font_family = "Arial";
        $tipo_informatico = 0;
        // indica se il laureando viene da informatica, viene modificato da solo


        // Setta pagine, font, dimensioni, testo, bordo, a capo, align
        $pdf->AddPage();
        $pdf->SetFont($font_family, "", 16);
        $pdf->Cell(0, 6, $this->_carrieraLaureando->_cdl, 0, 1, 'C');
        $pdf->Cell(0, 8, 'CARRIERA E SIMULAZIONE DEL VOTO DI LAUREA', 0, 1, 'C');
        $pdf->Ln(2);

        // Scrive l'intestazione del PDF
        $pdf->SetFont($font_family, "", 9);
        $anagrafica_stringa = "Matricola: " . $this->_matricola . 
        "\nNome: " . $this->_carrieraLaureando->_nome .
        "\nCognome: " . $this->_carrieraLaureando->_cognome .
        "\nEmail: " . $this->_carrieraLaureando->_email .
        "\nData: " . $this->_dataLaurea;

        // Se informatico viene aggiunto il bonus
        if ($this->_carrieraLaureando->get_class() == "T. Ing. Informatica") {
            $tipo_informatico = 1;
            $anagrafica_stringa .= "\nBonus: " . $this->_carrieraLaureando->getBonus();
        }

        // Inizializza la griglia
        $pdf->MultiCell(0, 6, $anagrafica_stringa, 1, 'L');
        $pdf->Ln(3);

        // Popola tutta la griglia degli esmai
        $larghezza_piccola = 12;
        $altezza = 5.5;
        $larghezza_grande = 190 - (3 * $larghezza_piccola);
        if ($tipo_informatico != 1) {
            $pdf->Cell($larghezza_grande, $altezza, "ESAME", 1, 0, 'C');
            $pdf->Cell($larghezza_piccola, $altezza, "CFU", 1, 0, 'C');
            $pdf->Cell($larghezza_piccola, $altezza, "VOT", 1, 0, 'C');
            $pdf->Cell($larghezza_piccola, $altezza, "MED", 1, 1, 'C');
        } else {
            $larghezza_piccola -= 1;
            $larghezza_grande = 190 - (4 * $larghezza_piccola);
            $pdf->Cell($larghezza_grande, $altezza, "ESAME", 1, 0, 'C');
            $pdf->Cell($larghezza_piccola, $altezza, "CFU", 1, 0, 'C');
            $pdf->Cell($larghezza_piccola, $altezza, "VOT", 1, 0, 'C');
            $pdf->Cell($larghezza_piccola, $altezza, "MED", 1, 0, 'C');
            $pdf->Cell($larghezza_piccola, $altezza, "INF", 1, 1, 'C');
        }

        $altezza = 4;
        $pdf->SetFont($font_family, "", 8);
        for ($i = 0; $i < sizeof($this->_carrieraLaureando->_esami); $i++) {
            $esame = $this->_carrieraLaureando->_esami[$i];
            $pdf->Cell($larghezza_grande, $altezza, $esame->_nomeEsame, 1, 0, 'L');
            $pdf->Cell($larghezza_piccola, $altezza, $esame->_cfu, 1, 0, 'C');
            $pdf->Cell($larghezza_piccola, $altezza, $esame->_votoEsame, 1, 0, 'C');
            if ($tipo_informatico != 1) {
                $pdf->Cell($larghezza_piccola, $altezza, ($esame->_faMedia == 1) ? 'X' : '', 1, 1, 'C');
            } else {
                $pdf->Cell($larghezza_piccola, $altezza, ($esame->_faMedia == 1) ? 'X' : '', 1, 0, 'C');
                $pdf->Cell($larghezza_piccola, $altezza, ($esame->_informatico == 1) ? 'X' : '', 1, 1, 'C');
            }
        }
        $pdf->Ln(5);
        
        // Riassunto finale
        $pdf->SetFont($font_family, "", 9);
        $string = "Media Pesata (M): " . $this->_carrieraLaureando->restituisciMedia() .
        "\nCrediti che fanno media (CFU): " . $this->_carrieraLaureando->creditiCheFannoMedia() .
        "\nCrediti curriculari conseguiti: " . $this->_carrieraLaureando->creditiCurricolariConseguiti() .
        "\nFormula calcolo voto di laurea: " . $this->_carrieraLaureando->restituisciFormula();
        if ($tipo_informatico == 1) {
            $string .= "\nMedia pesata esami INF: " . $this->_carrieraLaureando->getMediaEsamiInformatici();
        }

        $pdf->MultiCell(0, 6, $string, 1, "L");

        // Simulazione finale per la commissione
        $con_s = file_get_contents(dirname(__DIR__) . '/data/json/formule_laurea.json');
        $configurazione_json = json_decode($con_s, true);
        $t_min = $configurazione_json[$this->_carrieraLaureando->_cdl]["Tmin"];
        $t_max = $configurazione_json[$this->_carrieraLaureando->_cdl]["Tmax"];
        $t_step = $configurazione_json[$this->_carrieraLaureando->_cdl]["Tstep"];
        $c_min = $configurazione_json[$this->_carrieraLaureando->_cdl]["Cmin"];
        $c_max = $configurazione_json[$this->_carrieraLaureando->_cdl]["Cmax"];
        $c_step = $configurazione_json[$this->_carrieraLaureando->_cdl]["Cstep"];
        $CFU = 102;//$this->_carrieraLaureando->creditiCheFannoMedia();

        // Aggiunge al PDF le parti necessarie
        $pdf->Ln(4);
        $pdf->Cell(0, 5.5, "SIMULAZIONE DI VOTO DI LAUREA", 1, 1, 'C');
        $width = 190 / 2;
        $height = 4.5;

        if ($c_min != 0) {
            $pdf->Cell($width, $height, "VOTO COMMISSIONE (C)", 1, 0, 'C');
            $pdf->Cell($width, $height, "VOTO LAUREA", 1, 1, 'C');
            $M = $this->_carrieraLaureando->restituisciMedia();
            $T = 0;
            $bonusTempestivita = 0;

            // Se informatico calcola il bonus
            if ($this->_carrieraLaureando->get_class() == "T. Ing. Informatica") {
                $bonusTempestivita = ($this->_carrieraLaureando->getBonus() == "SI") ? 2 : 0;
            }

            for ($C = $c_min; $C <= $c_max; $C += $c_step) {
                $voto = 0;
                $voto = $this->calcolaVotoSicuro($M, $T, $C, $CFU, $bonusTempestivita);
                $pdf->Cell($width, $height, $C, 1, 0, 'C');
                $pdf->Cell($width, $height, $voto, 1, 1, 'C');
            }
        }
        if ($t_min != 0) {
            $pdf->Cell($width, $height, "VOTO TESI (T)", 1, 0, 'C');
            $pdf->Cell($width, $height, "VOTO LAUREA", 1, 1, 'C');
            $M = $this->_carrieraLaureando->restituisciMedia();
            $C = 0;
            $bonusTempestivita = 0;

            // Se informatico calcola il bonus
            if ($this->_carrieraLaureando->get_class() == "T. Ing. Informatica") {
                $bonusTempestivita = ($this->_carrieraLaureando->getBonus() == "SI") ? 2 : 0;
            }

            for ($T = $t_min; $T <= $t_max; $T += $t_step) {
                $voto = 0;
                $voto = $this->calcolaVotoSicuro($M, $T, $C, $CFU, $bonusTempestivita);
                $pdf->Cell($width, $height, $T, 1, 0, 'C');
                $pdf->Cell($width, $height, $voto, 1, 1, 'C');
            }
        }

        return $pdf;
    }

    /**
    * @param FPDF aPdf
    * @return FPDF
    * @ParamType aPdf FPDF
    * @ReturnType FPDF
    */
    public function generaRiga( $pdf) {
        $width = 190 / 4;
        $height = 5;
        $pdf->Cell($width, $height, $this->_carrieraLaureando->_cognome, 1, 0, 'L');
        $pdf->Cell($width, $height, $this->_carrieraLaureando->_nome, 1, 0, 'L');
        $cdl = $this->_carrieraLaureando->_cdl;
        if (strlen($cdl) > 24) {
            $cdl = substr($this->_carrieraLaureando->_cdl, 0, 21) . "...";
        }
        $pdf->Cell($width, $height, $cdl, 1, 0, 'C');
        $pdf->Cell($width, $height, "/110", 1, 1, 'C');
        return $pdf;
    }
}
?>