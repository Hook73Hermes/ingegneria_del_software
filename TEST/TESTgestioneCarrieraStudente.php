<?php
require_once(dirname(__DIR__) . '/utils/GestioneCarrieraStudente.php');
class TESTgestioneCarrieraStudente{
    public function test(){
        $val = new GestioneCarrieraStudente();
        $aux = $val->restituisciAnagraficaStudente(123456);
        $aux1 = json_decode($aux,true);
        $aux2 = $aux1["Entries"]["Entry"]["nome"];
        if($aux2 == "GIUSEPPE")
        echo "GestioneCarrieraStudente : TEST SUPERATI";
        else
        echo "GestioneCarrieraStudente non preleva correttamente i dati";
    }
}