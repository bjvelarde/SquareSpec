<?php
namespace SquareSpec;

class SpecVar {
    
    public $name;
    public $value;
    
    public function __construct($name) { $this->name = $name; }
    
    public function &be($value) { 
        $this->value = $value; 
        return $this;
    }
    
}
?>