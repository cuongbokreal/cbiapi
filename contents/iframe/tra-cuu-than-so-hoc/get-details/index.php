<?php

$day = (isset($_GET['day']) && strlen($_GET['day']) > 0) ? $_GET['day'] : '1';
$month = (isset($_GET['month']) && strlen($_GET['month']) > 0) ? $_GET['month'] : '1';
$year = (isset($_GET['year']) && strlen($_GET['year']) > 0) ? $_GET['year'] : '2005';
$gender = (isset($_GET['gender']) && strlen($_GET['gender']) > 0) ? $_GET['gender'] : '2'; //nam: 1, nữ: 2
$full_name = (isset($_GET['full_name']) && strlen($_GET['full_name']) > 0) ? $_GET['full_name'] : 'Nguyễn Thị Vân Anh';
$payload = 'func=get_details';
$payload .= '&day=' . $day;
$payload .= '&month=' . $month;
$payload .= '&year=' . $year;
$payload .= '&gender=' . $gender;
$payload .= '&full_name=' . urlencode($full_name);

$kq = array(
  'data_user' => array(
    'day' => $day,
    'month' => $month,
    'year' => $year,
    'gender' => $gender,
    'full_name' => $full_name
  )
);

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://horo.tracuuthansohoc.com/ajax',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => $payload,
  CURLOPT_HTTPHEADER => array(
    'accept-language: vi,fr-FR;q=0.9,fr;q=0.8,en-US;q=0.7,en;q=0.6',
    'content-type: application/x-www-form-urlencoded; charset=UTF-8',
    'origin: https://tracuuthansohoc.com',
    'referer: https://tracuuthansohoc.com/'
  ),
));

$response = curl_exec($curl);
$response = json_decode($response, true);
/*
if($response['status'] === 1){
  $response['html_content'] = removeExternalLinks($response['html_content']);
}*/
$kq += $response;
curl_close($curl);

// Thiết lập header Content-Type là application/json
header('Content-Type: application/json');

// Sử dụng var_dump hoặc print_r để in ra mảng
echo json_encode($kq);



function removeExternalLinks($html) {
    // Tạo một đối tượng DOMDocument
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);

    // Load HTML từ chuỗi
    $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    // Lấy danh sách tất cả các thẻ a
    $anchors = $dom->getElementsByTagName('a');

    // Duyệt qua từng thẻ a và kiểm tra href
    foreach ($anchors as $anchor) {
        $href = $anchor->getAttribute('href');

        // Kiểm tra xem href có chứa domain khác không
        if (parse_url($href, PHP_URL_HOST) !== 'tracuuthansohoc.com') {
            // Nếu có, xóa thẻ a
            $anchor->parentNode->removeChild($anchor);
        }
    }

    // Lấy nội dung HTML đã được chỉnh sửa
    $cleanedHtml = $dom->saveHTML();

    // Trả về kết quả
    return $cleanedHtml;
}

?>
