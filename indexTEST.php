<!DOCTYPE html>
<html>
<head>
    <title>Test Suite - Genera Prospetti</title>
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
        .test-section {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
    <h1>Test Suite - Sistema Genera Prospetti</h1>

    <div class="nav">
        <a href="index.php">Torna alla Home</a>
        <a href="indexCONF.php">Configuratore</a>
    </div>

    <div class="test-section">
        <?php
        require_once(__DIR__ . '/TEST/TESTcarrieraLaureando.php');
        $test1 = new TESTcarrieraLaureando();
        $test1->test();
        ?>
    </div>

    <div class="test-section">
        <?php
        require_once(__DIR__ . '/TEST/TESTcarrieraLaureandoInformatica.php');
        $test2 = new TESTcarrieraLaureandoInformatica();
        $test2->test();
        ?>
    </div>

    <div class="test-section">
        <?php
        require_once(__DIR__ . '/TEST/TESTesameLaureando.php');
        $test3 = new TESTesameLaureando();
        $test3->test();
        ?>
    </div>

    <div class="test-section">
        <?php
        require_once(__DIR__ . '/TEST/TESTgestioneCarrieraStudente.php');
        $test4 = new TESTgestioneCarrieraStudente();
        $test4->test();
        ?>
    </div>

    <div class="test-section">
        <?php
        require_once(__DIR__ . '/TEST/TESTaccessoProspetti.php');
        $test5 = new TESTaccessoProspetti();
        $test5->test();
        ?>
    </div>

    <div class="test-section">
        <?php
        require_once(__DIR__ . '/TEST/TESTprospettoPDFCommissione.php');
        $test6 = new TESTprospettoPDFCommissione();
        $test6->test();
        ?>
    </div>

    <div class="test-section">
        <?php
        require_once(__DIR__ . '/TEST/TESTprospettoPDFLaureando.php');
        $test7 = new TESTprospettoPDFLaureando();
        $test7->test();
        ?>
    </div>

    <div class="test-section">
        <?php
        require_once(__DIR__ . '/TEST/TESTconfigurazione.php');
        $test8 = new TESTconfigurazione();
        $test8->test();
        ?>
    </div>

    <div class="nav">
        <a href="index.php">Torna alla Home</a>
    </div>
</body>
</html>