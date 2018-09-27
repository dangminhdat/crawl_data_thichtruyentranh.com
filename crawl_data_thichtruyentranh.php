<?php
/*
 * Crawl data magic from http://thichtruyentranh.com website
 *
 * DatDM
 * Create by 2018/9/18
 */
class Data_Thich_Truyen_Tranh
{
    private $base_url = "http://thichtruyentranh.com";
    private $proxy = true;

    function __construct()
    {
        $this->proxy = ($this->proxy) ? $this->config_proxy() : NULL;
    }

    /**
     * Check using proxy
     * 
     * @return [boolean]
     */
    private function check_proxy($url)
    {
        $theHeader = curl_init($url);
        curl_setopt($theHeader, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($theHeader, CURLOPT_TIMEOUT, 20);
        curl_setopt($theHeader, CURLOPT_PROXY, $passByIPPort); 
         
        //Execute the request
        $curlResponse = curl_exec($theHeader);

        return $curlResponse;
    }

    /**
     * Config proxy
     * 
     * @return [proxy]
     */
    private function config_proxy()
    {
        $default_opts = array(
          'http'=>array(
            'proxy'=>"tcp://192.168.1.2:3128",
            'request_fulluri' => true,
          )
        );

        $default = stream_context_create($default_opts);

        return $default;
    }

    /**
     * Get all url chapter in page thichtruyentranh.com
     *
     * @param $url
     * @return array
     */
    private function get_url_thich_truyen_tranh($url)
    {
        $string = file_get_contents($this->base_url . $url, false, $this->proxy);

        $pattern = '#<div .* id="listChapterBlock">.*<ul class="ul_listchap">(.*)</ul>.*</div>#imsU';

        preg_match_all($pattern, $string, $matches);

        $data = $matches[1][0];

        $pattern_li = '#<li>.*<a href="(.*)" title="(.*)">.*</a></li>#imsU';

        preg_match_all($pattern_li, $data, $matches_li);

        $array = [];

        for ($i = 0; $i < count($matches_li[1]); $i++) {
            $arr['title'] = $matches_li[2][$i];
            $arr['href'] = $matches_li[1][$i];
            $array[] = $arr;
        }
        return $array;
    }

    /**
     * Get all pager of chapter in page thichtruyentranh.com
     *
     * @param $url
     * @return array
     */
    private function get_paging_thich_truyen_tranh($url)
    {
        $string = file_get_contents($this->base_url . $url, false, $this->proxy);

        $pattern_btn = '#<div .* id="listChapterBlock">.*<div class="pagingWrap".*<ul>(.*)</ul></div></div>#imsU';

        preg_match_all($pattern_btn, $string, $matches_btn);

        $data = $matches_btn[1][0];

        $pattern_li = '#<li>.*<a href="(.*)">.*</a></li>#imsU';

        preg_match_all($pattern_li, $data, $matches_li);

        return $matches_li[1];
    }

    /**
     * Check pager exist of page current
     *
     * @param $i
     * @param $array
     * @return bool
     */
    private function check_paging($i, $array) 
    {
        foreach ($array as $key => $value) {
            if (strpos($value, "trang.".$i.".html") !== false) {
                return $value;
            }
        }
        return false;
    }

    /**
     * All chapter of magic in thichtruyentranh.com
     *
     * @param string $url
     * @return array[title: string, url: string]
     */
    public function get_crawl_thich_truyen_tranh($url = "/truyen/2016/05/conan/196.html")
    {
        $i = 1;
        $result = $this->get_url_thich_truyen_tranh($url);
        while(true) {
            $data = $this->get_paging_thich_truyen_tranh($url);

            $url = $this->check_paging(++$i, $data);

            if($url === false) break;

            $array = $this->get_url_thich_truyen_tranh($url);

            $result = array_merge($result, $array);
        }
        return $result;
    }
}

$class_data = new Data_Thich_Truyen_Tranh();
$conan = $class_data->get_crawl_thich_truyen_tranh("/truyen/2016/05/conan/196.html");

echo "<pre>";
print_r($conan);
echo "</pre>";
