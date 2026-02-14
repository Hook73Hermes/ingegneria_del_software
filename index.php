<!DOCTYPE html>
<html>
<head>
    <title>Genera Prospetti di Laurea</title>
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
        #message-container {
            margin: 20px 0;
            padding: 15px;
            border-radius: 8px;
            display: none;
        }
    </style>
</head>
<body>
    <h1>Genera Prospetti di Laurea</h1>

    <div class="nav">
        <a href="indexTEST.php">Test Suite</a>
        <a href="indexCONF.php">Configuratore</a>
    </div>

    <form action="generaProspetti.php" method="post" id="form-genera-prospetti">
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

        <p>Matricole:</p>
        <textarea name="matricole"></textarea>

        <p>Data Laurea:</p>
        <input type="date" name="data_laurea"/>

        <button type="submit">Genera Prospetti</button>
    </form>

    <form action="inviaProspetti.php" method="post" id="form-invio-email">
        <button type="submit">Invia Prospetti</button>
    </form>

    <form action="visualizzaProspetti.php" method="post" id="form-visualizza-prospetti">
        <button type="submit">Visualizza Prospetti</button>
    </form>

    <?php
    if (isset($_GET["aux"])) {
        $aux = $_GET["aux"];
        echo '<a href="' . htmlspecialchars($aux, ENT_QUOTES, 'UTF-8') . '" download> Apri Prospetti</a>';
    }
    ?>

    <div id="message-container"></div>

    <script>
    // Gestisce gli eventi legati al form
    document.addEventListener('DOMContentLoaded', function() {
        const formGenera = document.getElementById('form-genera-prospetti');
        const formInvia = document.getElementById('form-invio-email');
        const formVisualizza = document.getElementById('form-visualizza-prospetti');
        const messageContainer = document.getElementById('message-container');

        // Form per generare i prospetti di laurea
        if (formGenera) {
            // Quando il form viene inviato la pagina non viene refreshata (comportamento default)
            formGenera.addEventListener('submit', function(e) {
                e.preventDefault();

                showMessage('Loading...', 'info');

                const formData = new FormData(formGenera);
                disableForm(formGenera, true);

                // Richiede l'esecuzione della routine per generare i prospetti
                fetch('generaProspetti.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // In caso di successo il form viene resettato dopo 3 secondi
                    if (data.success) {
                        showMessage('Prospetti generati con successo!', 'success');
                        setTimeout(() => {
                            formGenera.reset();
                            hideMessage();
                        }, 3000);
                    } else {
                        showMessage(data.message, 'error');
                    }
                    disableForm(formGenera, false);
                })
                .catch(error => {
                    showMessage('Errore di connessione: ' + error.message, 'error');
                    disableForm(formGenera, false);
                });
            });
        }

        // Form per inviare i prospetti di laurea
        if (formInvia) {
            // Quando il form viene inviato la pagina non viene refreshata (comportamento default)
            formInvia.addEventListener('submit', function(e) {
                e.preventDefault();

                showMessage('Invio email in corso...', 'info');

                const formData = new FormData(formInvia);
                disableForm(formInvia, true);

                // Richiede l'esecuzione della routine per inviare via mail i prospetti
                fetch('inviaProspetti.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // In caso di successo il form viene resettato dopo 5 secondi
                    if (data.success) {
                        showMessage(data.message, 'success');
                        setTimeout(() => {
                            hideMessage();
                        }, 5000);
                    } else {
                        showMessage(data.message, 'error');
                    }
                    disableForm(formInvia, false);
                })
                .catch(error => {
                    showMessage('Errore di connessione: ' + error.message, 'error');
                    disableForm(formInvia, false);
                });
            });
        }

        if (formVisualizza) {
            // Quando il form viene inviato la pagina non viene refreshata (comportamento default)
            formVisualizza.addEventListener('submit', function(e) {
                e.preventDefault();

                showMessage('Apertura PDF in corso...', 'info');

                const formData = new FormData(formVisualizza);
                disableForm(formVisualizza, true);

                // Richiede l'esecuzione della routine per inviare via mail i prospetti
                fetch('visualizzaProspetti.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // In caso di successo il form viene resettato dopo 5 secondi
                    if (data.success) {
                        showMessage(data.message, 'success');

                        // Apertura del PDF in una nuova scheda
                        if (data.pdf_url) {
                            window.open(data.pdf_url, '_blank');
                        }

                        setTimeout(() => {
                            hideMessage();
                        }, 5000);
                    } else {
                        showMessage(data.message, 'error');
                    }
                    disableForm(formVisualizza, false);
                })
                .catch(error => {
                    showMessage('Errore di sistema: ' + error.message, 'error');
                    disableForm(formVisualizza, false);
                });
            });
        }
    });

    // Mostra a schermo il messaggio 
    function showMessage(message, type) {
        const container = document.getElementById('message-container');
        container.textContent = message;
        container.style.display = 'block';

        // Il messaggio può essere generico, di successo (verde) o di errore (rooso)
        if (type == 'success') {
            container.style.backgroundColor = '#d4edda';
            container.style.color = '#155724';
            container.style.border = '1px solid #c3e6cb';
        } else if (type == 'error') {
            container.style.backgroundColor = '#f8d7da';
            container.style.color = '#721c24';
            container.style.border = '1px solid #f5c6cb';
        } else {
            container.style.backgroundColor = '#d1ecf1';
            container.style.color = '#0c5460';
            container.style.border = '1px solid #bee5eb';
        }
    }

    // Nasconde il messaggio
    function hideMessage() {
        const container = document.getElementById('message-container');
        container.style.display = 'none';
    }

    // Disabilita l'utilizzo del form durante il submit
    function disableForm(form, disabled) {
        // Disabilita la scrittura negli input
        const inputs = form.querySelectorAll('input, select, textarea, button');
        inputs.forEach(input => {
            input.disabled = disabled;
        });

        // DIsabilita il pulsante di submit
        const submitBtn = form.querySelector('button[type="submit"]');
        if (disabled) {
            submitBtn.setAttribute('data-original-text', submitBtn.textContent);
            submitBtn.textContent = 'Elaborazione...';
        } else {
            const originalText = submitBtn.getAttribute('data-original-text');
            if (originalText) {
                submitBtn.textContent = originalText;
            }
        }
    }
    </script>
</body>
</html>