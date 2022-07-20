# php-pdo-singleton-class
A singleton class Database which uses PDO and prepared statements .

## Initialization
Edit class consts values according to your databse
```php
private const HOST = 'your database host name';
private const NAME = 'your database name';
private const USERNAME = 'your database username';
private const PASSWORD = 'your database password';
```
Then in your app inlcude class file
```php
require_once 'Db.php';
```

## Expample usage

```php

Db::connect();

$posts = Db::prepare("SELECT * FROM `posts`")::getAll();


$user = Db::prepare('SELECT * FROM `users` WHERE `useranme` = :un AND `password`=:p')::bindValue(':un','john')::bindValue(':p',1234)::getFirst();

// you can also bind values by passing an assoc array of bound variables to 'getFirst' or 'getAll' methods !  
$user = Db::prepare('SELECT * FROM `users` WHERE `useranme` = :un AND `password`=:p')::getFirst(\PDO::FETCH_OBJ,[
    'un'=>'John',
    'p'=>'1234',
]);

```
