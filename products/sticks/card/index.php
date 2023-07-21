<?php

##########################################################################
#   Создаём индексный файл, который создаёт карточки товаров (клюшки)    #
# На входе ЧПУ-ссылка, которая "зашита" в таблице products для id товара #
# в .htaccess определили, что все несуществующие файлы клюшек идут сюда  #
#                                                                        #
# 1) получаем ссылку, затем из неё считываем url, который потом ищем     #
# в таблице products. По нему находим id запрашиваемого товара           #
#                                                                        #
# 2) По id получаем из БД путём написания запросов SQL все необходимые и #
# и существующие свойства и изображения товаров, которые                 #
# подставляются в "веб-морду" - layout.php                               #
#                                                                        #
# В результате формируется карточка товара, запрошенная пользователем    #
########################################################################## 
                        

require_once $_SERVER['DOCUMENT_ROOT'].'/db.php';
// var_dump($_SERVER['REQUEST_URI']);
// var_dump($_SERVER['DOCUMENT_ROOT']);
// var_dump($_SERVER['QUERY_STRING']);

// basename — Возвращает последний компонент имени из указанного пути
// basename(string $path, string $suffix = ""): string
// Если компонент имени заканчивается на suffix, то он также будет отброшен.

$textname= basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
//echo $textname; //10379-klyushka-dlya-florbola-unihoc-sniper-white-blue-104cm-left

// pathinfo — Возвращает информацию о пути к файлу
//pathinfo(string $path, int $flags = PATHINFO_ALL): array|string

$pathInfo = pathinfo($textname); 
// var_dump($pathInfo); // array(3) { 
// ["dirname"]=> string(1) "." 
// ["basename"]=> string(64) "10379-klyushka-dlya-florbola-unihoc-sniper-white-blue-104cm-left" 
// ["filename"]=> string(64) "10379-klyushka-dlya-florbola-unihoc-sniper-white-blue-104cm-left" }

$pathInfo = $pathInfo["filename"];
// echo $pathInfo; 10379-klyushka-dlya-florbola-unihoc-sniper-white-blue-104cm-left

// получаем id запрошенного товара:
$prodId = "SELECT id from products 
WHERE prod_url_semantic LIKE ('$pathInfo')
";
$result = $connect->query($prodId);
$prodId = $result->fetch_assoc()['id'];
// echo $prodId; // 15
// echo gettype($prodId); //string


$select = <<<SQL
SELECT p.*, b.*, i.*, p2.*, s.*, c.category  FROM products p
LEFT JOIN prod_prop pp on p.id = pp.product_id
    LEFT JOIN brands b on p.brand_id = b.id
    LEFT JOIN categories c on p.category_id = c.id
    LEFT JOIN images i on p.id = i.product_id
    LEFT JOIN sizes s on s.id = p.size_id
    LEFT JOIN prices p2 on p.id = p2.product_id
       WHERE p.id = $prodId
SQL;
$products = $connect->query($select);
$item = $products->fetch_object();
//var_dump($item); //object(stdClass)#4 (39) { ["id"]=> string(2) "12" ["article"]=> string(5) "10379" ["title"]=> string(82) "Клюшка для флорбола Unihoc SNIPER 30 white/blue 104cm, Левая" ["category_id"]=> string(1) "1" ["brand_id"]=> string(1) "1" ["model"]=> NULL ["marka"]=> string(6) "SNIPER" ["size_id"]=> string(2) "12" ["colour"]=> string(10) "white/blue" ["material"]=> string(33) "стекловолокно - 100%" ["weight"]=> string(3) "251" ["prod_desc"]=> string(1014) " и так далее: полная информация о товаре из БД

$imageMain = "SELECT * FROM images 
WHERE
    product_id = $prodId
    AND img_main = true
";
$result = $connect->query($imageMain);
$imageMain = $result->fetch_object();
// var_dump($imageMain);
// object(stdClass)#2 (6) { 
// ["id"]=> string(2) "50" 
// ["product_id"]=> string(2) "15" 
// ["img_link"]=> string(62) "images/sticks/10371-stick-unihoc-sniper-30-white-blue-main.jpg" 
// ["img_main"]=> string(1) "1" 
// ["img_showcase"]=> string(1) "0" 
// ["img_promo"]=> string(1) "0" }

