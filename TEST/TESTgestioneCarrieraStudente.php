<?php
require_once(dirname(__DIR__) . '/utils/GestioneCarrieraStudente.php');

// Verifica che la classe GestioneCarrieraStudente funzioni correttamente
class TESTgestioneCarrieraStudente{
    public function test(){
        $val = new GestioneCarrieraStudente();
        $aux = $val->restituisciAnagraficaStudente(123456);
        $aux1 = json_decode($aux,true);
        $aux2 = $aux1["Entries"]["Entry"]["nome"];

        // Verifica che il nome dello studente ricercato sia corretto
        if($aux2 == "GIUSEPPE") {
            echo "GestioneCarrieraStudente : TEST SUPERATI";
        } else {
            echo "GestioneCarrieraStudente non preleva correttamente i dati";
        }
    }
}