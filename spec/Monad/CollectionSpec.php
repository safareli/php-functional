<?php
namespace spec\Monad;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin \Monad\Collection
 */
class CollectionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->beConstructedWith([1, 2, 3]);
        $this->shouldHaveType('Monad\Collection');
        $this->shouldHaveType('Monad\MonadInterface');
        $this->shouldHaveType('Monad\LiftInterface');
    }

    public function it_should_obey_first_monad_law()
    {
        $mAddOne = function ($value) {
            return $value + 1;
        };

        $this->beConstructedWith([1, 2, 3]);
        /** @var \PhpSpec\Wrapper\Subject $right */
        $left = $this->bind($mAddOne);
        $right = array_map($mAddOne, [1, 2, 3]);

        $left->shouldReturn($right);
    }

    public function it_should_obey_second_monad_law()
    {
        $this->beConstructedWith([[1, 2, 3]]);
        $left = $this->bind(\Monad\Collection::create);
        $right = [\Monad\Collection::create([1, 2, 3])];

        $left->shouldHaveSameLike($right);
    }

//    public function it_should_obey_third_monad_law()
//    {
//        $mAddOne = function ($value) {
//            return \Monad\Unit::create($value + 1);
//        };
//        $mAddTwo = function ($value) {
//            return \Monad\Unit::create($value + 2);
//        };
//        $unWrap = function ($x) {
//            return $x;
//        };
//
//        $this->beConstructedWith([1, 2, 3]);
//        $right = $this->bind($mAddOne);
//        $right = \Monad\Collection::create($right);
//        $right = $right->bind($mAddTwo);
//        $right = \Monad\Collection::create($right);
//
//        $left = $this->bind(function($x) use($mAddOne, $mAddTwo){
//            return $mAddOne($x)->bind($mAddTwo);
//        });
//
//        $right->bind($unWrap)->shouldReturn($left->bind($unWrap));
//    }

    public function getMatchers()
    {
        $unWrap = function ($x) {
            return $x;
        };

        return [
            'haveSameLike' => function($left, $right) use ($unWrap) {
                return $left[0]->bind($unWrap) === $right[0]->bind($unWrap);
            },
        ];
    }
}
