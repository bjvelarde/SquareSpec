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

use SquareSpec as SQ;

SQ\describe('Bowling')->spec(
    SQ\before(
        SQ\let('bowling')->be(new Bowling)
    ),
    SQ\describe('#score')->spec(
        SQ\it("returns 0 for all gutter game")->spec(function($bowling) {
            for ($i = 0; $i < 20; $i++) {
                $bowling->hit(0);
            }
            $bowling->score->should->equals(0);
        })
    ),
    SQ\describe('#strike')->spec(
        SQ\it("returns 'strike' if all 10 pins are down")->spec(function($bowling) {
            $bowling->hit(10);
            $bowling->strike->should->be();
        })
    )
)->test();
?>