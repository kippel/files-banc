<? defined('SYSPATH') or die('no direct access allowed');

class bancfiles_n34_beneficiario extends bancfiles_n34_field{

      public function init(){
            $this->fields = array(
                'importe',
                'control',
                'nif',
                'nombre',
                'cuenta',
                'es_nomina'
            );
      
      }

      public static function generar_pre0656($nif_ordenant, $nif_beneficiari){
            return '0656'
                   .bancfiles::add_rchar($nif_ordenant, 10)
                   .bancfiles::add_rchar($this->nif, 12);
      
      }


      public function generar_registre10($nif_ordenant){
      
            $buff =  self::generar_pre0656($nif_ordenant, $this->nif)
                     .'010'
                     .bancfiles::zeros($this->importe,12)
                     .bancfiles::zeros($this->cuenta,18)
                     .'1';
            
            $buff .= ($this->es_nomina) ? '1' : '9';
            
            $buff  = $buff
                     .bancfiles::space(2)
                     .$this->control
                     .bancfiles::space(7)
                     .SLINIA;                    
            
            return $buff;
                               
      }


      public function generar_registre11($nif_ordenant){
      
           $buff = self::generar_pre0656($nif_ordenant, $this->nif)
                   .'011'
                   .bancfiles::add_char($this->nombre,36)
                   .bancfiles::space(7)
                   .SLINIA;
      
           return $buff;
      }

}
