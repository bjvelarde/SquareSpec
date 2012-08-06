<?php
/**
 * @package SquareSpec
 * @author Benjie Velarde
 * @copyright (c) 2012, Benjie Velarde bvelarde@gmail.com
 * @lincense http://opensource.org/licenses/PHP-3.0
 */
namespace SquareSpec;
/**
 * Mostly contains the wrapper functions for other Spec classes
 */
final class Spec {
    /**
     * Wraps a spec description
     *
     * @param string $desc,... List of text descriptions
     * @return SpecLevel
     */
    public static function describe() {
        $args = func_get_args();
        $desc = implode('>', $args);
        return new SpecLevel($desc);
    }
    /**
     * Wraps a spec context
     *
     * @param string $context Context description
     * @return SpecPlumb
     */
    public static function it($context) {  return new SpecPlumb($context);  }
    /**
     * For setting up the spec subjects
     *
     * @param SpecVar,... subject variables
     * @return array
     */
    public static function before() {
        $args = func_get_args();
        $subjects = array();
        foreach ($args as $arg) {
            if ($arg instanceof SpecVar) {
                $subjects[$arg->name] = $arg->value;                
            }
        }
        return $subjects;
    }
    /**
     * Wrap a spec double
     *
     * @param mixed $obj The mocked object
     * @return SpecDouble
     */    
    public static function double($obj) { return new SpecDouble($obj); }
    /**
     * Wrap a spec subject
     *
     * @param mixed $obj The test subject
     * @return SpecSubject
     */    
    public static function expect($obj) {   
        $obj = ($obj instanceof SpecSubject) ? $obj->getSubject() : $obj;    
        return new SpecSubject($obj); 
    } 
    /**
     * Wrap a variable as SpecVar
     *
     * @param string $var The variable name
     * @return SpecVar
     */     
    public static function let($var) { return new SpecVar($var); }
    
    private function __construct() {}
    
    private function __clone() {}
}
?>