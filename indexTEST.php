<!DOCTYPE html>
<head>
    <title>Test</title>
    <style type = "text/css">

    </style>
</head>
<body style = "background-color: whitesmoke" >

<h1>EVENTUALI ERRORI:</h1>
<br>

<?php
require_once(__DIR__ . '/TEST/TESTconfigurazione.php');
$val = new TESTconfigurazione();
$val->test();
?>
<br>
<?php
require_once(__DIR__ . '/TEST/TESTesameLaureando.php');

$valore = new TESTesameLaureando();
$valore->test();
?>
<br>
<?php
require_once(__DIR__ . '/TEST/TESTcarrieraLaureandoInformatica.php');
$val = new TESTcarrieraLaureandoInformatica();
$val->test();
?>
<br>
<?php
require_once(__DIR__ . '/TEST/TESTcarrieraLaureando.php');
$val = new TESTcarrieraLaureando();
$val->test();
?>
<br>
<?php
require_once(__DIR__ . '/TEST/TESTaccessoProspetti.php');
$val = new TESTaccessoProspetti();
$val->test();
?>
<br>
<?php
require_once(__DIR__ . '/TEST/TESTprospettoPDFCommissione.php');
$val = new TESTprospettoPDFCommissione();
$val->test();
?>

<br>
<?php
require_once(__DIR__ . '/TEST/TESTprospettoPDFLaureando.php');
$val = new TESTprospettoPDFLaureando();
$val->test();
?>
<br>
<?php
require_once(__DIR__ . '/TEST/TESTgestioneCarrieraStudente.php');
$val = new TESTgestioneCarrieraStudente();
$val->test();
?>
<br>

