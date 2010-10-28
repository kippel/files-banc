<? defined('SYSPATH') or die('no direct access allowed');

class bancfiles_n341_beneficiario extends bancfiles_n341_fields{

      public function init(){
            $this->fields = array(
                'sufijo',
                'importe',
                'entidad',
                'oficina',
                'control',
                'cuenta',
                'nif',
                'nombre',
                'es_nomina'
            );
            $this->sufijo = '000';
      }

      public function __set($id, $value){
           
           if ($id == 'nif' && strlen($value)>9){

                 $value = substr($value, 0, 1).substr($value, 2);
           }
           parent::__set($id, $value);
                                                 
      }
      
      
      /**
       * Zona A: Código de registro = 06
       * Zona B: Código de operación: 56 - Transferencia nacional ó 57 – Cheque Nómina/ bancario.
       * Zona C: Identificación del ordenante: Igual contenido que lleva este mismo campo
       *  en los registros de Cabecera de Ordenante.
       * Zona D: Referencia del beneficiario: Código de identificación fijado por el ordenante,
       *  distinto para cada beneficiario, que será el mismo para todas sus nóminas,
       *  pensiones o pagos sucesivos. Puede ser el N.I.F., número de matrícula,
       *  número de la Seguridad Social, etc.
       */
      protected function generar_pre0656($nif_ordenant, $sufijo){
      
            return '0656'
                   .bancfiles::add_rchar($nif_ordenant, 9)
                   .bancfiles::zeros($sufijo, 3)
                   .bancfiles::add_rchar($this->nif, 12);      
      }
      
      /**
       * Zona E: Número de dato = 010
       * Zona F: Datos del abono:
       *  F1: Importe en euros con dos posiciones decimales sin reflejar la coma.
       *  F2: Número de la Entidad de Crédito del beneficiario. Será el
       *   número asignado por el Banco de España a dicha Entidad.
       *   Siempre que se trate de transferencias, es obligatorio
       *   consignarlo
       *  F3: Número de la Oficina de la Entidad de Crédito del beneficiario.
       *   Siempre que se trate de transferencias, es obligatorio consignarlo
       *  F4: Dígitos de control del Código Cuenta Cliente (C.C.C.) de la
       *   cuenta de abono de la transferencia. Siempre que se trate de
       *   transferencias, es obligatorio consignarlo
       *  F5: Número de cuenta donde se ha de efectuar el abono. Siempre
       *   que se trate de transferencias, es obligatorio consignarlo.
       *  F6: Clave de Gastos: Código que indica por cuenta de quien deben
       *   ser los gastos de la operación según los siguientes códigos:
       *    1: Gastos por cuenta del ordenante.
       *    2: Gastos por cuenta del beneficiario.
       *  F7: Concepto de la orden:
       *   1 = Nómina.
       *   8 = Pensión.
       *   9 = Otros conceptos.
       *  F8: Instrucción de orden de abono directo por el CCC:
       *   1 = Sí
       *   2 = No.
       *  Siempre que se trate de transferencias, es obligatorio
       *  consignarlo
       *  Zona G: Libre = 6
       */
      public function generar_registre10($nif_ordenant, $sufijo){
      
            $buff =  $this->generar_pre0656($nif_ordenant, $sufijo)
                     .'010'
                     .bancfiles::zeros($this->importe, 12)
                     .bancfiles::zeros($this->entidad, 4)
                     .bancfiles::zeros($this->oficina, 4)
                     .bancfiles::zeros($this->control, 2)
                     .bancfiles::zeros($this->cuenta, 10)
                     .'1';
            
            $buff .= ($this->es_nomina) ? '1' : '9';
            
            $buff .= '1'
                     .bancfiles::space(6)
                     .SLINIA;                    
            
            return $buff;                               
      }

      /**
       * Zona E: Número de dato = 011
       * Zona F: Nombre del beneficiario. Obligatorio
       * Zona G: Libre
       */
      public function generar_registre11($nif_ordenant, $sufijo){
      
           $buff = $this->generar_pre0656($nif_ordenant, $sufijo)
                   .'011'
                   .bancfiles::add_char($this->nombre, 36)
                   .bancfiles::space(5)
                   .SLINIA;
      
           return $buff;
      }

}
