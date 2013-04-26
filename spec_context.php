<?php
/**
 * @package SquareSpec
 * @author Benjie Velarde
 * @copyright (c) 2012, Benjie Velarde bvelarde@gmail.com
 * @lincense http://opensource.org/licenses/PHP-3.0
 */
namespace SquareSpec;
use \ReflectionFunction;
/**
 * The spec context 
 * --- proove perpendiculars :) , if you don't get it, nevermind ;)
 */
class SpecContext implements Testable {
    /**
     * @var string The description text
     */
    private $desc;
    /**
     * @var callback The test callback
     */    
    private $callback;
    /**
     * @var array The test subjects
     */       
    private $subjects;
    /**
     * @var array To track callback argument requirements
     */       
    private $args;
    /**
     * Constructor
     *
     * @param string $desc The context description
     * @return SpecContext
     */ 
    public function __construct($desc) {
        $this->desc     = $desc;
        $this->callback = '';
        $this->subjects =
        $this->args     = array();
    }
    /**
     * The method to receive the callback
     *
     * @param callback $callback 
     * @return SpecContext
     */
    public function &do($callback) {
        $this->callback = $callback;
        $r      = new ReflectionFunction($callback);
        $args   = $r->getParameters();
        $params = array();
        foreach ($args as $a) {
            $this->args[] = $a->name;
        }
        return $this;
    }
    /**
     * Use to receive subjects from container descriptions
     *
     * @param array
     */
    public function addSubjects(array $subjects) {
        $subjs = array();
        foreach ($subjects as $k => $v) {
            if (in_array($k, $this->args)) {
                $subjs[$k] = $v;
            }
        }
        // so that subjects are arranged in expected order as the args
        foreach ($this->args as $arg) {		    
            if (isset($subjs[$arg])) {
                $this->subjects[] = $subjs[$arg];
            }
        }
    }
    /**
     * Run all test by invoking the callback
     *
     * @return array
     */
    public function test() {
        return call_user_func_array($this->callback, $this->subjects);
    }
    /**
     * Get the description
     *
     * @return string
     */   
    public function getDescription() { return $this->desc; }

}
?>