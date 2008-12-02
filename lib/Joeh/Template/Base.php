<?php
/*
The MIT License

Copyright (c) 2008 Rafael Souza <rafael@joeh.com.br>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

require_once 'Joeh/Template/Helper.php';

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

    public function __call($method, $arguments) {
        if(isset($this->{$method})) {
            return $this->{$method};
        }
        throw new RuntimeException('Undefined method ' . $method);
    }

    public function __get($name) {
        return $this->variables[$name];
    }

    public function __set($name, $value) {
        $this->assign($name, $value);
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

    public function setExtension($extension) {
        $this->extension = $extension;
    }

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

    public function addHelper(Joeh_Template_Helper $helper) {
        $this->assign($helper->getName(), $helper);
    }

    public function render($name, $return = false) {
          if($this->needCompile($name)) {
            $contents = $this->compile($name);
            $this->save($name, $contents);
        }

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

    private function compile($name) {
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
        return $contents;
    }

    private function read($name) {
        $path = $this->basePath . $name . '.' . $this->extension;
        if(file_exists($path) && is_readable($path)) {
            return file($this->basePath . $name . '.' . $this->extension);
        }

        throw new Exception("File {$path} cannot be loaded, check if it exists and/or have read permissions");
    }

    private function save($name, $contents) {
        $fullTemplatePath = $this->basePath . $name . '.' . $this->extension;
        $fullCompilePath = $this->compilePath . "{$name}.{$this->extension}.php";

        if(!is_dir(dirname($fullCompilePath))) {
            mkdir(dirname($fullCompilePath), 0755, true);
        }

        $fp = fopen($fullCompilePath, 'wb');
        fwrite($fp, $contents);
        fclose($fp);

        touch($fullCompilePath, filemtime($fullTemplatePath)+2);
    }

    private function needCompile($name) {
        $fullCompilePath = $this->compilePath . "{$name}.{$this->extension}.php";
        if(file_exists($fullCompilePath)) {
            $fullTemplatePath = $this->basePath . $name . '.' . $this->extension;

            $viewTime = filemtime($fullTemplatePath)+2;
            $compiledViewTime = filemtime($fullCompilePath);

            if($viewTime == $compiledViewTime) {
                return false;
            }
        }
        return true;
    }
}
?>