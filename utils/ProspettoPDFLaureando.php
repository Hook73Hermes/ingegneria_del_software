<?php
require_once(__DIR__ . '/CarrieraLaureandoInformatica.php');
require_once(__DIR__ . '/CarrieraLaureando.php');
require_once(__DIR__ . '/fpdf.php');

// Crea il PDF del laureando
class ProspettoPDFLaureando {
    /**
    * @AttributeType CarrieraLaureando
    */
    public $_carrieraLaureando;
    /**
    * @AttributeType int
    */
    protected $_matricola;
    /**
    * @AttributeType string
    */
    protected $_dataLaurea;

    /**
    * @access public
    * @param int aMatricola
    * @param string aCdl
    * @param string aDataLaurea
    * @ParamType aMatricola int
    * @ParamType aCdl string
    * @ParamType aDataLaurea string
    */
    public function __construct($aMatricola, $aCdl, $aDataLaurea) {
        if ($aCdl != "INGEGNERIA INFORMATICA (IFO-L)" && $aCdl != "T. Ing. Informatica") {
            $this->_carrieraLaureando = new CarrieraLaureando($aMatricola, $aCdl);
        } else {
            $this->_carrieraLaureando = new CarrieraLaureandoInformatica($aMatricola, $aCdl, $aDataLaurea);
        }
        $this->_matricola = $aMatricola;
        $this->_dataLaurea = $aDataLaurea;
    }

    /**
    * @access public
    * @return void
    * @ReturnType void
    */
    public function generaProspetto() {
        // genera il prospetto in pdf e lo salva in un percorso specifico
        $font_family = "Arial";
        $tipo_informatico = 0;
        
        // Setta pagina, font, dimensioni, testo, bordo, a capo, align
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont($font_family, "", 16);
        $pdf->Cell(0, 6, $this->_carrieraLaureando->_cdl, 0, 1, 'C');
        $pdf->Cell(0, 8, 'CARRIERA E SIMULAZIONE DEL VOTO DI LAUREA', 0, 1, 'C');
        $pdf->Ln(2);

        // Anagrafica dello studente
        $pdf->SetFont($font_family, "", 9);
        $anagrafica_stringa = "Matricola: " . $this->_matricola . 
        "\nNome: " . $this->_carrieraLaureando->_nome .
        "\nCognome: " . $this->_carrieraLaureando->_cognome .
        "\nEmail: " . $this->_carrieraLaureando->_email .
        "\nData: " . $this->_dataLaurea;

        if ($this->_carrieraLaureando->get_class() == "T. Ing. Informatica") {
            $tipo_informatico = 1;
            $anagrafica_stringa .= "\nBonus: " . $this->_carrieraLaureando->getBonus();
        }

        $pdf->MultiCell(0, 6, $anagrafica_stringa, 1, 'L');
        $pdf->Ln(3);
        
        // Informazioni sugli esami
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

        // Popola la griglia con gli esami
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
        
        // Parte riassuntiva finale
        $pdf->SetFont($font_family, "", 9);
        $string = "Media Pesata (M): " . $this->_carrieraLaureando->restituisciMedia() .
        "\nCrediti che fanno media (CFU): " . $this->_carrieraLaureando->creditiCheFannoMedia() .
        "\nCrediti curriculari conseguiti: " . $this->_carrieraLaureando->creditiCurricolariConseguiti() .
        "\nFormula calcolo voto di laurea: " . $this->_carrieraLaureando->restituisciFormula();
        if ($tipo_informatico == 1) {
            $string .= "\nMedia pesata esami INF: " . $this->_carrieraLaureando->getMediaEsamiInformatici();
        }

        $pdf->MultiCell(0, 6, $string, 1, "L");
        $percorso_output = dirname(__DIR__) . '/data/pdf/';
        $nome_file = $this->_matricola . "-prospetto.pdf";
        $pdf->Output('F', $percorso_output . $nome_file);

    }
    public function getCarriera()
    {
        return $this->_carrieraLaureando;
    }
}
?>