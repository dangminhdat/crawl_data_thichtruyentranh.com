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
	private $instance = NULL;
	private $data = NULL;
	private $data_img = [];
	
	function __construct()
	{
		parent::__construct();
		if ($this->instance === NULL)
			$this->instance = new thichtruyentranh\Data_Thich_Truyen_Tranh();
		$this->data = $this->getData();
	}

	/**
	 * Get data from class Data_Thich_Truyen_Tranh
	 * @return [type] [description]
	 */
	private function getData()
	{
		if ($this->instance !== NULL)
			return $this->instance->get_crawl_thich_truyen_tranh("/cuc-han-chi-dia/9113.html");
	}

	/**
	 * Get image from specific link
	 * @param  [type] $link [description]
	 * @return [type]       [description]
	 */
	private function getImage($link)
	{
		$url = $this->base_url.$link;

		$content = file_get_contents($url, false, $this->proxy);

		$pattern = '#\'<img src="(.*)" .*/>\'#imsU';

		preg_match_all($pattern, $content, $matches);

		return $matches[0];
	}


	public function getDataConan()
	{
		for ($i=1; $i <= count($this->data); $i++) { 
			$this->data_img[] = $this->getImage($this->data[$i]['href']);
		}
		return $this->data_img;
	}

}

$class_data = new Image_Thich_Truyen_Tranh();
$conan = $class_data->getDataConan();

echo "<pre>";
print_r($conan);
echo "</pre>";