<?php
namespace SquareSpec;

class SpecCleanUp {
    
    public $name;
    public $action;
    public $args;
    
    public function __construct($name, $action='nullify', $args=array()) { 
        $this->name = $name;
        if ($action == 'call') {
            $this->args = $args;
        }
        $this->action = $action;        
    }
    
    public function &nullify() { 
        $this->action = 'nullify'; 
        return $this;
    }

    public function &call($method, $args=array()) { 
        $this->action = 'call';
        $this->args   = $args;        
        return $this;
    }    
    
}
?>