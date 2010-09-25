<? defined('SYSPATH') or die('no dirct allowed');

define('SLINIA', "\n");

abstract class bancfiles {
    
    protected $buffer;

    protected static $config;
    
    public function __construct(){
    	
    	if (!self::$config instanceof Kohana_Config_File){

    		self::$config = kohana::config('files-banc');    		
    	}
  	
    }
    
    public function buffer(){
  
        return (string)$this->buffer;
    }
        
    public function save($nom){
    
    	$path = isset(self::$config['path']) ? self::$config['path'] : '';
    
      	if (!is_dir($path) || !is_writable($path)){

         	throw new Bancfiles_exception("path banc-files ".$path." not exist or not writable");          
      	}
        
    	$file = $path.$nom;
    	
    	if (is_file($file)){

    		throw new Bancfiles_exception('file already exists'. $file);     	
    	}
    	
    	file_put_contents( $file, $this->buffer());
    
    	return (bool)TRUE;
    }
    
    
    
    public static function factory( $name /*, array $values = NULL*/){
                                  
    
          $classname = 'bancfiles_'.$name;
          $class =  new $classname;
          
          /*if ($values){
              $class->value($values);
          }*/
          
          return $class;
    
    }
    
    public static function zeros($num, $zeros){
    
          return str_pad($num, $zeros, "0", STR_PAD_LEFT);    
    }
    
    public static function space($num){
        
          return str_pad('',$num,' ');        
    }

    
    public static function add_char($c,$l){
        
          $c = substr($c,0,$l);
          return $c.bancfiles::space($l-strlen($c));
    }
                            
    public static function add_rchar($c,$l){
    
          return substr(bancfiles::space($l-strlen($c).$c), 0, $l);
    }
                                                            
                                                            

}