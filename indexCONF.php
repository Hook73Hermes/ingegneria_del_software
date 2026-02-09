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

// Verifica che la richiesta sia POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    if(isset($_POST["formula"]) && isset($_POST["esami_informatici"])){
        $array_inf = array_map("intval", explode(",", $_POST["esami_informatici"]));
        $val = new ModificaParametriConfigurazione($_POST["cdl"], $array_inf);
        $val->modificaFormula($_POST["formula"]);
        $val->modificaEsamiInformatici();
        echo "parametri configurati";
    }
}

?>
</form>