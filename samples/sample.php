<?php
//--------------------------------------------------------------------------------------------------------------------------------
// 
//    From console, run: $> php square.php
//    to test all <spec-name>.specs.php files on your designated 'specs' folder (see: square.php)
//
//    To test a single spec: $>php square.php <my-spec>
//    to test <my-spec>.specs.php
//
//--------------------------------------------------------------------------------------------------------------------------------
/*
GIVEN:

class Bowling {    
    public $score = 0;
    public $strike = FALSE;
    
    public function hit($pins) {
        if ($pins == 10) {
            $this->strike = TRUE;
            $this->score = $pins * 2;
        } 
        if ($pins) {
            $this->score = $pins;
        }
    } 
}
*/
include('../square_spec.php');

use SquareSpec\Spec;

Spec::describe('Bowling')->spec(
    Spec::before(
        Spec::let('bowling')->be(new Bowling)
    ),
    Spec::describe('#score')->spec(
        Spec::it("returns 0 for all gutter game")->spec(function($bowling) {
            for ($i = 0; $i < 20; $i++) {
                $bowling->hit(0);
            }
            $bowling->score->should->equals(0);
        })
    ),
    Spec::describe('#strike')->spec(
        Spec::it("returns 'strike' if all 10 pins are down")->spec(function($bowling) {
            $bowling->hit(10);
            $bowling->strike->should->be();
        })
    )
)->test();
?>