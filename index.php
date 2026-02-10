<!DOCTYPE html>
<html>
<head>
    <title>Genera Prospetti di Laurea</title>
    <style type="text/css">
        body {
            text-align: center;
            background-color: whitesmoke;
            font-size: larger;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        button {

            color: white;
            background-color: red;
            padding: 0.5em;
            margin: 0.5em;
            border-radius: 5px;
        }
        select, textarea, input {
            margin: 0.5em;
        }
    </style>
</head>
<body >
<h1> genera prospetti di laurea </h1>


<form action = "generaProspetti.php" method = "post">

    <h1> Laureandosi 2 - Gestione Lauree </h1>

    <p>Cdl:</p>
    <select name = "cdl">
        <option name = "cdl">T. Ing. Informatica</option>
        <option name = "cdl">M. Cybersecurity</option>
        <option name = "cdl">M. Ing. Elettronica</option>
        <option name = "cdl">T. Ing. Biomedica</option>
        <option name = "cdl">M. Ing. Biomedica, Bionics Engineering</option>
        <option name = "cdl">T. Ing. Elettronica</option>
        <option name = "cdl">T. Ing. delle Telecomunicazioni</option>
        <option name = "cdl">M. Ing. delle Telecomunicazioni</option>
        <option name = "cdl">M. Computer Engineering, Artificial Intelligence and Data Enginering</option>
        <option name = "cdl">M. Ing. Robotica e della Automazione</option>
    </select>

    <br>

    <p>Matricole:</p>
    <textarea name = "matricole"></textarea>

    <br>

    <p>Data Laurea:</p>
    <input type = "date" name = "data_laurea"/>

    <br>
    <br>
    <br>

    <button type = "submit">
        Crea Prospetti
    </button>



</form>
<form action = "inviaProspetti.php" method = "post">

<br>
<br>


    <button type = "submit"> Invia Prospetti </button>


</form>

<br>
<?php
require_once(__DIR__ . '/utils/AccessoProspetti.php');
$accesso = new AccessoProspetti;
$aux = $accesso->fornisciAccesso();
echo '<a href="' . htmlspecialchars($aux, ENT_QUOTES, 'UTF-8') . '" download> Apri Prospetti</a>';
?>
<br>
<br>
<br>
<a href="indexTEST.php">Vai alla pagina 2</a>

<a href = "indexCONF.php"> Vai alla pagina del configuratore</a>
</body>
