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
 */
class Describe implements Testable {
    /**
     * @var string The description text
     */
    private $desc;
    /**
     * @var array To stack children descriptions & contexts
     */
    private $children;
    private $setup;
    private $teardwon;
    /**
     * @var array The test subjects
     */
    private $subjects;
    /**
     * Constructor
     *
     * @param string $desc The spec description
     * @return Describe
     */
    public function __construct($desc) {
        $this->desc     = $desc;
        $this->setup    =
        $this->teardwon =
        $this->children =
        $this->subjects = array();
    }
    
    public function __invoke($desc) {
        return new self($desc);
    }

    public function &subject($name, $value=NULL) {
        $this->subjects[$name] = $value ? new SpecSubject($value) : NULL;
        return $this;
    }

    public function &let($name, $value) {
        $this->subjects[$name] = ($value instanceof SpecSubject) ? $subject : new SpecSubject($value);
        return $this;
    }

    public function &before($what, $callback) {
        if (is_callable($callback)) {
            $what = $what === 'all' ? 'all' : 'each';
            $this->setup[$what][] = array(
                $callback,
                $this->getCallbackArgs($callback)
            );
        }
        return $this;
    }

    public function &after($what, $callback) {
        if (is_callable($callback)) {
            $what = $what === 'all' ? 'all' : 'each';
            $this->teardwon[$what][] = array(
                $callback,
                $this->getCallbackArgs($callback)
            );
        }
        return $this;
    }
    
    public function &test() {
        foreach ($args as $arg) {
            if ($arg instanceof Testable) {                
                $this->children[$arg->getDescription()] = $arg;
            }
        }
        return $this;        
    }
    
    private function getCallbackArgs($callback) {
        $r      = new ReflectionFunction($callback);
        $args   = $r->getParameters();
        $params = array();
        foreach ($args as $a) {
            $params[] = $a->name;
        }
        return $params;
    }
    
    

    /**
     * The method to receive / wrap all the children descriptions and contexts
     *
     * @param mixed $v,... List of descriptions and contexts. The first param could be a call to Spec::before which should ultimately return an associative array containing the test subjects.
     * @return SpecLevel
     */
    public function &spec() {
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
    public function run() {
        foreach ($this->children as $desc => $child) {
            $child->run();
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