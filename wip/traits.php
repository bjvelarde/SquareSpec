<?php
namespace SquareSpec;

trait StaticClass {

    private function __construct() {}
    
    private function __clone() {}

}

trait Describable {

     public function getDescription() { return $this->desc; }

}
?>