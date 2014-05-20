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

use SquareSpec\Spec as Spec;

Spec::describe('Bowling')
    ->subject('bowling')
    ->before('all', function(&$bowling, $db) {
        $bowling = new Bowling;
        $db->connect();
    })
    ->before('each', function($bowling) {
        $bowling->throw_ball();
    })->test(
        Spec::context('on success')
            ->subject('player')
            ->test(
        
            Spec::describe('#score')
                ->let('player', new Player)->test(
                
                Spec::it("returns 0 for all gutter game")->test(function($bowling, $player) {
                    $bowling->setPlayer($player);
                    for ($i = 0; $i < 20; $i++) {
                        $bowling->hit(0);
                    }
                    $bowling->score->should->equals(0);
                })
                
            ),
            
            Spec::describe('#strike')->test(
            
                Spec::it("returns 'strike' if all 10 pins are down")->test(function($bowling) {
                    $bowling->hit(10);
                    $bowling->strike->should->be();
                })
                
            )
            
        )
    )
    ->after('all', function($db) {
        $db->close();
    })
    ->run();
    
    