<?php
// Avvia la sessione per gestire il login
session_start();

// Password di accesso
// Per cambiare password: echo hash('sha256', 'nuova_password');
define('PASSWORD_HASH', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918');

// Verifica se l'autenticazione dell'utente
$is_authenticated = isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;

// Gestisce il logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: indexCONF.php');
    exit();
}

// Gestisce il login
$login_error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["azione"]) && $_POST["azione"] === 'login') {
    $password_inserita = $_POST["password"] ?? '';
    
    // Verifica password
    if (hash('sha256', $password_inserita) === PASSWORD_HASH) {
        $_SESSION['authenticated'] = true;
        header('Location: indexCONF.php');
        exit();
    } else {
        $login_error = 'Password errata. Riprova.';
    }
}

// Se non autenticato, mostra form di login
if (!$is_authenticated) {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Accesso Configurazione</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1 {
            color: #333;
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
        }
        .login-box {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        input[type="password"] {
            width: 100%;
            margin: 10px 0;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #f5c6cb;
        }
        .info {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 10px;
            border-radius: 5px;
            margin-top: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h1>Accesso Configurazione</h1>
    
    <div class="login-box">
        <?php if ($login_error): ?>
            <div class="error"><?php echo htmlspecialchars($login_error); ?></div>
        <?php endif; ?>
        
        <form method="post">
            <p><strong>Inserisci la password di amministrazione:</strong></p>
            <input type="password" name="password" placeholder="Password" autofocus required />
            <button type="submit" name="azione" value="login">Accedi</button>
        </form>
        
        <div class="info">
            <strong>Nota:</strong> L'accesso a questa pagina è riservato agli amministratori.
        </div>
    </div>
</body>
</html>
<?php
    exit();
}
// Se autenticato, mostra la pagina di configurazione
?>
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
        h2 {
            color: #555;
            margin-top: 30px;
            border-bottom: 2px solid #6c757d;
            padding-bottom: 8px;
        }
        form {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        select, textarea, input {
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
            width: 100%;
        }
        button:hover {
            background-color: #0056b3;
        }
        button.danger {
            background-color: #dc3545;
        }
        button.danger:hover {
            background-color: #c82333;
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
        .nav a.logout {
            background: #dc3545;
            float: right;
        }
        .nav a.logout:hover {
            background: #c82333;
        }
        .messaggio {
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            font-weight: bold;
        }
        .messaggio.successo {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .messaggio.errore {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .warning-box {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .auth-info {
            background-color: #d4edda;
            color: #155724;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="auth-info">
        Accesso effettuato come <strong>Amministratore</strong>
    </div>

    <h1>Configurazione Sistema</h1>

    <div class="nav">
        <a href="index.php">Torna alla Home</a>
        <a href="indexTEST.php">Test Suite</a>
        <a href="indexCONF.php?logout=1" class="logout">Logout</a>
    </div>
    
    <!-- Configurazione Parametri -->
    <h2>Configura Parametri Corso di Laurea</h2>
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
        <textarea name="formula" placeholder="Es: ($M * 110) / 30"></textarea>

        <p>Esami Informatici:</p>
        <textarea name="esami_informatici" placeholder="Es: Programmazione, Algoritmi, Basi di Dati (separati da virgola)"></textarea>

        <p>Valore Lode:</p>
        <input type="number" name="valore_lode" placeholder="Es: 3, 7, 2.5, 0, ..." step="0.5" min="0" max="10" />

        <button type="submit" name="azione" value="configura">Configura</button>
    </form>

    <!-- Cancellazione Dati Appello -->
    <h2>Cancella Dati Appello</h2>
    <form action="indexCONF.php" method="post" onsubmit="return confirm('ATTENZIONE: Questa operazione cancellerà TUTTI i prospetti PDF generati e il file ausiliario.json. Sei sicuro di voler procedere?');">
        <div class="warning-box">
            <strong>⚠️ ATTENZIONE:</strong> Questa operazione eliminerà in modo permanente:
            <ul style="margin: 10px 0 0 20px;">
                <li>Tutti i file PDF generati in data/pdf/</li>
                <li>Il file ausiliario.json</li>
            </ul>
            <br>
            L'operazione è <strong>irreversibile</strong>.
        </div>

        <button type="submit" name="azione" value="cancella">Cancella Tutti i Dati dell'Appello</button>
    </form>

    <?php
    require_once(__DIR__ . '/utils/ModificaParametriConfigurazione.php');
    require_once(__DIR__ . '/utils/CancellazioneDatiAppello.php');

    // Verifica che il metodo sia POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // Determina quale azione eseguire
        $azione = isset($_POST["azione"]) ? $_POST["azione"] : '';

        // ===== AZIONE: CONFIGURA PARAMETRI =====
        if ($azione === 'configura') {
            
            // Verifica che la variabile sia settata e non sia una stringa vuota
            if (!isset($_POST["cdl"]) || empty($_POST["cdl"])) {
                echo '<div class="messaggio errore">Errore: Corso di Laurea non fornito</div>';
            } else {
                
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
                    echo '<div class="messaggio errore">Errore: Corso di Laurea non valido. Seleziona un corso dalla lista.</div>';
                } else {
                    
                    // Variabile locale verificata
                    $cdl = $_POST["cdl"];

                    // Prepara array esami informatici
                    $array_inf = array_filter(array_map("trim", explode(",", $_POST["esami_informatici"] ?? "")));

                    // Crea oggetto configurazione
                    $val = new ModificaParametriConfigurazione($cdl, $array_inf);
                    
                    $modifiche_effettuate = [];
                    
                    // Modifica formula se fornita
                    if (isset($_POST["formula"]) && !empty($_POST["formula"])) {
                        $val->modificaFormula($_POST["formula"]);
                        $modifiche_effettuate[] = "Formula aggiornata";
                    }
                    
                    // Modifica esami informatici se forniti
                    if (isset($_POST["esami_informatici"]) && !empty($_POST["esami_informatici"])) {
                        $val->modificaEsamiInformatici();
                        $modifiche_effettuate[] = "Esami informatici aggiornati";
                    }
                    
                    // Modifica valore lode se fornito
                    if (isset($_POST["valore_lode"]) && $_POST["valore_lode"] !== '') {
                        $valore_lode = floatval($_POST["valore_lode"]);
                        if ($valore_lode >= 0 && $valore_lode <= 10) {
                            $val->modificaValoreLode($valore_lode);
                            $modifiche_effettuate[] = "Valore lode aggiornato a $valore_lode";
                        } else {
                            $modifiche_effettuate[] = "ERRORE: Valore lode deve essere tra 0 e 10";
                        }
                    }
                    
                    // Stampa i messaggi di corretto funzionamento
                    if (count($modifiche_effettuate) > 0) {
                        echo '<div class="messaggio successo">';
                        echo 'Configurazione completata per <strong>' . htmlspecialchars($cdl) . '</strong>:<br>';
                        echo '<ul style="margin: 10px 0 0 20px;">';
                        foreach ($modifiche_effettuate as $modifica) {
                            echo '<li>' . htmlspecialchars($modifica) . '</li>';
                        }
                        echo '</ul>';
                        echo '</div>';
                        echo '<script> setTimeout(function() {document.querySelector(".messaggio.successo").style.display = "none";}, 5000); </script>';
                    } else {
                        echo '<div class="messaggio errore">Nessun parametro da modificare. Compila almeno un campo.</div>';
                    }
                }
            }
        }
        
        // Cancella tutti i dati dell'appello
        elseif ($azione === 'cancella') {
            
            $cancellazione = new CancellazioneDatiAppello();
            $risultato = $cancellazione->cancellaTuttiDati();
            
            if ($risultato['success']) {
                echo '<div class="messaggio successo">';
                echo '✓ ' . htmlspecialchars($risultato['message']);
                if (isset($risultato['dettagli']['pdf']['files_eliminati']) && $risultato['dettagli']['pdf']['files_eliminati'] > 0) {
                    echo '<br>File PDF eliminati: ' . $risultato['dettagli']['pdf']['files_eliminati'];
                }
                echo '</div>';
            } else {
                echo '<div class="messaggio errore">';
                echo '✗ ' . htmlspecialchars($risultato['message']);
                echo '</div>';
            }
        }
    }
    ?>
</body>
</html>