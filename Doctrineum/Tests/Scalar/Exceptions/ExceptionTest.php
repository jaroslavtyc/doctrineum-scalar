<?php
namespace Doctrineum\Scalar\Exceptions;

class ExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function is_interface()
    {
        $this->assertTrue(interface_exists(Exception::class));
    }

    /**
     * @test
     * @expectedException \Doctrineum\Scalar\Exceptions\Exception
     */
    public function is_local_mark_interface()
    {
        throw new TestExceptionInterface();
    }
}

/** inner */
class TestExceptionInterface extends \Exception implements Exception
{

}