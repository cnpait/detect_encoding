<?php

/*

Detect utf-8, windows-1251, koi8-r, iso8859-5 cyrillic encoding

Ivan Matveev, 2013, https://github.com/cnpait/detect_encoding/

Fast fork from https://github.com/m00t/detect_encoding/
See more:
	http://habrahabr.ru/post/107945/
	http://habrahabr.ru/post/127658/

Usage:

    <?php

    use DetectCyrillic\Encoding;

    $text = 'Привет, как дела?';
    //require_once 'detect_encoding/classes/DetectCyrillic/Encoding.php';
    $Detector = new Encoding();
    $encoding = $Detector->detectMaxRelevant($text);

    ?>

*/

namespace DetectCyrillic;

class Encoding
{
	private $possible_encodings = array("windows-1251", "koi8-r", "iso8859-5");
	private $specters = array();

	function __construct()
	{
		$this->setSpecterPath(__DIR__."/../../specters/");
	}

	public function setSpecterPath($path)
	{
		foreach ($this->possible_encodings as $encoding)
		{
			$this->specters[$encoding] = require rtrim($path, "/")."/".$encoding.".php";
		}
	}

	public function getWeights($content)
	{
		$weights = array_fill_keys($this->possible_encodings, 0);

		if(preg_match_all("#(?<let>.{2})#", $content, $matches))
		{
			foreach($matches['let'] as $key)
			{
				foreach ($this->possible_encodings as $encoding)
				{
					if(isset($this->specters[$encoding][$key]))
					{
						$weights[$encoding] += $this->specters[$encoding][$key];
					}
				}
			}
		}

		$weightSum = array_sum($weights);
		foreach ($weights as $encoding => $weight)
		{
			$weights[$encoding] = $weightSum?($weight / $weightSum):0;
		}

		return $weights;
	}

	public function detectMaxRelevant($content)
	{
		if(!$content or preg_match('#.#u', $content))
		{
			return "utf-8";
		}
		else
		{
			$weights = $this->getWeights($content);
			return array_search(max($weights), $weights);
		}
	}

}

?>
