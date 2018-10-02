# crawl_data_thichtruyentranh.com
Crawl all data of chapter in one magic of thichtruyentranh.com website

# config url

$base = "http://thichtruyentranh.com/truyen/2016/05/conan/196.html";

$url = "/truyen/2016/05/conan/196.html";

$base_chapter_01 = "http://thichtruyentranh.com/2016/05/conan/conan-chap-1/10381.html?c=1";

$chapter_01 = "/2016/05/conan/conan-chap-1/10381.html?c=1";

# init
$object = new Image_Thich_Truyen_Tranh();

# get all data of link comic
$data = $object->get_crawl_thich_truyen_tranh($url);

# get link specific of chapter comic
$chapter = $object->getImageSpecific($chapter_01);

# show all link
echo "\<pre\>";
print_r($data);
echo "\<\/pre\>";

# show image of chapter
echo "\<pre\>";
print_r($chapter);
echo "\<\/pre\>";
