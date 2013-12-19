<?php

/*

Detect utf-8, windows-1251, koi8-r, iso8859-5 cyrillic encoding

Ivan Matveev, 2013, https://github.com/cnpait/detect_encodingc

Fast fork from https://github.com/m00t/detect_encoding/
See more:
	http://habrahabr.ru/post/107945/
	http://habrahabr.ru/post/127658/

Usage:

        $text = 'Привет, как дела?';
	require_once 'detect_encoding/Encoding.php';
	$Detector = new \DetectCyrillic\Encoding($text);
	$encoding = $Detector->detectMaxRelevant();

*/

namespace DetectCyrillic;

class Encoding
{
	private $possible_encodings = array("windows-1251", "koi8-r", "iso8859-5");
	private $content;
	private $specter_path;
	private $weights = array();
	private $specters = array();
	private $sum_weight = array();
	private $relevant_encoding;

	function __construct($content)
	{
		$this->specter_path = __DIR__."/specters/";
        	$this->content = $content;
	}

	public function setSpecterPath($path)
	{
		$this->specter_path = $path;
	}

	public function getWeights()
	{
		foreach ($this->possible_encodings as $encoding)
		{
			$this->weights[$encoding] = 0;
			$this->specters[$encoding] = require $this->specter_path.$encoding.".php";
		}

		if(preg_match_all("#(?<let>.{2})#", $this->content, $matches))
		{
			foreach($matches['let'] as $key)
			{
				foreach ($this->possible_encodings as $encoding)
				{
					if(isset($this->specters[$encoding][$key]))
					{
						$this->weights[$encoding] += $this->specters[$encoding][$key];
					}
				}
			}
		}

		$this->sum_weight = array_sum($this->weights);
		foreach ($this->weights as $encoding => $weight)
		{
			$this->weights[$encoding] = ($this->sum_weight)?($weight / $this->sum_weight):0;
		}

		return $this->weights;
	}

	public function detectMaxRelevant()
	{
		if(preg_match('#.#u', $this->content))
		{
			return "utf-8";
		}
		else
		{
			$this->weights = $this->getWeights();
			$this->relevant_encoding = array_keys($this->weights, max($this->weights));
			return $this->relevant_encoding[0];
		}
	}

}

?>
