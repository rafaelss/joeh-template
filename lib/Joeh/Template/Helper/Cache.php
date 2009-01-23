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

class Joeh_Template_Helper_Cache extends Joeh_Template_Helper {

    /**
     * Returns the name of variable used in templates
     *
     * @return string
     */
    public function getName() {
        return 'cache';
    }

    /**
     * Checks if exists cache file for $key
     *
     * @param string $key
     * @param int $expirationTime in seconds
     * @return boolean
     */
    public function has($key, $expirationTime = 10) {
        $path = $this->cachePath . $key . '.php';
        if(file_exists($path)) {
            if((time() - filemtime($path)) < $expirationTime) {
                return true;
            }
        }
        return false;
    }

    /**
     * Start proccess of content store, using ob_start
     */
    public function start() {
        ob_start();
    }

    /**
     * Save contents between start and saveAndPrint methods in
     * a file named $key.php inside cache directory
     *
     * @param string $key
     * @return string
     */
    public function saveAndPrint($key) {
        $contents = ob_get_clean();
        file_put_contents($this->cachePath . $key . '.php', $contents);
        echo $contents;
    }

    /**
     * Returns the content of cache file
     *
     * @param string $key
     * @return string
     */
    public function get($key) {
        return file_get_contents($this->cachePath . $key . '.php');
    }
}
?>
