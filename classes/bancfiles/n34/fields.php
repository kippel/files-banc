<? defined('SYSPATH') or die('no direct access allowed');

abstract class bancfiles_n34_fields {

      protected $fields = array();
      
      protected $data = array();
      
      abstract protected function init();
      
      final public function __construct(){
            $this->init();
      }
      
      public function __set($name, $value){
      
            if (!in_array($name, $this->fields))
                  throw new Bancfiles_Exception('Datos no validos');
      
            $this->data[$name] = $value;
            
            return $this;
      }
      
      public function __get($name){
      
          if (in_array($name,$this->fields) && array_key_exists($name,$this->data)){
              return $this->data[$name];
          }
          
          return NULL;
      
      }     
      
      public function values( array $values){
      
            foreach ($values as $field => $value){
            
                $this->$field = $value;
            }
            
            return $this;
      } 
}
