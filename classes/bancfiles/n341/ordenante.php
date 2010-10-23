<? defined('SYSPATH') or die('no direct access allowed');

class bancfiles_n341_ordenante extends bancfiles_n341_fields {

   protected function init(){
        $this->fields = array(
            'nombre',
            'plaza',
            'domicilio',
            'entidad',
            'oficina',
            'control',
            'cuenta',
            'nif',
            'dataemisio',
            'dataordres',
            'sufijo'
        );
        
        $this->dataemisio = date('dmy');
        $this->dataordres = date('dmy');
        $this->sufijo = '000';
        
    }
   
    /**
     * En los seis tipos de registros de cabecera, las 28 primeras posiciones serán para
     * todos ellos iguales, conteniendo la siguiente información:
     *  Zona A: Código de registro = 03
     *  Zona B: Código de operación = 62
     *  Zona C: Identificación del ordenante: = 12
     *    C1: NIF del Ordenante: Será el NIF, CIF o NIE del ordenante. Es campo alfanumérico. = 9
     *    C2: Sufijo: cuando el ordenante desee identificar distintos tipos de pago = 3
     *    El contenido de esta zona será igual para todos los registros que
     *    contenga el fichero, sean del tipo que sean.
     *  Zona D: Libre = 12
     *    En el primer tipo de registro obligatorio del ordenante, podría figurar
     *    opcionalmente una Referencia del ordenante.
     */
    private static function previ($nif, $sufijo){
    
        return  '0362'
                .bancfiles::add_rchar($nif, 9)
                .bancfiles::zeros($sufijo, 3)
                .bancfiles::space(12);                        
    }
   
    /**
     * Zona E: Número de dato = 001
     * Zona F: F1: Fecha de envío del fichero: en formato DDMMAA
     * F2: Fecha de emisión de las órdenes: en formato DDMMAA
     * F3: Código de Entidad: Número designado a la Entidad de Crédito
     * por el Banco de España.
     * F4: Número de la Oficina donde el cliente ordenante mantiene la
     * cuenta de cargo.
     * F5: Dígitos de control del Código Cuenta Cliente (CCC) de la cuenta
     * de cargo.
     * F6: Número de la cuenta de cargo.
     * F7: Detalle del cargo:
     *  0 – Sin detalle
     *  1 – Con detalle
     * Zona G: Libre = 8
     */    
    public function generar_cap(){
        
          return self::previ($this->nif, $this->sufijo)
                 .'001'
                 .$this->dataemisio
                 .$this->dataordres
                 .bancfiles::zeros($this->entidad, 4)
                 .bancfiles::zeros($this->oficina, 4)
                 .bancfiles::zeros($this->control, 2)
                 .bancfiles::zeros($this->cuenta, 10)
                 .'0'
                 .bancfiles::space(8)
                 .SLINIA;
    }
    
    
    /**
     * Zona E: Número de dato = 002
     * Zona F: Nombre del ordenante.
     * Zona G: Libre
     */
    public function generar_nombre(){
          
          return $this->generar_caps('002',  $this->nombre);     
    }
    
    /**
     * Zona E: Número de dato = 003
     * Zona F: Domicilio del ordenante.
     * Zona G: Libre.
     */                                                                             
    public function generar_domicilio(){

          return $this->generar_caps('003', $this->domicilio);
    }
    
    /**
     * Zona E: Número de dato = 004
     * Zona F: Plaza del ordenante.
     * Zona G: Libre
     */
    public function generar_plaza(){
  
          return $this->generar_caps('004', $this->plaza);  
    }
    
    private function generar_caps($id, $value){
    
          return self::previ($this->nif, $this->sufijo)
                 .$id
                 .bancfiles::add_rchar($value, 36)
                 .bancfiles::space(5)
                 .SLINIA;
    }

}