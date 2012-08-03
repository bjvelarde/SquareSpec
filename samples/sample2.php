<?php
include('../config/config.php');

use SquareSpec\describe as describe;
use SquareSpec\before as before;
use SquareSpec\it as it;

describe('Bowling')->spec(
    before(function() {
        return array(
            'bowling' => new Bowling
        );
    }),
    describe('#score')->spec(
        it("returns 0 for all gutter game")->spec(function($bowling) {
            for ($i = 0; $i < 20; $i++) {
                $bowling->hit(0);
            }
            $bowling->score->should->equals(0);
        })
    ),
    describe('#strike')->spec(
        it("returns 'strike' if all 10 pins are down")->spec(function($member) {
            $bowling->hit(10);
            $bowling->strike->should->be();
        })
    )
)->run();
?>