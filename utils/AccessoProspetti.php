<?php
/**
* @access public
* @author franc
*/
class AccessoProspetti {
    private $file = 'data/pdf/prospettoCommissione.pdf';
    
    public function fornisciAccesso() {
        return $this->file;
    }
}
?>