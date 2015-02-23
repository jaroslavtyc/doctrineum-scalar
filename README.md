Customizable enumeration type for Doctrine 2.4+

About custom Doctrine types, see the [official documentation](http://doctrine-orm.readthedocs.org/en/latest/cookbook/custom-mapping-types.html).
For default custom types see the [official documentation as well](http://doctrine-dbal.readthedocs.org/en/latest/reference/types.html).

## <span id="usage">Usage</span>
1. [Installation](#installation)
2. [Custom type registration](#custom_type_registration)
3. [Map property as an enum](#map_property_as_an_enum)
4. [Understand the basics](#understand_the_basics)

### <span id="installation">Installation</span>
Edit composer.json at your project, add
```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/jaroslavtyc/doctrineum.git"
        }
    ],
```
then extend in the same composer.json file the field require by doctrineum
```json
    "require": {
        "jaroslavtyc/doctrineum": "dev-master"
    }
```

### <span id="custom_type_registration">Custom type registration</span>

```php
<?php
// in bootstrapping code
// ...
use Doctrine\DBAL\Types\Type;
use Doctrineum\EnumType;
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
            enum: Doctrineum\EnumType
            baz: Foo\BarEnumType
            #...
```

### <span id="map_property_as_an_enum">Map property as an enum</span>
```php
<?php
class Foo
{
    /** @Column(type="\Doctrineum\Enum") */
    protected $field;
}
```

*note: the type has the same name as the Enum class itself, but its just a string; you can change it at child class anytime; see EnumType::getTypeName()*

#### <span id="understand_the_basics">Understand the basics</span>
There are two roles - the factory and the value.
 - EnumType is the factory (as part of the Doctrine\DBAL\Types\Type family), building an Enum following rules.
 - Enum is the value holder, de facto singleton, represented by a class (and class, as you know, can do a lot of things).
