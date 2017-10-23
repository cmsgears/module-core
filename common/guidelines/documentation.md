Below mentioned documentation convention can be used to follow CMG. Though it's not 
mandatory or necessary to follow it, but we highly recommend it for CMG modules, 
plugins, widgets and themes development.

We follow the standard annotations specified by phpdoc(https://docs.phpdoc.org).

Documentation:
========================================
* File-Level DocBlock

```php
/**
 * @link link
 * @copyright Copyright (c) year organisation
 * @license license
 * @package package
 * @subpackage sub-package
 */
```

* Class-Level DocBlock - Example

```php
/**
 * Summary goes here.
 *
 * Detail goes here.
 * 
 * @author author <author@email.com>
 * @since version
 */
```

* Types - http://docs.phpdoc.org/guides/types.html

Primitives - string, int, float, bool, array, resource, null, callable

PHPDoc Keywords - mixed, void, object, false, true, self, static $this

* Variables - Static

```php
/**
 * @staticvar type description of static variable
 */
```

* Variables - Member

```php
/**
 * @var type description of static variable
 */
```

* Variables - Array

```php
/** 
 * @var string[] An array of string objects. 
 */
```

* Method

```php
/**
 * Summary goes here.
 *
 * Detail goes here.
 *
 * @since version
 * @param type $param description
 * @return type description
 * @throws <exception classpath> exception description
 * @see <reference>
 */
public function testPublic( $param ) {

    // throw exception

    // return
}
```