<!DOCTYPE html>
<html>
<head>
    <title>Configurazione</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
        }
        form {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        select, textarea {
            width: 100%;
            margin: 10px 0;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
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
        .nav {
            margin: 20px 0;
        }
        .nav a {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
        }
        .nav a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Configurazione Sistema</h1>

    <div class="nav">
        <a href="index.php">Torna alla Home</a>
        <a href="indexTEST.php">Test Suite</a>
    </div>

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

        <p>Formula:</p>
        <textarea name="formula"></textarea>

        <p>Esami Informatici:</p>
        <textarea name="esami_informatici"></textarea>

        <button type="submit">Configura</button>

        <?php
        require_once(__DIR__ . '/utils/ModificaParametriConfigurazione.php');

        // Verifica che il metodo sia POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

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

            // Variabile locale verificata
            $cdl = $_POST["cdl"];

            // Modifica effettiva dei parametri
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