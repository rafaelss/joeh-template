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

class Joeh_Template_Helper_Url extends Joeh_Template_Helper {

    private $currentUrl;

    private $baseUrl;

    public function getName() {
        return 'url';
    }

    public function base() {
        // TODO rever a forma de pegar a url
        //list($base, $path) = split('/index.php', $_SERVER['PHP_SELF']);

        if(empty($this->baseUrl)) {
            $base = str_replace($_SERVER['PATH_INFO'], '', str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']));
            $base = trim($base, '/');
            $port = $_SERVER['SERVER_PORT'];

            file_put_contents(LOG_PATH . 'server.log', print_r($_SERVER, true));
            //$this->baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $base . '/'; // . $path; // TODO add https support
            $this->baseUrl = sprintf('http://%s%s%s/%s/', $_SERVER['HTTP_HOST'], ($port != 80 ? ':' : ''), ($port != 80 ? $port : ''), $base); // . $path; // TODO add https support
        }
        return $this->baseUrl;
    }

    public function current() {
        if(empty($this->currentUrl)) {
            $this->currentUrl = 'http://' . $_SERVER['HTTP_HOST'] . str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
        }
        return $this->currentUrl;
    }

    public function referer() {
        if(!empty($_SERVER['HTTP_REFERER'])) {
            return $_SERVER['HTTP_REFERER'];
        }
        return null;
    }
}
?>