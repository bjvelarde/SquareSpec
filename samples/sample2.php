<?php
include('../square_spec.php');

use SquareSpec as SQ;

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


SQ\describe('Bowling')->spec(
    SQ\before(function() {
        return array(
            'bowling' => new Bowling
        );
    }),
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
)->run();
?>