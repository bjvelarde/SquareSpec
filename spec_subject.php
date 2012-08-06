<?php
/**
 * @package SquareSpec
 * @author Benjie Velarde
 * @copyright (c) 2012, Benjie Velarde bvelarde@gmail.com
 * @lincense http://opensource.org/licenses/PHP-3.0
 */
namespace SquareSpec;

use \Exception as Exception;
/**
 * The Spec Subject. Meant to wrap any data so we can 'spec' on it.
 */
class SpecSubject {
    
    public static $failures = array();
    
    public static $total = 0;
    
    public static $success = 0;
    /**
     * @var mixed The test subject
     */
    private $subject;
    /**
     * Constructor
     *
     * @param mixed $subject The test subject
     * @return SpecSubject
     */
    public function __construct($subject=NULL) {
        $this->subject = $subject;
    }

    public function __get($var) {
        if ($var == 'should' || $var == 'to' || $var == 'expect_to') {
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
	    if (is_object($this->subject) && method_exists($this->subject, $method)) {        
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
	public function equals($var) { 
        $return = $this->_equals($var); 
        $this->evaluate($return);
        return $return;        
    }
    /**
     * Test for non-equivalence
     *
     * @param mixed $var
     * @return bool
     */
	public function not_equals($var) { 
        $return = $this->_not_equals($var); 
        $this->evaluate($return);
        return $return;        
    }
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
        if (!isset($var)) {
            $return = (isset($this->subject));
        } else {
            $return = $this->_equals($var);
        }
        $this->evaluate($return);
        return $return;
    }
    /**
     * Test for non-existence or non-equivalence
     *
     * @param mixed $var
     * @return bool
     */
	public function not_be() {
        $args = func_get_args();
        if (count($args)) {
            $var = $args[0];
        }
        if (!isset($var)) {
            $return = (!isset($this->subject));
        } else {
            $return = $this->_not_equals($var);
        }
        $this->evaluate($return);
        return $return;        
	}
    /**
     * Test if value is greater than the subject
     *
     * @param numeric $var
     * @return bool
     */
	public function be_greater_than($var) { 
        $return = ($this->subject > $var);	
        $this->evaluate($return);
        return $return;        
    }
    /**
     * Test if subject is less than the value
     *
     * @param numeric $var
     * @return bool
     */
	public function be_less_than($var) { 
        $return = ($this->subject < $var);	
        $this->evaluate($return);
        return $return;        
    }
    /**
     * Test if subject is greater than or equal to the value
     *
     * @param numeric $var
     * @return bool
     */
	public function be_greater_than_or_equal_to($var) { 
        $return = ($this->subject >= $var);	
        $this->evaluate($return);
        return $return;        
    }
    /**
     * Test if subject is less than or equal to the value
     *
     * @param numeric $var
     * @return bool
     */
	public function be_less_than_or_equal_to($var) { 
        $return = ($this->subject <= $var);	
        $this->evaluate($return);
        return $return;        
    }
    /**
     * Test if subject is in a container...or equivalence for scalar subjects
     *
     * @param mixed $var
     * @return bool
     */	
	public function have($var) {
	    if (is_array($this->subject)) {
		    $return = in_array($var, $this->subject);
		} else {
		    $return = $this->_equals($var);
		}
        $this->evaluate($return);
        return $return;        
	}
    /**
     * Test if subject starts with the string provided
     *
     * @param string $var
     * @return bool
     */		
	public function start_with($str) {
	    $return = (is_string($str) && (strpos($this->subject, $str) === 0));
        $this->evaluate($return);
        return $return;        
	}
    
    public function to_throw($exception='Exception') {
        $return = ($this->subject instanceof $exception);
        $this->evaluate($return);
        return $return;         
    }
    
    public function getSubject() { return $this->subject; }
    /**
     * Evaluate the returned value and store the results
     *
     * @param bool $return     
     */
    private function evaluate($return) {
        if (!$return) {
            $dbt = array_reverse(debug_backtrace());
            $desc = array();
            foreach ($dbt as $bt) {
                if (isset($bt['object']) && $bt['object'] instanceof Testable && $bt['function'] == 'test') {
                    $desc[] = $bt['object']->getDescription();
                }
            }
            self::$failures[] = implode(' ', $desc);
            echo 'F ';
        } else {
            echo '. ';
            self::$success++;
        }
        self::$total++;
    }  

    public function _equals($var) { return ($this->subject === $var); }
    /**
     * Test for non-equivalence
     *
     * @param mixed $var
     * @return bool
     */
	public function _not_equals($var) { return !$this->_equals($var); }
    
}
?>