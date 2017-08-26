<?php

use PHPUnit\Framework\TestCase;

class BuilderTest extends TestCase
{
    public function testDoc1()
    {
        $list = lllist(', ', ' and ')
            ->items(['apple', 'bananas', 'grapes']);

        $this->assertEquals('apple, bananas and grapes', $list->compile());
    }

    public function testDoc2()
    {
        $list = lllist(', ', ' and ')
            ->items(['apple', 'bananas', 'grapes'])
            ->sep(' and also ')
            ->append(null);

        $this->assertEquals('apple, bananas and grapes', $list->compile());
    }

    public function testHelper()
    {
        $list = lllist();

        $this->assertInstanceOf(\Lllist\Builder::class, $list);
    }

    public function testAppend()
    {
        $list = lllist();

        $list->append("a");

        $this->assertEquals("a", $list->compile());;
    }

    public function testAppend2()
    {
        $list = lllist();

        $list
            ->append("a")
            ->append("b");

        $this->assertEquals("a b", $list->compile());
    }

    public function testAppendNull()
    {
        $list = lllist();

        $list
            ->append("a")
            ->append(null)
            ->append("b");

        $this->assertEquals("a b", $list->compile());
    }

    public function testMerge()
    {
        $list = lllist();

        $list2 = lllist(", ")
            ->append(null)
            ->append('1')
            ->append(null)
            ->append('2');

        $list
            ->append("a")
            ->merge($list2, " - ", " and also ")
            ->append("b");


        $this->assertEquals("a - 1, 2 and also b", $list->compile());
    }

    public function testItemsf()
    {
        $list = lllist(", ");

        $list->itemsf('item %s', [0, 1, 2, 3], function ($x) {
            return $x > 0;
        });

        $this->assertEquals("item 1, item 2, item 3", $list->compile());
    }
}