// если сделать запрос: $imageMain = $result->fetch_assoc();, то получим (тоже самое, только  массив, НЕ ОБЪЕКТ):
// array(6) { 
// ["id"]=> string(2) "50" 
// ["product_id"]=> string(2) "15" 
// ["img_link"]=> string(62) "images/sticks/10371-stick-unihoc-sniper-30-white-blue-main.jpg" 
// ["img_main"]=> string(1) "1" 
// ["img_showcase"]=> string(1) "0" 
// ["img_promo"]=> string(1) "0" }

$propHook = "SELECT prop_description, prop_value_view
FROM products, categories, properties, prod_prop
WHERE products.category_id = categories.id AND properties.category_id = categories.id 
AND prod_prop.product_id = products.id AND prod_prop.property_id = properties.id 
AND prop_title LIKE 'hook' AND products.id = $prodId
";
$result = $connect->query($propHook);
$propHook = $result->fetch_assoc();
// var_dump($result);
// var_dump($propHook); // array(2) { ["prop_description"]=> string(85) "на левую сторону (левый хват, левая рука внизу) " ["prop_value_view"]=> string(10) "Левый" }
// echo $propHook['prop_value_view'];

$propFlex = "SELECT prop_value_view 
FROM products, categories, properties, prod_prop
WHERE products.category_id = categories.id AND properties.category_id = categories.id 
AND prod_prop.product_id = products.id AND prod_prop.property_id = properties.id 
AND prop_title LIKE 'shaft_flex' AND products.id = $prodId
";
$result = $connect->query($propFlex);
$propFlex = $result->fetch_object();

$propProfile = "SELECT prop_value_view 
FROM products, categories, properties, prod_prop
WHERE products.category_id = categories.id AND properties.category_id = categories.id 
AND prod_prop.product_id = products.id AND prod_prop.property_id = properties.id 
AND prop_title LIKE 'shaft_profile' AND products.id = $prodId
";
$result = $connect->query($propProfile);
$propProfile = $result->fetch_object();

$propGrip = "SELECT prop_value_view 
FROM products, categories, properties, prod_prop
WHERE products.category_id = categories.id AND properties.category_id = categories.id 
AND prod_prop.product_id = products.id AND prod_prop.property_id = properties.id 
AND prop_title LIKE 'grip_type' AND products.id = $prodId
";
$result = $connect->query($propGrip);
$propGrip = $result->fetch_object();

$propBlade = "SELECT prop_value_view 
FROM products, categories, properties, prod_prop
WHERE products.category_id = categories.id AND properties.category_id = categories.id 
AND prod_prop.product_id = products.id AND prod_prop.property_id = properties.id 
AND prop_title LIKE 'blade_model' AND products.id = $prodId
";
$result = $connect->query($propBlade);
$propBlade = $result->fetch_object();

$propSeries = "SELECT prop_value_view 
FROM products, categories, properties, prod_prop
WHERE products.category_id = categories.id AND properties.category_id = categories.id 
AND prod_prop.product_id = products.id AND prod_prop.property_id = properties.id 
AND prop_title LIKE 'series' AND products.id = $prodId
";
$result = $connect->query($propSeries);
$propSeries = $result->fetch_object();

$propCollection = "SELECT prop_value_view 
FROM products, categories, properties, prod_prop
WHERE products.category_id = categories.id AND properties.category_id = categories.id 
AND prod_prop.product_id = products.id AND prod_prop.property_id = properties.id 
AND prop_title LIKE 'collection' AND products.id = $prodId
";
$result = $connect->query($propCollection);
$propCollection = $result->fetch_object();

$prodStatus = "SELECT in_stock, on_sale, reserved, on_order FROM statuses
    LEFT JOIN products on statuses.product_id = products.id 
    WHERE products.id = $prodId
";
$result = $connect->query($prodStatus);
$prodStatus = $result->fetch_object();

$imagePromo = "SELECT img_link FROM images
    WHERE product_id = $prodId
    AND img_promo = 1
";
$resultImagePromo = $connect->query($imagePromo);
// $imagePromo = $result->fetch_object();

include 'layout.php';