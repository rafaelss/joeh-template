<?php
abstract class Joeh_Template_Base {

    #######################
    ## PROTECTED PROPERTIES
    #######################

    protected $basePath;

    protected $cachePath;

    protected $compilePath;

    protected $extension = 'tpl';

    #####################
    ## PRIVATE PROPERTIES
    #####################
    
    private $variables = array();

    ################
    ## MAGIC METHODS
    ################
    
    public function __get($name) {
        return $this->variables[$name];
    }

    public function __set($name, $value) {
        $this->variables[$name] = $value;
    }

    public function __isset($name) {
        return isset($this->variables[$name]);
    }

    public function __unset($name) {
        unset($this->variables[$name]);
    }

    #################
    ## PUBLIC METHODS
    #################

    public function assign($name, $value = null) {
        if(is_array($name) || $name instanceof ArrayAccess) {
            foreach($name as $name => $value) {
                $this->assign($name, $value);
            }
        }
        else {
            $this->{$name} = $value;
        }
    }

    public function render($name, $return = false) {
        $contents = null;
        $lines = $this->read($name);
        foreach($lines as $index => $line) {
            if(strpos($line, '<?') !== false) {
                if(strpos($line, '?>') === false || strpos($line, '<?php') !== false) {
                    throw new RuntimeException("Syntax error in line " . ($index+1));
                }
            }

            $line = preg_replace('/<\?[^=]/', '<?php ', $line);
            $line = preg_replace('/<\?=/', '<?php echo', $line);
            $line = preg_replace('/\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/', '\$this->\\1', $line);

            $contents .= $line;
        }

        $this->save($name, $contents);

        ob_start();
        include($this->compilePath . "{$name}.{$this->extension}.php");
        $result = ob_get_clean();

        if($return) {
            return $result;
        }

        echo $result;
    }

    ##################
    ## PRIVATE METHODS
    ##################

    private function read($name) {
        return file($this->basePath . $name . '.' . $this->extension);
    }

    private function save($name, $contents) {
        $fullCompilePath = $this->compilePath . "{$name}.{$this->extension}.php";

        if(!is_dir(dirname($fullCompilePath))) {
            mkdir(dirname($fullCompilePath), 0755, true);
        }

        file_put_contents($fullCompilePath, $contents);
    }
}
?>
