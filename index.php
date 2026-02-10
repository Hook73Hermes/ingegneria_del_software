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
    <!-- campi  -->

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

    <!-- bottoni  -->
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

<!-- AJAX Script per submit form senza reload (V023) -->
<div id="message-container" style="margin: 20px; padding: 15px; border-radius: 5px; display: none;"></div>

<script>
// ==================== AJAX IMPLEMENTATION (V023) ====================

/**
 * Intercetta il submit del form e usa AJAX invece di reload
 */
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action="generaProspetti.php"]');
    const messageContainer = document.getElementById('message-container');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            // PREVIENI il comportamento default (reload pagina)
            e.preventDefault();
            
            // Mostra loading
            showMessage('Loading...', 'info');
            disableForm(true);
            
            // Raccogli dati dal form
            const formData = new FormData(form);
            
            // FETCH API - Invia richiesta AJAX
            fetch('generaProspetti.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())  // Parse JSON response
            .then(data => {
                // SUCCESSO
                if (data.success) {
                    showMessage('✓ ' + data.message, 'success');
                    // Opzionale: resetta form dopo 3 secondi
                    setTimeout(() => {
                        form.reset();
                        hideMessage();
                    }, 3000);
                } else {
                    // ERRORE dal server
                    showMessage('✗ ' + data.message, 'error');
                }
                disableForm(false);
            })
            .catch(error => {
                // ERRORE di rete o parsing
                showMessage('✗ Errore di connessione: ' + error.message, 'error');
                disableForm(false);
            });
        });
    }
});

/**
 * Mostra un messaggio all'utente
 * @param {string} message - Testo del messaggio
 * @param {string} type - Tipo: 'success', 'error', 'info'
 */
function showMessage(message, type) {
    const container = document.getElementById('message-container');
    container.textContent = message;
    container.style.display = 'block';
    
    // Colori in base al tipo
    if (type === 'success') {
        container.style.backgroundColor = '#d4edda';
        container.style.color = '#155724';
        container.style.border = '1px solid #c3e6cb';
    } else if (type === 'error') {
        container.style.backgroundColor = '#f8d7da';
        container.style.color = '#721c24';
        container.style.border = '1px solid #f5c6cb';
    } else { // info/loading
        container.style.backgroundColor = '#d1ecf1';
        container.style.color = '#0c5460';
        container.style.border = '1px solid #bee5eb';
    }
}

/**
 * Nasconde il messaggio
 */
function hideMessage() {
    const container = document.getElementById('message-container');
    container.style.display = 'none';
}

/**
 * Disabilita/abilita il form durante il processing
 * @param {boolean} disabled - true per disabilitare
 */
function disableForm(disabled) {
    const form = document.querySelector('form[action="generaProspetti.php"]');
    const inputs = form.querySelectorAll('input, select, textarea, button');
    inputs.forEach(input => {
        input.disabled = disabled;
    });
    
    // Cambia testo bottone durante loading
    const submitBtn = form.querySelector('button[type="submit"]');
    if (disabled) {
        submitBtn.setAttribute('data-original-text', submitBtn.textContent);
        submitBtn.textContent = '⏳ Elaborazione...';
    } else {
        const originalText = submitBtn.getAttribute('data-original-text');
        if (originalText) {
            submitBtn.textContent = originalText;
        }
    }
}

// ==================== END AJAX IMPLEMENTATION ====================
</script>

</body>
