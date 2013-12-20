Cyrillic text encoding detection class
==============


Detect utf-8, windows-1251, koi8-r, iso8859-5 cyrillic encoding


Installation with Composer
-------------

Declare detect_encoding as a dependency in your projects `composer.json` file:

``` json
{
  "require": {
    "cnpait/detect_encoding": "dev-master"
  }
}
```

Usage Example
-------------

```php
    <?php

    use DetectCyrillic\Encoding;

    $text = 'Привет, как дела?';
    //require_once 'detect_encoding/classes/DetectCyrillic/Encoding.php';
    $Detector = new Encoding($text);
    $encoding = $Detector->detectMaxRelevant();

    ?>
```

Requirements
--------------
    PHP 5.3 and up.

Класс для определения кодировки текста. Использует статистические методы, см. исходные статьи:

- http://habrahabr.ru/post/107945/
- http://habrahabr.ru/post/127658/


Код является оберткой, готовой к эксплуатации, на основе https://github.com/m00t/detect_encoding/
