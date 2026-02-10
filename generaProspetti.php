<?php
require_once(realpath(dirname(__FILE__)) . '/utils/ProspettoPdfCommissione2.php');

// Verifica che il metodo utilizzato sia POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["matricole"])) {
        // Verifica che la variabile sia settata e non sia una stringa vuota
        if (!isset($_POST["matricole"]) || empty($_POST["matricole"])) {
            die("Errore: Nessuna matricola fornita");
        }

        // Espressione regola che matcha soltanto numeri separati da virgole (con eventuali spazi)
        if (!preg_match('/^\d+(,\s*\d+)*$/', $_POST["matricole"])) {
            die("Errore: Formato matricole non valido. Usa solo numeri separati da virgola (es: 123456, 234567)");
        }

        // Variabile locale validata
        $matricole_array = array_map("intval", explode(",", $_POST["matricole"]));

        // Verifica che la variabile sia settata e non sia una stringa vuota
        if (!isset($_POST["data_laurea"]) || empty($_POST["data_laurea"])) {
            die("Errore: Data di laurea non fornita");
        }

        // Variabile locale da validare
        $data_laurea = $_POST["data_laurea"];

        // Espressione regolare che controlla la correttezza della forma
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data_laurea)) {
            die("Errore: Formato data non valido. Usa il formato AAAA-MM-GG (es: 2024-07-15)");
        }

        // Verifica che la data esista
        $timestamp = strtotime($data_laurea);
        if ($timestamp === false) {
            die("Errore: Data non valida. Controlla che giorno e mese siano corretti");
        }

        // Variabili locali con data attuale e data tra due anni esatti da utilizzare per confronto
        $data_laurea_obj = new DateTime($data_laurea);
        $oggi = new DateTime();
        $oggi->setTime(0, 0, 0);
        $data_massima = clone $oggi;
        $data_massima->modify('+2 years');

        // Verifica che la data sia compresa tra oggi e due anni successivi
        if ($data_laurea_obj < $oggi) {
            die("Errore: La data di laurea non può essere nel passato");
        }
        else if ($data_laurea_obj > $data_massima) {
            die("Errore: La data di laurea non può essere superiore a 2 anni nel futuro");
        }

        // Verifica che la variabile sia settata e non sia una stringa vuota
        if (!isset($_POST["cdl"]) || empty($_POST["cdl"])) {
            die("Errore: Corso di Laurea non fornito");
        }

        // Lista di tutti i Cdl consentiti
        $cdl_whitelist = [
            'T. Ing. Informatica',
            'M. Cybersecurity',
            'M. Ing. Elettronica',
            'T. Ing. Biomedica',
            'M. Ing. Biomedica, Bionics Engineering',
            'T. Ing. Elettronica',
            'T. Ing. delle Telecomunicazioni',
            'M. Ing. delle Telecomunicazioni',
            'M. Computer Engineering, Artificial Intelligence and Data Enginering',
            'M. Ing. Robotica e della Automazione'
        ];

        // Verifica che il Cdl sia nella lista consentita (approccio whitelist)
        if (!in_array($_POST["cdl"], $cdl_whitelist, true)) {
            die("Errore: Corso di Laurea non valido. Seleziona un corso dalla lista.");
        }

        // Variabile locale validata
        $cdl = $_POST["cdl"];

        // Generazione dei prospetti di laurea
        $prospetto = new ProspettoPdfCommissione2($matricole_array, $data_laurea, $cdl);
        $prospetto->generaProspettiCommissione();
        $prospetto->generaProspettiLaureandi();
        $prospetto->popolaJSON(__DIR__ . '/data/ausiliario.json');
        $prospetto->popolaJSON2(__DIR__ . '/data/ausiliario2.json');
        $prospetto->popolaJSON3(__DIR__ . '/data/ausiliario3.json');
        echo "i prospetti sono stati generati";
    }
}
?>