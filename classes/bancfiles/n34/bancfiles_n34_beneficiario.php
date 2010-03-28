<? defined('SYSPATH') or die('no direct access allowed');

class bancfiles_n34_beneficiario extends bancfiles_n34_field{

      public function init(){
            $this->fields = array(
                'importe',
                'control',
                'nif',
                'nombre',
                'cuenta'
            );
      
      }


}
