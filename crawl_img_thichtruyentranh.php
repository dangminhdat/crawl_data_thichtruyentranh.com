<?php
/**
 * Crawl image comic from http://thichtruyentranh.com website within link chapter
 *
 * DatDM
 * Create by 2018/10/2
 */
include 'crawl_data_thichtruyentranh.php';

class Image_Thich_Truyen_Tranh extends thichtruyentranh\Data_Thich_Truyen_Tranh
{
	private $instance 		= NULL;
	private $url_comic 		= "/cuc-han-chi-dia/9113.html";
	private $link_chapter 	= "/2016/05/conan/conan-chapter-1021/270130.html?c=1";
	private $data 			= NULL;
	private $data_img 		= [];
	private $pattern_img 	= '#\'<img src="(.*)" .*/>\'#imsU';
	
	function __construct($url_comic = NULL)
	{
		if ($url !== NULL)
		{
			$this->url_comic = $url_comic;
		}
		parent::__construct();
		if ($this->instance === NULL)
		{
			$this->instance = new thichtruyentranh\Data_Thich_Truyen_Tranh();
			$this->data = $this->instance->get_crawl_thich_truyen_tranh($this->url_comic);
		}
	}

	/**
	 * Get image from specific link
	 * @param  [type] $link [description]
	 * @return [type]       [description]
	 */
	public function getImageSpecific($link_chapter = NULL)
	{
		$this->link_chapter = ($link_chapter !== NULL) ? $link_chapter : $this->link_chapter;

		$url = $this->base_url.$this->link_chapter;

		$content = file_get_contents($url, false, $this->proxy);

		preg_match_all($this->pattern_img, $content, $matches);

		return $matches[0];
	}

	/**
	 * Get all image from comic page
	 * @return [type] [description]
	 */
	public function getDataConan()
	{
		for ($i=0; $i < count($this->data); $i++) { 
			$this->data_img[] = $this->getImage($this->data[$i]['href']);
		}
		return $this->data_img;
	}

}
$class_data = new Image_Thich_Truyen_Tranh();

$all = $class_data->get_crawl_thich_truyen_tranh();
$conan = $class_data->getImageSpecific();

echo "<pre>";
print_r($all);
echo "</pre>";

echo "<pre>";
print_r($conan);
echo "</pre>";