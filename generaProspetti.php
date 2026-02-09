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