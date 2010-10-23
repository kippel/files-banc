<? defined('SYSPATH') or die('no direct acces allowed');

class bancfiles_n341 extends bancfiles{
  
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
        
        private static function parseImport( $importe){
    
              $importe = number_format($importe,2, '.','');
              $importe = str_replace('.','',$importe);          
                            
              return $importe;        
        }
        
        // :factory que retorna un nou ordenant
        public function ordenante(){

              $classname = 'bancfiles_n341_ordenante';
                
              return new $classname;
        }    
        
        // :factory que retorna una beneficiari
        public function beneficiario(){

              $classname = 'bancfiles_n341_beneficiario';
                
              return new $classname;
        }
        
        // assigna un ordenant
        public function setOrdenante(bancfiles_n341_ordenante $ord){
        
              $this->ordenante = $ord;
            
              return $this;
        }

        public function addBeneficiario( bancfiles_n341_beneficiario $ben){
          
              if ($ben->importe >0){
        
                    // afegim aquest import a la suma total
                    $this->sumatotal += $ben->importe;
    
                    
                    // substituim pel nou format
                    $ben->importe = self::parseImport($ben->importe);
          
                    // afegim el beneficiari a la llista
                    $this->beneficiarios[] = $ben;
              }
              
              return $this;        
        
        }
        
        public function generar(){
              
              // si no hi ha ordenant llancem una excepcio
              if (!$this->ordenante instanceof bancfiles_n341_ordenante) {

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
              
              foreach ($this->beneficiarios as $beneficiario){
              
                  $this->buffer  =  $this->buffer
                                    .$beneficiario->generar_registre10($this->ordenante->nif)
                                    .$beneficiario->generar_registre11($this->ordenante->nif);
                  
                  
              } 
               
              // 4 del ordenant
              // 1 totals
              // 2 per cada beneficiari
              $this->linies = 5+(count($this->beneficiarios)<<1);

              $this->buffer .= $this->generar_totals();
                     
              return $this;
        }
  
        private function generar_totals(){

              return '0962'
                     .bancfiles::add_rchar($this->ordenante->nif,9)
                     .'000'
                     .bancfiles::space(12)
                     .bancfiles::space(3)
                     .bancfiles::zeros( self::parseImport($this->sumatotal), 12)
                     .bancfiles::zeros( $this->linies_diez, 8)
                     .bancfiles::zeros( $this->linies, 10)
                     .bancfiles::space(6)
                     .bancfiles::space(7);
                     
        }
        
}