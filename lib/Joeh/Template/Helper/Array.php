<?php
class Joeh_Template_Helper_Array extends Joeh_Template_Helper {

    public function getName() {
        return 'array';
    }

    public function join($array, $separator = ', ', $attribute = null) {
        if($attribute) {
            $toJoin = array();
            foreach($array as $value) {
                $toJoin[] = $value->{$attribute};
            }
        }
        else {
            $toJoin = $array;
        }
        return join($separator, $toJoin);
    }

    public function create() {
        $args = func_get_args();
        foreach($args as $index => $arg) {
            $args[$index] = new ArrayObject($arg, ArrayObject::ARRAY_AS_PROPS);
        }
        return new ArrayObject($args, ArrayObject::ARRAY_AS_PROPS);
    }
}
?>