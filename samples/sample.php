<?php
include('../config/config.php');

use SquareSpec\Spec as Spec;

Spec::describe('Bowling')->spec(
    Spec::before(function() {
        return array(
            'bowling' => new Bowling
        );
    }),
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
)->run();
?>