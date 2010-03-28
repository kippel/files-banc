<? defined('SYSPATH') or die('no dirct allowed');

define('SLINIA', '\n\r');

abstract class bancfiles {
    
    protected $buffer;

    public static function factory( $name /*, array $values = NULL*/){
    
          $classname = 'bancfiles_'.$name;
          $class =  new $classname;
          
          /*if ($values){
              $class->value($values);
          }*/
          
          return $class;
    
    }
    
    public static function zeros($num, $zeros){
    
          return str_pad($num, $zeros, "0", STR_PAD_LEFT)
    
    }
    
    public static function space($num){
        
          return str_pad('',$num,' ');
        
    }

    
    public static function add_char($c,$l){
        
          $c = substr($c,0,$l);
          return $c . $this->espai($l-strlen($c));
    }
                            
    public static function add_rchar($c,$l){
    
          return substr(trim($this->espai($l-strlen($c)).$c), 0, $l);
    }
                                                            
                                                            

}