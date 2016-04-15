<?php
namespace Doctrineum\Tests\Scalar;

use Doctrineum\Scalar\Enum;
use Granam\Scalar\ScalarInterface;

class EnumTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function I_can_use_enum_interface_as_scalar()
    {
        self::assertTrue(is_a(Enum::class, ScalarInterface::class, true));
    }

    /**
     * @test
     */
    public function I_got_enums_comparison_method()
    {
        $enumReflection = new \ReflectionClass(Enum::class);
        $isMethod = $enumReflection->getMethod('is');
        $parameters = $isMethod->getParameters();
        self::assertCount(1, $parameters);
        /** @var \ReflectionParameter $enumAsParameter */
        $enumAsParameter = current($parameters);
        self::assertFalse($enumAsParameter->isOptional());
    }
}