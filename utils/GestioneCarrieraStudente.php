<?php
class GestioneCarrieraStudente{
    public function restituisciCarrieraStudente($matricola){
        $json_carriera = file_get_contents(dirname(__DIR__) . '/data/json/' . $matricola . "_esami.json");        
        return $json_carriera;
    }
    
    public static function restituisciAnagraficaStudente($matricola){
        $json_anagrafica = file_get_contents(dirname(__DIR__) . '/data/json/' . $matricola . "_anagrafica.json");
        return $json_anagrafica;
    }
}
?>