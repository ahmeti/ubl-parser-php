# UBL Parser Php

### Installation

You can install the package via composer:
```bash
composer require ahmeti/ubl-parser
```

### Usage

```php
require __DIR__.'/vendor/autoload.php';

$xmlString = file_get_contents('Path of Invoice UBL (XML) File');

$result = (new UblParser)->set($xmlString)->get();

print_r($result);

```

### Sample

![ubl parser php](https://github.com/ahmeti/ubl-parser-php/blob/master/parser_result.png?raw=true)

