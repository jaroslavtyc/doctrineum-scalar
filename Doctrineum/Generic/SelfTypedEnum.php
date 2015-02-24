<?php
namespace Doctrineum\Generic;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * @method static SelfTypedEnum getType(string $name),
 * @see EnumType::getType
 * @method SelfTypedEnum convertToPHPValue(string $value, AbstractPlatform $platform)
 * @see EnumType::convertToPHPValue
 */
class SelfTypedEnum extends EnumType implements EnumInterface
{
    /**
     * The enum __toString overwrites type __toString method
     * @see \Doctrineum\Generic\EnumTrait::__toString for current
     * and
     * @see \Doctrine\DBAL\Types\Type::__toString for overwritten
     */
    use EnumTrait;

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public static function registerSelf()
    {
        static::addType(static::getTypeName(), static::class);
    }

    /**
     * Type has private constructor, the only way how to create an Enum, which is also Type, is by Type factory method,
     * @see Type::getType
     *
     * @param string|int|float|bool|null $enumValue
     * @return SelfTypedEnum
     */
    protected static function createByValue($enumValue)
    {
        $checkedValue = static::convertToScalarOrNull($enumValue);

        $selfTypedEnum = static::getType(static::getTypeName());
        $selfTypedEnum->enumValue = $checkedValue;

        return $selfTypedEnum;
    }

    /**
     * Core idea of self-typed enum.
     * As an enum class returns itself.
     *
     * @return string
     */
    protected static function getEnumClass()
    {
        return static::class;
    }

    /**
     * Gets the strongly recommended name of this type.
     * Its used at @see \Doctrine\DBAL\Platforms\AbstractPlatform::getDoctrineTypeComment
     *
     * @return string
     */
    public static function getTypeName()
    {
        return 'self-typed-enum';
    }
}
