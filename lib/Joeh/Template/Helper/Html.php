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
require_once 'Joeh/Template/Helper/Url.php';
require_once 'Joeh/Template/Tag.php';

class Joeh_Template_Helper_Html extends Joeh_Template_Helper {

    public function getName() {
        return "html";
    }

    public function scriptTag($url) {
        $script = new Joeh_Template_Tag("script", "");
        $script->type = "text/javascript";
        $script->src = $this->javascriptPath($url);
        return $script->toHTML() . PHP_EOL;
    }

    public function javascriptPath($file) {
        $path = "assets/js/" . $file;
        $time = @filemtime(ROOT_PATH . $path);
        return $this->url->base() . $path . '?' . $time;
    }

    public function buttonTag($value, array $options = array()) {
        $options["type"] = "button";

        $button = new Joeh_Template_Tag("input");
        $button->value = $value;
        $button->addAttributesFromArray($options);
        return $button->toHTML() . PHP_EOL;
    }

    public function contentType($type, $encoding = 'utf-8') {
        $meta = new Joeh_Template_Tag('meta');
        $meta->{"http-equiv"} = 'Content-Type';
        $meta->content = $type . '; charset=' . $encoding;
        return $meta->toHTML() . PHP_EOL;
    }

    public function doctype($type) {
        switch($type) {
            case "xhtml1-transitional":
                return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . PHP_EOL;
            case "xhtml1-strict":
                return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . PHP_EOL;
        }
    }

    /**
     * <code>
     * $html->linkTo('New User', 'users/create') # <a href='http://.../users/create'>New User</a>
     * $html->linkTo('List User', 'users'))       # <a href='http://.../users'>List User</a>
     * $html->linkTo('New User', array('controller' => 'users', 'action' => 'create')) # <a href='http://.../users/create'>New User</a>
     * $html->linkTo('List Users', array('controller' => 'users')) # <a href='http://.../users'>New User</a>
     * $html->linkTo('Home', array('action' => 'index')) # <a href='http://.../'>Home</a>
     * $html->linkTo('Home', array('controller' => 'index')) # <a href='http://.../'>Home</a>
     * $html->linkTo('Home', array('controller' => 'index')) # <a href='http://.../'>Home</a>
     * $html->linkTo('Home', null, array('id' => 'home_link')) # <a href='http://.../' id='html_link'>Home</a>
     * </code>
     */
    public function linkTo($title, $options = array(), array $htmlOptions = array()) {
        $href = $this->urlFor($options);

        unset($options['controller']);
        unset($options['action']);
        unset($options['id']);

        foreach($options as $key => $value) {
            if(is_object($value) && method_exists($value, 'toParam')) {
                $value = $value->toParam();
            }
            $href .= $key . '/' . $value . '/';
        }

        return $this->anchor($title, $href, $htmlOptions);
    }

    public function anchor($title, $url, array $htmlOptions = array()) {
        $link = new Joeh_Template_Tag('a', $title);
        $link->addAttributesFromArray($htmlOptions);
        $link->href = $url;

        if(!empty($htmlOptions['confirm'])) {
            $link->onclick = "return confirm('{$htmlOptions['confirm']}');";
        }

        return $link->toHTML() . PHP_EOL;
    }

    public function form($action = null, array $htmlOptions = array(), $multipart = false, $method = 'post') {
        $html = "<form action=\"" . ROOT_URL . "{$action}\" method=\"{$method}\"";

        if($multipart) {
            $html .= " enctype=\"multipart/form-data\"";
        }

        foreach($htmlOptions as $key => $value) {
            $html .= " {$key}=\"{$value}\"";
        }

        return $html . '>' . PHP_EOL;
    }

    public function stylesheetLink($file, $media = 'screen') {
        $tag = new Joeh_Template_Tag('link');
        $tag->href = $this->stylesheetPath($file);
        $tag->media = $media;
        $tag->rel = 'stylesheet';
        $tag->type = 'text/css';
        return $tag->toHTML() . PHP_EOL;
    }

