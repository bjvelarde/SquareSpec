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
     * @param callback $callback Context description
     * @return array
     */
    public static function before($callback) { return $callback();  }
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
    public static function expect($obj) { return new SpecSubject($obj); }
    
    private function __construct() {}
    
    private function __clone() {}

}
?>