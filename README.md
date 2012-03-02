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
    
    // Now what? Let's get a Snap_Reflection object for that class
    $snap = Snap::get( 'DummyClass' );
    
    // and what can I do with it?
    echo $snap->property('p2', 'my.meta'); // will print "Other Meta Value"
    
    // how about default values?
    echo $snap->property('p2', 'no.such.meta', 'Oh well'); // will print "Oh well"
    
Well, you might think thats boring, but if you use some imagination you can
do some pretty neat things with that...

The Wordpress functionality is built around the Snap_Reflection object, and
it really helps cut down on, what I find to be, verbose code...