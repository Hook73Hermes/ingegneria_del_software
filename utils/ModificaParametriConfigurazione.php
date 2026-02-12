<?php

// Classe per modificare la configurazione
class ModificaParametriConfigurazione{
    private $corso_di_laurea;
    private $esami_inf = array();

    // Costruttore
    public function __construct($cdl_in, $informatici){
        $this->corso_di_laurea = $cdl_in;
        $this->esami_inf = $informatici;
    }

    // Modifica la formula per il calcolo del voto
    public function modificaFormula($new_formula){
        $var = file_get_contents(dirname(__DIR__) . '/data/json/formule_laurea.json');
        $var1 = json_decode($var,true);
        $var1[$this->corso_di_laurea]["formula"] = $new_formula;
        $new_json = json_encode($var1,JSON_PRETTY_PRINT);
        file_put_contents(dirname(__DIR__) . '/data/json/formule_laurea.json',$new_json);
    }

    // Modifica la lista degli esami informatici
    public function modificaEsamiInformatici(){
        $var = file_get_contents(dirname(__DIR__) . '/data/json/esami_informatici.json');
        $var1 = json_decode($var,true);
        $var1["nomi_esami"] = $this->esami_inf;
        $new_json = json_encode($var1,JSON_PRETTY_PRINT);
        file_put_contents(dirname(__DIR__) . '/data/json/esami_informatici.json',$new_json);
    }

    // Modifica il valore della lode per il corso di laurea
    public function modificaValoreLode($valore_lode){
        $var = file_get_contents(dirname(__DIR__) . '/data/json/formule_laurea.json');
        $var1 = json_decode($var,true);
        $var1[$this->corso_di_laurea]["valore_lode"] = floatval($valore_lode);
        $new_json = json_encode($var1,JSON_PRETTY_PRINT);
        file_put_contents(dirname(__DIR__) . '/data/json/formule_laurea.json',$new_json);
    }
}
?>