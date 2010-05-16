<? defined('SYSPATH') or die('no direct acces allowed');

class bancfiles_n34 extends bancfiles{
  
        // definim l'ordenant
        private $ordenante;

        // definim els beneficiaris
        private $beneficiarios;
        
        // suma total dels imports
        private $sumatotal;
        
        // suma total de linies
        private $linies;
        
        // suma total de linies tipus 10 que son les que tenen els imports
        // coincideix amb els beneficiaris
        private $linies_diez;
        
        public function __construct(){
        		
        	  parent::__construct();
        
        
              $this->sumatotal = 0;        

              $this->linies = 0;

              $this->linies_diez = 0;
        
              $this->ordenante = NULL;
              $this->beneficiarios = array();
              
              $this->buffer = '';
        
        }
        // :factory que retorna un nou ordenant
        public function ordenante(){
              $classname = 'bancfiles_n34_ordenante';
                
              return new $classname;
        }    
        
        // :factory que retorna una beneficiari
        public function beneficiario(){
              $classname = 'bancfiles_n34_beneficiario';
                
              return new $classname;
        }
        
        // assigna un ordenant
        public function setOrdenante(bancfiles_n34_ordenante $ord){
        
              $this->ordenante = $ord;
            
              return $this;
        }

        public function addBeneficiario( bancfiles_n34_beneficiario $ben){
          
              if ($ben->importe >0){
    
    
                    // afegim aquest import a la suma total
                    $this->sumatotal += $ben->importe;
    
                    // cambiem el format per posarlo al fitxer
                    $importe = sprintf('%01.2f',$ben->importe);
                    $importe = str_replace('.','',$importe);          
    
                    // substituim pel nou format
                    $ben->importe = $importe;
          
                    // afegim el beneficiari a la llista
                    $this->beneficiarios[] = $ben;
              }
              
              return $this;        
        
        }
        
        public function generar(){
              
              // si no hi ha ordenant llancem una excepcio
              if (!$this->ordenante instanceof bancfiles_n34_ordenante) {
                   throw new Bancfiles_Exception('Ordenante no existente');
              }
              
              // si no hi ha beneficiaris llancem una excepcio
              if ( ($this->linies_diez = count($this->beneficiarios)) ==0) {
                   throw new Bancfiles_Exception('Beneficiarios == 0');
              }
        
              $this->buffer = $this->ordenante->generar_cap()
                              .$this->ordenante->generar_nombre()
                              .$this->ordenante->generar_domicilio()
                              .$this->ordenante->generar_plaza();
              
              $this->linies += 4;
              
              foreach ($this->beneficiarios as $beneficiario){
              
                  $this->buffer  =  $this->buffer
                                    .$beneficiario->generar_registre10($this->ordenante->nif)
                                    .$beneficiario->generar_registre11($this->ordenante->nif);
                  
                  $this->linies += 2;
                  
              } 
               
              $this->linies++;
              $this->buffer .= $this->generar_totals();
                     
              return $this;
        }
  
        private function generar_totals(){
        
              return '0856'
                     .bancfiles::add_rchar($this->ordenante->nif,10)
                     .bancfiles::space(12)
                     .bancfiles::space(3)
                     .bancfiles::zeros( $this->sumatotal, 12)
                     .bancfiles::zeros( $this->linies_diez, 8)
                     .bancfiles::zeros( $this->linies, 10)
                     .bancfiles::space(6)
                     .bancfiles::space(7);
                     
        }
        
}