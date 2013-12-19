Cyrillic text encoding detection class
==============


Detect utf-8, windows-1251, koi8-r, iso8859-5 cyrillic encoding

Usage:

	$text = 'Привет, как дела?';
        require_once 'detect_encoding/Encoding.php';
        $Detector = new \DetectCyrillic\Encoding($text);
        $encoding = $Detector->detectMaxRelevant();


Класс для определения кодировки текста. Использует статистические методы, см. исходные статьи:

http://habrahabr.ru/post/107945/
http://habrahabr.ru/post/127658/


Код является оберткой, готовой к эксплуатации, на основе https://github.com/m00t/detect_encoding/
