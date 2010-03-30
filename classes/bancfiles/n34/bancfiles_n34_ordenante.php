<? defined('SYSPATH') or die('no direct access allowed');

class bancfiles_n34_ordenante {

    public function init(){
        $this->fields = array(
            'nombre',
            'plaza',
            'domicilio',
            'control',
            'cuenta',
            'nif',
            'dataemisio',
            'dataordres'
        );
        
        $this->dataemisio = date('dmy');
        $this->dataordre = date('dmy');
        
    }


    private static function previ($nif);
    
        return  '0356'.bancfiles::add_rchar($nif,10).$this->espai(12);
                        
    }
    
    public function generar_cap(){
        
          return self::previ($this->nif)
                 .'001'
                 .$this->dataemisio
                 .$this->dataordres
                 .bancfiles::zeros($this->cuenta,18)
                 .'0'
                 .bancfiles::space(3)
                 .$this->control
                 .bancfiles::space(7)
                 .SLINIA;
    }
    
    private static function generar_caps($id, $nif,$value){
    
          return bancfiles_n34_ordenante::previ($nif)
                 .$id
                 .bancfiles::add_rchar($values,36)
                 .bancfiles::space(7)
                 .SLINIA;
    
    }
    
    public function generar_nombre(){
          
          return bancfiles_n34_ordenante::generar_caps('001', $this->nif, $this->nombre);
     
    }
                                                                                 
    public function generar_domicilio(){

           return bancfiles_n34_ordenante::generar_caps('002', $this->nif, $this->domicilio);
    }
    
    public function generar_plaza(){
  
           return bancfiles_n34_ordenante::generar_caps('003', $this->nif, $this->plaza);
  
    }
    

}