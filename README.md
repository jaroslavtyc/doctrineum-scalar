[![Build Status](https://travis-ci.org/jaroslavtyc/doctrineum-scalar.svg?branch=master)](https://travis-ci.org/jaroslavtyc/doctrineum-scalar)

##### Customizable enumeration type for Doctrine 2.4+

About custom Doctrine types, see the [official documentation](http://doctrine-orm.readthedocs.org/en/latest/cookbook/custom-mapping-types.html).
For default custom types see the [official documentation as well](http://doctrine-dbal.readthedocs.org/en/latest/reference/types.html).

## <span id="usage">Usage</span>
1. [Installation](#installation)
2. [Custom type registration](#custom-type-registration)
3. [Map property as an enum](#map-property-as-an-enum)
3. [Create enum](#create-enum)
4. [Understand the basics](#understand-the-basics)

### <span id="installation">Installation</span>
Edit composer.json at your project, add
```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/jaroslavtyc/doctrineum-scalar.git"
        }
    ],
```
then extend in the same composer.json file the field require by doctrineum
```json
    "require": {
        "doctrineum/scalar": "dev-master"
    }
```

### Custom type registration

```php
<?php
// in bootstrapping code
// ...
use Doctrine\DBAL\Types\Type;
use Doctrineum\Scalar\EnumType;
// ...
// Register type
Type::addType(EnumType::getTypeName(), '\Doctrineum\EnumType');
Type::addType(BarEnumType::getTypeName(), '\Foo\BarEnumType');
```

Or better for PHP [5.5+](http://php.net/manual/en/language.oop5.basic.php#language.oop5.basic.class.class)
```php
// ...
Type::addType(EnumType::getTypeName(), EnumType::class);
Type::addType(BarEnumType::getTypeName(), BarEnumType::class);
```

For Symfony2 using the config is the best approach

```yaml
# app/config/config.yml
doctrine:
    dbal:
        # ...
        types:
            enum: Doctrineum\Scalar\EnumType
            baz: Foo\BarEnumType
            #...
```

### Map property as an enum
```php
<?php
class Foo
{
    /** @Column(type="enum") */
    protected $field;
}
```

### Create enum
```php
<?php
use Doctrineum\Scalar\Enum;

$enum = \Doctrineum\Scalar\Enum::getEnum('foo bar');
```

### Register subtype enum
You can register infinite number of enums, which are used according to a regexp of your choice.
```php
<?php
use Doctrineum\Scalar\EnumType;

EnumType::addSubTypeEnum('\Foo\Bar\YourEnum', '~get me different enum for this value~');
// ...
$enum = $enumType->convertToPHPValue('foo');
get_class($enum) === '\Doctrineum\Scalar\Enum'; // true
get_class($enum) === '\Foo\Bar\YourEnum'; // false
$byRegexpDeterminedEnum = $enumType->convertToPHPValue('And now get me different enum for this value.');
get_class($byRegexpDeterminedEnum) === '\Foo\Bar\YourEnum'; // true
```

*note: the type has the same (lowercased) name as the Enum class itself ("enum"), but its just a string; you can change it at child class anytime; see \Doctrineum\Scalar\EnumType::getTypeName()*

#### Understand the basics
There are two roles - the factory and the value.
 - EnumType is the factory (as part of the Doctrine\DBAL\Types\Type family), building an Enum by following EnumType rules.
 - Enum is the value holder, de facto singleton, represented by a class. And class, as you know, can do a lot of things, which makes enum more sexy then whole scalar value.
 - Subtype is an EnumType, but ruled not just by type, but also by current value itself. One type can has any number of subtypes, in dependence on your imagination and used enum values.
