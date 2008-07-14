<?php
abstract class Joeh_Template_Base {

    protected $basePath;
    
    protected $cachePath;
    
    protected $compilePath;

  #################
  ## PUBLIC METHODS
  #################

  public function render($name) {
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
    include($this->compilePath . "{$name}.php");
    $result = ob_get_clean();
    return $result;
  }

  ##################
  ## PRIVATE METHODS
  ##################

  private function read($name) {
    return file($this->basePath . $name);
  }

  private function save($name, $contents) {
    if(!is_dir($this->compilePath)) {
      mkdir($this->compilePath, 0755, true);
    }

    file_put_contents($this->compilePath . "{$name}.php", $contents);
  }
}
?>
