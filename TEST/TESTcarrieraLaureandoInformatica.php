<?php
require_once(dirname(__DIR__) . '/utils/CarrieraLaureandoInformatica.php');

// Verifica che la classe CarrieraLaureandoInformatica funzioni correttamente
class TESTcarrieraLaureandoInformatica
{
    // Esegue i test
    public function test()
    {
        $this->test_costruttore();
        $this->test_media();
        echo "CarrieraLaureandoInformatica : TEST SUPERATI";
    }

    // Test del costruttore
    public function test_costruttore()
    {
        // Verifica che il bonus venga concesso solo enro 4 anni dalla laurea
        $val = new CarrieraLaureandoInformatica(123456, "T. Ing. Informatica", "2024-01-05");
        $aspettato = "NO";
        if ($aspettato != $val->getBonus()) {
            echo "aspettato" . $aspettato . "rivevuto" . $val->getBonus();
        }

        $val1 = new CarrieraLaureandoInformatica(123456, "T. Ing. Informatica", "2018-01-05");
        $aspettato1 = "SI";
        if($aspettato1 != $val1->getBonus()){
            echo "aspettato" . $aspettato1 . "rivevuto" . $val1->getBonus();
        }
    }

    // Test della media informatica
    public function test_media(){
        $val = new CarrieraLaureandoInformatica(123456, "T. Ing. Informatica", "2024-01-05");
        $val1 = new CarrieraLaureandoInformatica(123456, "T. Ing. Informatica", "2018-01-05");

        // Verifica che la media sia diversa in funzione del bonus
        if($val->restituisciMedia() == $val1->restituisciMedia()) {
            echo "il bonus non viene applicato correttamente";
        }
    }
}
?>