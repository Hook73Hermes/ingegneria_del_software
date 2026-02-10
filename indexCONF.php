<!DOCTYPE html>
<head>
    <title>Conf</title>
    <style type = "text/css">

    </style>
</head>
<body style = "background-color: whitesmoke" >

<h1>CONFIGURAZIONE:</h1>
<br>
<form action = "indexCONF.php" method = "post">
    <p>Cdl:</p>
    <select name = "cdl"><!-- tutti quelli dei test  -->
        <option name = "cdl">T. Ing. Informatica</option>
        <option name = "cdl">M. Cybersecurity</option>
        <option name = "cdl">M. Ing. Elettronica</option>
        <option name = "cdl">T. Ing. Biomedica</option>
        <option name = "cdl">M. Ing. Biomedica, Bionics Engineering</option>
        <option name = "cdl">T. Ing. Elettronica</option>
        <option name = "cdl">T. Ing. delle Telecomunicazioni</option>
        <option name = "cdl">M. Ing. delle Telecomunicazioni</option>
        <option name = "cdl">M. Computer Engineering, Artificial Intelligence and Data Enginering</option>
        <option name = "cdl">M. Ing. Robotica e della Automazione"</option>
    </select>
    <br>
    <p>Formula:</p>
    <textarea name = "formula"></textarea>
    <br>
    <p>Esami Informatici:</p>
    <textarea name = "esami_informatici"></textarea>
    <br>
    <button type = "submit">
        Configura
    </button>
    <br>

<?php
require_once('C:\Users\franc\Local Sites\genera-prospetti-laurea\app\public\utils\ModificaParametriConfigurazione.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Verifica che la variabile sia settata e non sia una stringa vuota
    if (!isset($_POST["cdl"]) || empty($_POST["cdl"])) {
        die("Errore: Corso di Laurea non fornito");
    }

    // Lista dei Cdl validi
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

    // Verifica che il Cdl sia valido (approccio whitelist)
    if (!in_array($_POST["cdl"], $cdl_whitelist, true)) {
        die("Errore: Corso di Laurea non valido. Seleziona un corso dalla lista.");
    }

    // variabile locale validata
    $cdl = $_POST["cdl"];
    
    // Validazione formula es esami
    if(isset($_POST["formula"]) && isset($_POST["esami_informatici"])){
        $array_inf = array_map("intval", explode(",", $_POST["esami_informatici"]));
        $val = new ModificaParametriConfigurazione($cdl, $array_inf);
        $val->modificaFormula($_POST["formula"]);
        $val->modificaEsamiInformatici();
        echo "parametri configurati";
    }
}
?>
</form>