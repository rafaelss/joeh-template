<?php
abstract class Joeh_Template_Base {

    protected $basePath;

    protected $cachePath;

    protected $compilePath;

    protected $extension = 'tpl';

    #################
    ## PUBLIC METHODS
    #################

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
