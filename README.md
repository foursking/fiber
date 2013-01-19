
## Fiber

Fiber is a simple `Dependency Injection Container` for PHP 5.3 that consists of just one file and one class

It's imitate [Pimple](https://github.com/fabpot/Pimple), I don't like it's ArrayAccess Usage

## Dependencies

- php5.3+

## Install

Use require file:

```php
require_once '/path/to/Fiber.php';
```

## Usage

```php
$dic = new Fiber();
```

Create with default injectors

```php
$dic = new Fiber(array(
    'db' => new Database();
));
```

## Examples

### Defining Parameters

```php
$dic->db_host = 'localhost';
$dic->db_name => 'test';
```

### Defining Services

```php
$dic->db = function ($dic) {
    return new Database($dic->db_host);
};

$dic->dbm = function ($dic) {
    return new Database($dic->db_host, $db_name);
};
```

### Defining Shared Services

```php
$dic->db = $dic->share(function ($dic) {
    return new Database($dic->db_host);
});
```

or

```php
$dic->share('db', function ($dic) {
    return new Database($dic->db_host);
});
```

### Protecting Parameters

```php
$dic->save = $dic->protect(function($key, $value){
    return $_SESSION[$key] = $value;
})
```

or

```php
$dic->protect('save', function($key, $value){
    return $_SESSION[$key] = $value;
})
```

Get Closure

```php
$closure = $dic->save;
```

Use protect function

```php
$dic->save('test', 'test');
```

### Modifying services after creation

```php
$dic->extend('db', $dic->share(function ($db, $dic) {
    $db->select($dic->db_name);
    return $db;
}));
```

## License

(The MIT License)

Copyright (c) 2012 hfcorriez &lt;hfcorriez@gmail.com&gt;

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
'Software'), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.