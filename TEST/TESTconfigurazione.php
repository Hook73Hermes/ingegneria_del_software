<?php
require_once(dirname(__DIR__) . '/utils/ModificaParametriConfigurazione.php');
class TESTconfigurazione{
    public function test(){
        $val = file_get_contents(dirname(__DIR__) . '/utils/json_files/formule_laurea.json');
        $val1 = json_decode($val,true);
        $x = file_get_contents(dirname(__DIR__) . '/utils/json_files/esami_informatici.json');
        $y = json_decode($x,true);
        $var = new ModificaParametriConfigurazione("T. Ing. Informatica",array("ELETTROTECNICA","CRITTOGRAFIA"));
        $var->modificaFormula("4*3");
        $var->modificaEsamiInformatici();
        $aux = file_get_contents(dirname(__DIR__) . '/utils/json_files/formule_laurea.json');
        $aux1 = json_decode($aux,true);
        if($val1 == $aux1)
            echo "parametri non configurati";
        else
            echo "ModificaParametriConfigurazione : TEST SUPERATI";
        $json_file = json_encode($val1,JSON_PRETTY_PRINT);
        file_put_contents(dirname(__DIR__) . '/utils/json_files/formule_laurea.json',$json_file);
        file_put_contents(dirname(__DIR__) . '/data/formule_laurea.json',$json_file);
        $json_file1 = json_encode($y,JSON_PRETTY_PRINT);
        file_put_contents(dirname(__DIR__) . '/utils/json_files/esami_informatici.json',$json_file1);
        file_put_contents(dirname(__DIR__) . '/data/esami_informatici.json',$json_file1);
    }
}
?>
