<?php
require_once(realpath(dirname(__FILE__)) . '/utils/ProspettoPdfCommissione2.php');
if (isset($_POST["matricole"])) {
    // Controlla che non sia una stringa vuota prima del processing
    if (empty($_POST["matricole"])) {
        die("Errore: Nessuna matricola fornita");
    }

    // Regex che accetta solo numeri separati da virgole con opzionali spazi (whitelist)
    if (!preg_match('/^\d+(,\s*\d+)*$/', $_POST["matricole"])) {
        die("Errore: Formato matricole non valido. Usa solo numeri separati da virgola (es: 123456, 234567)");
    }

    // Verifica che sia una data valida (non 30 febbraio, etc.)
    $timestamp = strtotime($data_laurea);
    if ($timestamp === false) {
        die("Errore: Data non valida. Controlla che giorno e mese siano corretti");
    }

    // COnverte timestamp in data per confronto
    $data_laurea_obj = new DateTime($data_laurea);
    $oggi = new DateTime();
    $oggi->setTime(0, 0, 0);

    // Controlla che non sia nel passato
    if ($data_laurea_obj < $oggi) {
        die("Errore: La data di laurea non può essere nel passato");
    }

    // Controlla che non sia troppo lontana nel futuro 
    $data_massima = clone $oggi;
    $data_massima->modify('+2 years');

    if ($data_laurea_obj > $data_massima) {
        die("Errore: La data di laurea non può essere superiore a 2 anni nel futuro");
    }

    // Processing dei dati
    $matricole_array = array_map("intval", explode(",", $_POST["matricole"]));

    $prospetto = new ProspettoPdfCommissione2($matricole_array, $_POST["data_laurea"], $_POST["cdl"]);
    $prospetto->generaProspettiCommissione();
    $prospetto->generaProspettiLaureandi();
    $prospetto->popolaJSON('C:\Users\franc\Local Sites\genera-prospetti-laurea\app\public\data\ausiliario.json');
    $prospetto->popolaJSON2('C:\Users\franc\Local Sites\genera-prospetti-laurea\app\public\data\ausiliario2.json');
    $prospetto->popolaJSON3('C:\Users\franc\Local Sites\genera-prospetti-laurea\app\public\data\ausiliario3.json');
    echo "i prospetti sono stati generati";
}
?>