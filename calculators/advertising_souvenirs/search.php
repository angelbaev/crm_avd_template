<?php
 // header('Content-Type: application/json;');
  header('Content-Type: application/javascript; charset=utf-8');
  $q = (isset($_GET['term'])? $_GET['term'] : '');

$con = mysqli_connect("localhost", "avdes_avsite", "FIAT126p", "avdes_avd2");

// Проверка за успешна връзка
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Задаване на charset и collation
mysqli_set_charset($con, "cp1251");
mysqli_query($con, "SET collation_connection = 'cp1251_bulgarian_ci'");

$json = array();
if (!empty($q)) {
    $q = mb_convert_encoding($q, "windows-1251", "utf-8");
    $q = mysqli_real_escape_string($con, $q);

    $result = mysqli_query($con, "SELECT * FROM product WHERE model LIKE '%$q%' OR name LIKE '%$q%' LIMIT 20");

    while ($row = mysqli_fetch_assoc($result)) {
        $row['model'] = mb_convert_encoding($row['model'], "utf-8", "windows-1251");
        $row['name'] = mb_convert_encoding($row['name'], "utf-8", "windows-1251");
        $row['description'] = mb_convert_encoding($row['description'], "utf-8", "windows-1251");
        $row['curcode'] = mb_convert_encoding($row['curcode'], "utf-8", "windows-1251");
        $row['in_stock'] = mb_convert_encoding($row['in_stock'], "utf-8", "windows-1251");

        $json[] = array(
            'id' => $row['product_id'],
            'label' => $row['model'] . '-' . $row['name'],
            'value' => $row['model'] . '-' . $row['name'],
            'price' => $row['price'],
            'curcode' => $row['curcode'],
            'min_quantity' => $row['min_quantity'],
            'image' => $row['image'],
            'model' => $row['model'],
            'name' => $row['name'],
            'description' => $row['description'],
            'in_stock' => $row['in_stock'],
        );
    }
}

  echo json_encode($json); 
  
function php2js($a=false)
{
    if (is_null($a) || is_resource($a)) {
        return 'null';
    }
    if ($a === false) {
        return 'false';
    }
    if ($a === true) {
        return 'true';
    }
    
    if (is_scalar($a)) {
        if (is_float($a)) {
            //Always use "." for floats.
            $a = str_replace(',', '.', strval($a));
        }

        // All scalars are converted to strings to avoid indeterminism.
        // PHP's "1" and 1 are equal for all PHP operators, but
        // JS's "1" and 1 are not. So if we pass "1" or 1 from the PHP backend,
        // we should get the same result in the JS frontend (string).
        // Character replacements for JSON.
        static $jsonReplaces = array(
            array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'),
            array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"')
        );

        return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
    }

    $isList = true;

    for ($i = 0, reset($a); $i < count($a); $i++, next($a)) {
        if (key($a) !== $i) {
            $isList = false;
            break;
        }
    }

    $result = array();
    
    if ($isList) {
        foreach ($a as $v) {
            $result[] = php2js($v);
        }
    
        return '[ ' . join(', ', $result) . ' ]';
    } else {
        foreach ($a as $k => $v) {
            $result[] = php2js($k) . ': ' . php2js($v);
        }

        return '{ ' . join(', ', $result) . ' }';
    }
}  
?>