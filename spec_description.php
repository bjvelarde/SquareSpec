<?php
/**
 * @package SquareSpec
 * @author Benjie Velarde
 * @copyright (c) 2012, Benjie Velarde bvelarde@gmail.com
 * @lincense http://opensource.org/licenses/PHP-3.0
 */
namespace SquareSpec;
/**
 * The spec description 
 * --- proove horizontals :) , if you don't get it, nevermind ;)
 */
class SpecDescription implements Testable {

    /**
     * @var string The description text
     */
    private $desc;
    /**
     * @var array To stack children descriptions and contexts
     */    
    private $contexts;
    /**
     * @var array The test subjects
     */        
    private $subjects;
    /**
     * Constructor
     *
     * @param string $desc The spec description
     * @return SpecDescription
     */ 
    public function __construct($desc) {
        $this->desc     = $desc;
        $this->contexts =
        $this->subjects = array();
    }    

    /**
     * The method to receive / wrap all the children descriptions and contexts
     *
     * @param mixed $v,... List of descriptions and contexts. The first param could be a call to Spec::before which should ultimately return an associative array containing the test subjects.
     * @return SpecDescription
     */
    public function &do() {
        $args = func_get_args();
        if (is_array($args[0])) {
            $before = array_shift($args);
            $this->subjects = $this->wrapSubjects($before);
        }
        foreach ($args as $arg) {
            if ($arg instanceof Testable) {
                $arg->addSubjects($this->subjects);
                $this->contexts[$arg->getDescription()] = $arg;
            }
        }
        return $this;
    }
    /**
     * Use to pass subjects to children contexts and descriptions
     *
     * @param array
     */
    public function addSubjects(array $subjects) {
        $subjects = $this->wrapSubjects($subjects);
        $this->subjects = array_merge($subjects, $this->subjects);
        foreach ($this->contexts as $desc => $context) {
            $context->addSubjects($subjects);
            $this->contexts[$desc] = $context;
        }
    }
    /**
     * Get the description
     *
     * @return string
     */    
    //public function getDescription() { return $this->desc; }
    /**
     * Run all test
     *
     * @return array
     */
    public function test() {
        foreach ($this->contexts as $desc => $context) {            
            $context->test();
        }
    }
    /**
     * Get the description
     *
     * @return string
     */       
    public function getDescription() { return $this->desc; }
    /**
     * Wrap each subjects as SpecSubject objects
     *
     * @return array
     */
    private function wrapSubjects(array $subjects) {
        foreach ($subjects as $k => $subject) {
            $subjects[$k] = ($subject instanceof SpecSubject) ? $subject : new SpecSubject($subject);
        }
        return $subjects;
    }    
}
?>