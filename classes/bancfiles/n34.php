<? defined('SYSPATH') or die('no direct acces allowed');

class bancfiles_n34 extends bancfiles{
  
        private $ordenante;

        private $beneficiario = array();
        
        private $esnomina;
        
//        private $dataemisio;
//        private $dataordres;
        
        private $sumatotal;
        
        private $linies;
        
        public function __construct(){
        
        
                $this->esnomina  = false;
                
                
                $this->sumatotal = 0;        
                $this->linies = 0;
        
        }
        
        public static function ordenante(){
                $classname = 'bancfiles_n34_ordenante';
                
                return new $classname;
        }    
        
        public static function beneficiario(){
                $classname = 'bancfiles_n34_beneficiario';
                
                return new $classname;
        }
        
        public function setOrdenante(bancfiles_n34_ordenante $ord){
        
                $this->ordenante = $ord;
            
                return $this;
        }

        public function setBeneficiari( bancfiles_n34_beneficiario $ben){
          
                if ($ben->importe >0){
    
                    $this->sumatotal += $ben->importe;
          
                    $this->beneficiario[] = $ben;
                }
              
                return $this;        
        
        }
        
        public function esNomina((BOOL) $nomina){
                
                $this->esnomina = $nomina;
                
                return $this;
        }
        
        public function generar(){
        
              $this->buffer = $this->ordenante->generar_cap(); $this->linies++;
              
              $this->buffer .= $this->ordenante->generar_nombre(); $this->linies++;
              
              $this->buffer .= $this->ordenante->generar_domicilio(); $this->linies++;
              
              $this->buffer .= $this->ordenante->generar_plaza(); $this->linies++;
              
              
        
              return $this;
        }
        
        
        
}