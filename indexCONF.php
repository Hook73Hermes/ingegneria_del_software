<!DOCTYPE html>
<html>
<head>
    <title>Configurazione</title>
    <style type="text/css">
        body {
            background-color: whitesmoke;
            padding: 20px;
        }
        form {
            max-width: 600px;
        }
        select, textarea {
            width: 100%;
            margin: 10px 0;
            padding: 8px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>CONFIGURAZIONE:</h1>
    <br>

    <form action="indexCONF.php" method="post">
        <p>Cdl:</p>
        <select name="cdl">
            <option name="cdl">T. Ing. Informatica</option>
            <option name="cdl">M. Cybersecurity</option>
            <option name="cdl">M. Ing. Elettronica</option>
            <option name="cdl">T. Ing. Biomedica</option>
            <option name="cdl">M. Ing. Biomedica, Bionics Engineering</option>
            <option name="cdl">T. Ing. Elettronica</option>
            <option name="cdl">T. Ing. delle Telecomunicazioni</option>
            <option name="cdl">M. Ing. delle Telecomunicazioni</option>
            <option name="cdl">M. Computer Engineering, Artificial Intelligence and Data Enginering</option>
            <option name="cdl">M. Ing. Robotica e della Automazione</option>
        </select>

        <br>

        <p>Formula:</p>
        <textarea name="formula"></textarea>

        <br>

        <p>Esami Informatici:</p>
        <textarea name="esami_informatici"></textarea>

        <br>

        <button type="submit">Configura</button>

        <br>

<?php
require_once(__DIR__ . '/utils/ModificaParametriConfigurazione.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST["cdl"]) || empty($_POST["cdl"])) {
        die("Errore: Corso di Laurea non fornito");
    }

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

    if (!in_array($_POST["cdl"], $cdl_whitelist, true)) {
        die("Errore: Corso di Laurea non valido. Seleziona un corso dalla lista.");
    }

    $cdl = $_POST["cdl"];

    if (isset($_POST["formula"]) && isset($_POST["esami_informatici"])) {
        $array_inf = array_map("intval", explode(",", $_POST["esami_informatici"]));
        $val = new ModificaParametriConfigurazione($cdl, $array_inf);
        $val->modificaFormula($_POST["formula"]);
        $val->modificaEsamiInformatici();
        echo "parametri configurati";
    }
}
?>
    </form>
</body>
</html>