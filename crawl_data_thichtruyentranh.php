<?php
/*
 * Crawl data magic from http://thichtruyentranh.com website
 *
 * DatDM
 * Create by 2018/9/18
 */
namespace thichtruyentranh;

class Data_Thich_Truyen_Tranh
{
    protected $base_url     = "http://thichtruyentranh.com";
    private   $pattern_li   = '#<div .* id="listChapterBlock">.*<ul class="ul_listchap">(.*)</ul>.*</div>#imsU';
    private   $pattern_a    = '#<li>.*<a href="(.*)" title="(.*)">.*</a></li>#imsU';
    private   $paging_li    = '#<div .* id="listChapterBlock">.*<div class="pagingWrap".*<ul>(.*)</ul></div></div>#imsU';
    private   $paging_a     = '#<li>.*<a href="(.*)">.*</a></li>#imsU';
 
    function __construct()
    {
        
    }

    /**
     * Get all url chapter in page thichtruyentranh.com
     *
     * @param $url
     * @return array
     */
    private function get_url_thich_truyen_tranh($url)
    {
        $string = file_get_contents($this->base_url . $url, false, false);

        preg_match_all($this->pattern_li, $string, $matches);

        $data = $matches[1][0];

        preg_match_all($this->pattern_a, $data, $matches_li);

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
        $string = file_get_contents($this->base_url . $url, false, false);

        preg_match_all($this->paging_li, $string, $matches_btn);

        $data = $matches_btn[1][0];

        preg_match_all($this->paging_a, $data, $matches_li);

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
