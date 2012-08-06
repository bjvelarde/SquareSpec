<?php
/**
 * @package SquareSpec
 * @author Benjie Velarde
 * @copyright (c) 2012, Benjie Velarde bvelarde@gmail.com
 * @lincense http://opensource.org/licenses/PHP-3.0
 */
namespace SquareSpec;

use \Exception as Exception;
use \ArrayAccess as ArrayAccess;
use \Countable as Countable;
use \IteratorAggregate as IteratorAggregate;
/**
 * The Spec Subject. Meant to wrap any data so we can 'spec' on it.
 */
class SpecSubject implements IteratorAggregate, Countable, ArrayAccess {

    public static $failures = array();

    public static $total = 0;

    public static $success = 0;
    /**
     * @var mixed The test subject
     */
    private $subject;
    
    private $state;
    /**
     * Constructor
     *
     * @param mixed $subject The test subject
     * @return SpecSubject
     */
    public function __construct($subject=NULL, $state='it') {
        $this->subject = $subject;
        $this->state = $state;
    }

    public function __get($var) {
        if ($var == 'should' || $var == 'to' || $var == 'should_not' || $var == 'to_not' || $var == 'not' || $var == 'be') {
            $this->state = $var;
            return $this;
        } elseif (is_object($this->subject)) {
		    return new self($this->subject->{$var});
		}
		return new self;
    }

	public function __set($var, $val) {
	    if (is_object($this->subject)) {
		    $this->subject->{$var} = $val;
		}
	}

	public function __call($method, $args) {
	    if (is_object($this->subject)) { // && method_exists($this->subject, $method)) {
            try {
		        $ret = call_user_func_array(array($this->subject, $method), $args);
			    return $ret ? new self($ret) : $this;
            } catch (Exception $e) {
                return new self($e);
            }
		}
		return $this;
	}
    /**
     * Test for equivalence
     *
     * @param mixed $var
     * @return bool
     */
	public function equals($var) { return $this->evaluate($this->_equals($var)); }
    /**
     * Test for existence or equivalence
     *
     * @param mixed $var
     * @return bool
     */
    public function be() {
        $args = func_get_args();
        if (count($args)) {
            $var = $args[0];
        }
        return $this->evaluate((!isset($var) ? isset($this->subject) : $this->_equals($var)));
    }
    /**
     * Test is subject is instance of a class or interface
     *
     * @param string $class_or_interface
     * @return bool
     */
    public function be_a($what) {
        return $this->evaluate(
            ((is_object($this->subject) && ($this->subject instanceof $what))) ||
            (is_scalar($this->subject) && gettype($this->subject) == $what)
        );
    }
    /**
     * Test if value is greater than the subject
     *
     * @param numeric $var
     * @return bool
     */
	public function be_greater_than($var) { return $this->evaluate(($this->subject > $var)); }
    /**
     * Test if subject is less than the value
     *
     * @param numeric $var
     * @return bool
     */
	public function be_less_than($var) { return $this->evaluate(($this->subject < $var)); }
    /**
     * Test if subject is greater than or equal to the value
     *
     * @param numeric $var
     * @return bool
     */
	public function be_greater_than_or_equal_to($var) { return $this->evaluate(($this->subject >= $var)); }
    /**
     * Test if subject is less than or equal to the value
     *
     * @param numeric $var
     * @return bool
     */
	public function be_less_than_or_equal_to($var) { return $this->evaluate(($this->subject <= $var)); }
    /**
     * Test if subject is in a container...or equivalence for scalar subjects
     *
     * @param mixed $var
     * @return bool
     */
	public function have($var) {
        return $this->evaluate(
            (is_array($this->subject) ? in_array($var, $this->subject) : $this->_equals($var))
        );
    }
    /**
     * Test if subject starts with the string provided
     *
     * @param string $var
     * @return bool
     */
	public function start_with($str) {
        return $this->evaluate(
            (is_string($str) && (strpos($this->subject, $str) === 0))
        );
    }
    /**
     * Test if subject matches a regexp pattern
     *
     * @param string $pattern
     * @return bool
     */
	public function match($pattern) { return $this->evaluate((is_string($str) && preg_match($pattern, $this->subject))); }
    /**
     * Test if last action threw an exception
     *
     * @param string $exception Exception class
     * @return bool
     */
    public function to_throw($exception='Exception') { return $this->evaluate(($this->subject instanceof $exception)); }

    public function respond_to($method_or_property) {
        $return = FALSE;
        if (is_callable(array($this->subject, '__call')) ||
            is_callable(array($this->subject, '__get')) ||
            is_callable(array($this->subject, $method_or_property))) {
            $return = TRUE;
        } else {
            $ro = new ReflectionObject($this->subject);
            if (($ro->hasMethod($method_or_property) && $ro->getMethod($method_or_property)->isPublic()) ||
                ($ro->hasProperty($method_or_property) && $ro->getProperty($method_or_property)->isPublic())) {
                $return = TRUE;
            }
        }
        return $this->evaluate($return);
    }
    /**
     * Get the raw (unwrapped) test subject
     *
     * @return mixed
     */
    public function getSubject() { return $this->subject; }
    /**
     * Evaluate the returned value and store the results
     *
     * @param bool $return
     */
    private function evaluate($return) {
        $return = (in_array($this->state, array('should_not', 'to_not', 'not'))) ? !$return : $return;
        if (!$return) {
            $dbt = array_reverse(debug_backtrace());
            $desc = array();
            foreach ($dbt as $bt) {
                if (isset($bt['object']) && $bt['object'] instanceof Testable && $bt['function'] == 'test') {
                    $desc[] = $bt['object']->getDescription();
                }
            }
            array_pop($dbt); // call to evaluate, ignore this
            $bt = array_pop($dbt); // this is what we want, the method who called evaluate
            $desc[] = "[{$bt['file']}:{$bt['line']}]";
            self::$failures[] = implode(' ', $desc);
            echo 'F';
        } else {
            echo '.';
            self::$success++;
        }
        self::$total++;
        return $return;
    }

    private function _equals($var) { return ($this->subject === $var); }
    /**
     * IteratorAggreate interface implementation
     */
    public function getIterator() {
        if (is_array($this->subject)) {
            return new ArrayIterator($this->subject);
        } else {
            throw new Exception('Subject is not an array');
        }
    }

    public function count() { return count($this->subject); }
    /**#@+
     * ArrayAccess interface implementation
     */
    public function offsetExists($offset) { return is_array($this->subject) && isset($this->subject[$offset]); }

    public function offsetGet($offset) { return new self((is_array($this->subject) && isset($this->subject[$offset])) ? $this->subject[$offset] : NULL); }

    public function offsetSet($offset, $value) {
        if (is_array($this->subject)) {
            $this->subject[$offset] = $value;
        }
    }

    public function offsetUnset($offset) {
        if (is_array($this->subject)) {
            unset($this->subject[$offset]);
        }
    }
    /**#@-*/
}
?>