##Snap Library

The Snap library is designed to "snap" on to any php code and provide
the ability to stuff meta data into document comments for php classes.

It also provides a simple Class Loader as well as some goodies for
Wordpress, which should probably be made a bit more abstract for use
in any library, but I just don't have the time for that right now.

## Example

To use snap, all you need to do is include the library file and then
reap the benefits.

Check this out...

```php
<?php

// first include the library
require( 'path/to/snap/directory/index.php' );

// now lets define a class with some documents for use later.

/**
 * Dummy Class
 *
 * @some.property           Foo
 * @some.other.property     Bar
 */
class DummyClass
{
    /**
     * @my.meta                 Meta Value
     */
    public $p1;
    
    /**
     * @my.meta                 Other Meta Value
     */
    public $p2;
    
    /**
     * @my.super.meta           Super Awesome Meta
     */ 
    public function exampleFunction()
    {
        
    }
}
```

// Now what? Let's get a Snap_Reflection object for that class
$snap = Snap::get( 'DummyClass' );

// and what can I do with it?
echo $snap->property('p2', 'my.meta'); // will print "Other Meta Value"

// how about default values?
echo $snap->property('p2', 'no.such.meta', 'Oh well'); // will print "Oh well"
    
Well, you might be thinking, "whoopdee doo buddy, thats pretty boring",
but if you use some imagination you can do some pretty neat things. 

The Wordpress functionality is an example of what you can do to cut the
fat on your plugin code and keep it a bit more modular and organized.

Okay, lets get all infomercial - I'll do a before and after to demonstrate the
Wordpress functionality and why its good. Here is some typical plugin code.

```php
<?php

function namespace_some_action( $arg1, $arg2, $arg3 )
{
    echo "Getting some action!";
}
add_action('some_action', 'namespace_some_action', 10, 3 );

function namespace_some_filter( $arg1 )
{
    $arg1 = "You've been filtered!";
    return $arg1;
}
add_filter('some_filter', 'namespace_some_filter', 10 );
```
    
Alright... thats okay. But how about this instead:

```
<?php

class MyNamespace extends Snap_Wordpress_Plugin
{
    /**
     * @wp.action
     */
    public function some_action( $arg1, $arg2, $arg3 )
    {
        echo "Getting some action!";
    }
    
    /**
     * @wp.filter
     */
    public function some_filter( $arg1 )
    {
        $arg1 = "You've been filtered!";
        return $arg1;
    }
    
}
```