    public function stylesheetPath($file) {
        $path = "assets/css/" . $file;
        $time = @filemtime(ROOT_PATH . $path);
        return $this->url->base() . $path . '?' . $time;
    }

    public function image($src, array $htmlOptions = array()) {
        if(!isset($htmlOptions['alt'])) {
            $htmlOptions['alt'] = $src;
        }

        $tag = new Joeh_Template_Tag('img');
        $tag->src = $this->imagePath($src);
        $tag->addAttributesFromArray($htmlOptions);
        return $tag->toHTML();
    }

    public function imagePath($src) {
        if($src[0] == '/') {
            $path = $src;
        }
        else {
            $path = "assets/imgs/" . $src;
        }

        $time = @filemtime(ROOT_PATH . $path);
        return $this->url->base() . $path . '?' . $time;
    }

    public function swfPath($src) {
        if($src[0] == '/') {
            $path = $src;
        }
        else {
            $path = "assets/swf/" . $src;
        }

        $time = @filemtime(ROOT_PATH . $path);
        return $this->url->base() . $path . '?' . $time;
    }

    public function escape() {
        $texts = func_get_args();
        $return = '';
        foreach($texts as $text) {
            $return .= htmlentities($text, ENT_QUOTES, 'UTF-8');
        }
        return $return;
    }

    public function paginate($collection) {
        $currentPage = $this->request->page;
        $foundRows = $collection->foundRows();
        $offset = (empty($this->request->offset) ? count($collection) : $this->request->offset);

        if(empty($currentPage)) {
            $currentPage = 1;
        }

        $html = sprintf('<div class="pagination">
            <div class="limit">
                Registros por página  #
                <select name="limit" id="limit" class="inputbox" size="1" onchange="%s">
                    <option value="5"%s>5</option>
                    <option value="10"%s>10</option>
                    <option value="15"%s>15</option>
                    <option value="20"%s>20</option>
                    <option value="25"%s>25</option>
                    <option value="30"%s>30</option>
                    <option value="50"%s>50</option>
                    <option value="100"%s>100</option>
                    <option value="%s"%s>Todos</option>
                </select>
            </div>
            <div><ul><li></li>',
            "location.href='{$this->url->current()}?offset=' + \$(this).val();",
            $offset == 5 ? ' selected="selected"' : null,
            $offset == 10 ? ' selected="selected"' : null,
            $offset == 15 ? ' selected="selected"' : null,
            $offset == 20 ? ' selected="selected"' : null,
            $offset == 25 ? ' selected="selected"' : null,
            $offset == 30 ? ' selected="selected"' : null,
            $offset == 50 ? ' selected="selected"' : null,
            $offset == 100 ? ' selected="selected"' : null,
            $foundRows,
            $offset == $foundRows ? ' selected="selected"' : null
        );

        if($currentPage > 1) {
            $prevPage = $currentPage - 1;
            $html .= "<li><a href=\"{$this->url->current()}?page=1&offset={$offset}\">primeira</a></li>";
            $html .= "<li><a href=\"{$this->url->current()}?page={$prevPage}&offset={$offset}\">anterior</a></li>";
        }
        else {
            $html .= "<li>primeira</li>";
            $html .= "<li>anterior</li>";
        }

        $totalPages = $collection->totalPages();
        for($i = 1; $i <= $totalPages; $i++) {
            if($i == $currentPage) {
                $html .= "<li class=\"active\">{$i}</li>";
            }
            else {
                $html .= "<li><a href=\"{$this->url->current()}?page={$i}&offset={$offset}\">{$i}</a></li>";
            }
        }

        if($currentPage < $totalPages) {
            $nextPage = $currentPage + 1;
            $html .= "<a href=\"{$this->url->current()}?page={$nextPage}&offset={$offset}\">próxima</a>";
            $html .= "<li><a href=\"{$this->url->current()}?page={$totalPages}&offset={$offset}\">última</a></li>";
        }
        else {
            $html .= "<li>próxima</li>";
            $html .= "<li>última</li>";
        }

        $html .= '</ul></div></div>';
        return $html;
    }
}
?>