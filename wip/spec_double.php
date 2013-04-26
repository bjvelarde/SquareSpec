<?php
/**
 * @package SquareSpec
 * @author Benjie Velarde
 * @copyright (c) 2012, Benjie Velarde bvelarde@gmail.com
 * @lincense http://opensource.org/licenses/PHP-3.0
 */
namespace SquareSpec;
/**
 * For creating mocks
 */
class SpecDouble {
    /**
     * @var string mocked object name
     */
    private $subject;
    /**
     * @var array property stubs
     */    
    private $var_stubs;
    /**
     * @var array method stubs
     */     
    private $func_stubs;
    /**
     * Constructor
     *
     * @param string $subject mocked object name
     * @return SpecDouble
     */ 
    public function __construct($subject) {
        $this->subject    = $subject;
        $this->var_stubs  = 
        $this->func_stubs = array();
    }
    /**
     * Stub a method or property
     * 
     * @param string $what To stub a method, include the trailing () on the subject e.g. $mock->stub('callme()')->and_return('Moshi moshi!');
     * @return SpecStub
     */
    public function &stub($what) {
        if (is_array($what) && (array_keys($what) !== range(0, count($what) - 1))) {
            list($what, $v) = each($what);
            list($stub, $what) = $this->stubWhat($what); 
            $this->{$stub}[$what] = new SpecStub($v);
        } else {
            list($stub, $what) = $this->stubWhat($what); 
            $this->{$stub}[$what] = new SpecStub;
        }
        return $this->{$stub}[$what];
    }
    
    public function __get($var) { return $this->var_stubs[$var](); }

    public function __call($method, $args) { return $this->func_stubs[$method]();  }    
    /**
     * Determine which to stub, property or method
     *
     * @param string $what stub alias
     * @return array
     */
    private function stubWhat($what) { 
        if (substr($what, -2) == '()') {
            return array('func_stubs', substr($what, 0, -2));
        } else {
            return array('var_stubs', $what);
        } 
    }
}
?>