<?php
include('../square/square_spec.php');

use SquareSpec\Spec as Spec;

Spec::describe('Member', '#token')->spec(

    Spec::before(function() {
        return array(
            'member' => new Member
        );
    }),

    Spec::it("generates a token upon login")->spec(function($member) {
        $token = $member->login('rightpassword');
        return $token->should_be();
    }),

    Spec::describe('negative tests')->spec(

        Spec::it("does not generate a token upon a failed login")->spec(function($member) {
            $token = $member->login('wrongpassword');
            return $token->should->not_be();
        })

    )

)->run();
?>