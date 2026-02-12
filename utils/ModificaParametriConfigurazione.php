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

    // Estrea la formula, la mofidica e la reinserisce
    public function modificaFormula($new_formula){
        $var = file_get_contents(dirname(__DIR__) . '/data/json/formule_laurea.json');
        $var1 = json_decode($var,true);
        $var1[$this->corso_di_laurea]["formula"] = $new_formula;
        $new_json = json_encode($var1,JSON_PRETTY_PRINT);
        file_put_contents(dirname(__DIR__) . '/data/json/formule_laurea.json',$new_json);
    }

    // Estrea gli esami, li mofidica e li reinserisce
    public function modificaEsamiInformatici(){
        $var = file_get_contents(dirname(__DIR__) . '/data/json/esami_informatici.json');
        $var1 = json_decode($var,true);
        $var1["nomi_esami"] = $this->esami_inf;
        $new_json = json_encode($var1,JSON_PRETTY_PRINT);
        file_put_contents(dirname(__DIR__) . '/data/json/esami_informatici.json',$new_json);
    }
}
?>