<?php
/**
 * @package SquareSpec
 * @author Benjie Velarde
 * @copyright (c) 2012, Benjie Velarde bvelarde@gmail.com
 * @lincense http://opensource.org/licenses/PHP-3.0
 */
namespace SquareSpec;
/**
 * For stubbing SpecDouble properties or methods
 */
class SpecStub {
    /**
     * @var mixed Stubbed value
     */
    private $value;
    /**
     * Constructor
     *
     * @param mixed $value Stubbed value
     * @return SpecStub
     */
    public function __construct($value=NULL) { $this->value = $value; }    
    /**
     * Set the stubbed value
     *
     * @param mixed $value Stubbed value
     * @return SpecStub
     */    
    public function &and_return($value) {
        $this->value = $value;
        return $this;
    }
    
    public function __invoke() { return $this->value;  }
}
?>