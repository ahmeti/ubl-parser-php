# UBL Parser Php

```php
include 'UBLParser.php';


$xml = file_get_contents('Path of Invoice UBL (XML) File');


$parser = new UBLParser;

$parser->set($xml);

$result = $parser->get();

print_r($result);

```

![ubl parser php](https://github.com/ahmeti/ubl-parser-php/blob/master/parser_result.png?raw=true)

