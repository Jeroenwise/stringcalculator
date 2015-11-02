<?php

require __DIR__ . '/../vendor/autoload.php';

class StringCalculatorTest extends PHPUnit_Framework_TestCase
{
    /** @var StringCalculator */
    private $calc;

    protected function setUp()
    {
        $this->calc = new StringCalculator();
    }

    public function test_add_with_empty_string_returns_0()
    {
        $this->assertEquals(0, $this->calc->add(''));
    }

    public function test_add_with_one_number_returns_that_number()
    {
        $this->assertEquals(0, $this->calc->add('0'));
        $this->assertEquals(1, $this->calc->add('1'));
        $this->assertEquals(2, $this->calc->add('2'));
        $this->assertEquals(3, $this->calc->add('3'));
    }

    public function test_add_with_two_numbers_returns_the_sum_of_those_numbers()
    {
        $this->assertEquals(1, $this->calc->add('0,1'));
        $this->assertEquals(3, $this->calc->add('1,2'));
        $this->assertEquals(6, $this->calc->add('2,4'));
        $this->assertEquals(9, $this->calc->add('3,6'));
    }

    public function test_add_with_multiple_numbers_returns_the_sum_of_those_numbers()
    {
        $this->assertEquals(5, $this->calc->add('0,1,4'));
        $this->assertEquals(11, $this->calc->add('1,2,7,1'));
        $this->assertEquals(15, $this->calc->add('2,4,0,4,5'));
        $this->assertEquals(28, $this->calc->add('3,6,5,6,7,1'));
    }

    public function test_add_with_a_newline_as_delimiter()
    {
        $this->assertEquals(5, $this->calc->add("0,1\n4"));
        $this->assertEquals(11, $this->calc->add("1\n2\n7\n1"));
        $this->assertEquals(15, $this->calc->add("2\n4,0,4\n5"));
        $this->assertEquals(28, $this->calc->add("3,6\n5,6\n7,1"));
    }

    public function test_add_with_custom_delimiter()
    {
        $this->assertEquals(5, $this->calc->add("//q\n0q1q4"));
        $this->assertEquals(11, $this->calc->add("//aa\n1\n2aa7\n1"));
        $this->assertEquals(15, $this->calc->add("//;\n2;4,0,4;5"));
    }

    public function test_add_with_negative_number_throws_exception_with_given_numbers()
    {
        $this->setExpectedException('Exception', 'negatives not allowed (-1)');
        $this->calc->add('0,-1,4');

        $this->setExpectedException('Exception', 'negatives not allowed (-1,-4)');
        $this->calc->add('0,-1,3,-4');
    }

    public function test_add_ignores_bigger_than_1000()
    {
        $this->assertEquals(1001, $this->calc->add('0,1,1000'));
        $this->assertEquals(3, $this->calc->add('1,1002,2'));
        $this->assertEquals(6, $this->calc->add('1100,2,4,1200'));
        $this->assertEquals(9, $this->calc->add('3,6,1234'));
    }

    public function test_add_delimiter_can_have_any_length()
    {
        $this->assertEquals(5, $this->calc->add("//qqq\n0qqq1qqq4"));
        $this->assertEquals(11, $this->calc->add("//aa\n1\n2aa7\n1"));
        $this->assertEquals(15, $this->calc->add("//;;;\n2;;;4,0,4;;;5"));
    }

    public function test_add_with_multiple_custom_delimiters()
    {
        $this->assertEquals(5, $this->calc->add("//[q][;]\n0q1;4"));
        $this->assertEquals(11, $this->calc->add("//[z][a][q]\n1z2a7q1"));
    }

    public function test_add_with_multiple_custom_delimiters_with_multiple_characters()
    {
        $this->assertEquals(5, $this->calc->add("//[qq][;z]\n0qq1;z4"));
        $this->assertEquals(11, $this->calc->add("//[z][aa][qqq]\n1z2aa7qqq1"));
    }

}