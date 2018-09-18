# crawl_data_thichtruyentranh.com
Crawl all data of chapter in one magic of thichtruyentranh.com website

# config url

$base = "http://thichtruyentranh.com/truyen/2016/05/conan/196.html"

$url = "/truyen/2016/05/conan/196.html";

# init
$object = new Data_Thich_Truyen_Tranh();

# get data
$data = $object->get_crawl_thich_truyen_tranh($url);

# show all
echo "<pre>";
print_r($data);
echo "</pre>";

