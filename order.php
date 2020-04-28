<?php
header("Content-type: application/json; charset=utf-8");
include "cockpit/bootstrap.php";

// Формируем настройки отправщика из настроек Движка
$settings = cockpit("singletons")->getData("settings");
$domain = $_SERVER["SERVER_NAME"];
$address = $settings["senderEmail"];
$haveToSaveOrders = $settings["haveToSaveOrders"];
$fromName = !empty($settings["senderName"]) ? $settings["senderName"] : "Движок";
$fromAdderss = !empty($settings["senderFrom"]) ? $settings["senderFrom"] : "noreplay@$domain";
$subject = !empty($settings["senderSubject"]) ? $settings["senderSubject"] : "Письмо с сайта $domain";

/* ?><pre><code><?php var_dump($settings); ?></code></pre><?php */

// functions
function numberWithSpaces($number) {
  return number_format($number, 0, ',', ' ');
}

function findProduct($collectionName, $productId) {
  $product = cockpit("collections")->findOne($collectionName, [ "_id" => $productId ]);
  return $product;
}

function calculateSum($orderArray) {
  $totalSum = 0;
  foreach ($orderArray as $key => $position) {
    $totalSum += $position["sum"];
  }
  return $totalSum;
}

function createOrderTable($orderArray) {
  $tableMarkup = <<<EOT
  <table style="border-collapse: separate;" cellspacing="0">
EOT;

  foreach ($orderArray as $key => $position) {
    $title = $position["title"];
    $amount = $position["amount"];
    $price = numberWithSpaces($position["price"]);
    $sum = numberWithSpaces($position["sum"]);
    $tableMarkup .= <<<EOT
    <tr>
      <td style="border-bottom: 1px solid #ccc; padding: 5px 0;">$title</td>
      <td style="border-bottom: 1px solid #ccc; padding: 5px 10px;">$amount шт</td>
      <td style="border-bottom: 1px solid #ccc; padding: 5px 0 5px 10px; text-align: right;">$sum &#x20bd;</td>
    </tr>
EOT;
  }
  $totalSum = numberWithSpaces(calculateSum($orderArray));
  $tableMarkup .= <<<EOT
    <tr>
      <td style="border-bottom: 1px solid #ccc; padding: 5px 0;" colspan="2"><strong>Общая сумма заказа</strong></td>
      <td style="border-bottom: 1px solid #ccc; padding: 5px 0 5px 10px; text-align: right;"><strong>$totalSum &#x20bd;</strong></td>
    </tr>
  </table>
EOT;

  return $tableMarkup;
}

function sendEmail($address, $fromName, $fromAdderss, $subject, $message) {
  $fromBase64 = "=?utf-8?B?".base64_encode($fromName)."?=";
  $subjectBase64 = "=?utf-8?B?".base64_encode($subject)."?=";

  $headers = "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/html; charset=utf-8\r\n";
  $headers .= "From: $fromBase64 <$fromAdderss>\r\n";

  return mail($address, $subjectBase64, $message, $headers, "-f $fromAdderss");
}
// end functions

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (empty($data)) {
  echo json_encode([
    "success" => false,
    "message" => "Не отправлены данные",
    "input" => $input,
  ]);

  die();
}

$orderName = $data["name"];
$orderPhone = $data["phone"];
$orderAddress = $data["address"];
$orderComment = $data["comment"];
$orderArray = $data["order"];

$processedOrderArray = array_map(
  function($position) {
    $categoryId = $position["categoryId"];
    $productId = $position["productId"];
    $sizeIndex = $position["sizeIndex"];
    $amount = $position["amount"];

    // забираем продукт из базы
    $product = findProduct($categoryId, $productId);

    // преобразуем цену в число
    $title = $product["title"];

    $productSize = $product["sizes"][$sizeIndex]["value"];

    $price = intval($productSize["price"]);
    $sizeName = $productSize["sizeName"];

    // вычисляем сумму
    $sum = $amount * $price;

    return [
      "title" => "$title $sizeName",
      "amount" => $amount,
      "price" => $price,
      "sum" => $sum,
    ];
  },
  $orderArray
);

$messageSent = false;
// $orderSaved = false;

// Создаем письмо
$message = <<<EOT
<p>Имя: <strong>$orderName</strong></p>
<p>Телефон: <strong>$orderPhone</strong></p>
<p>Адрес: <strong>$orderAddress</strong></p>
<p>Комментарий: <strong>$orderComment</strong></p>
EOT;
$orderTable = createOrderTable($processedOrderArray);
$totalSum = calculateSum($processedOrderArray);
$message .= $orderTable;

$messageSent = sendEmail($address, $fromName, $fromAdderss, $subject, $message);

echo json_encode(array(
  "success" => $messageSent,
));

if ($haveToSaveOrders) {
  $orderSummary = "";

  foreach ($orderArray as $key => $position) {
    $title = $position["title"];
    $amount = $position["amount"];
    $orderSummary .= "$title × $amount шт\r\n";
  }

  $orderEntry = [
    "name" => $orderName,
    "phone" => $orderPhone,
    "address" => $orderAddress,
    "comment" => $orderComment,
    "order" => $orderSummary,
    "sum" => "$totalSum руб",
  ];

  $result = cockpit("collections")->save('orders', $orderEntry);
}
