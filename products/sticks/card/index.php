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

// basename — Возвращает последний компонент имени из указанного пути
// basename(string $path, string $suffix = ""): string
// Если компонент имени заканчивается на suffix, то он также будет отброшен.

$textname= basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
//echo $textname; //index.php

// pathinfo — Возвращает информацию о пути к файлу
//pathinfo(string $path, int $flags = PATHINFO_ALL): array|string

$pathInfo = pathinfo($textname); 
//echo $pathInfo["filename"];
//index
$pathInfo = $pathInfo["filename"];
// echo gettype($pathInfo); //string

$test = '11411-klyushka-dlya-florbola-unihoc-cavity-youngster-36mm-neon-green-55cm-left';

$prodId = "SELECT id from products
WHERE prod_url_semantic LIKE ('$pathInfo')
";
$result = $connect->query($prodId);
$prodId = $result->fetch_assoc()['id'];
// echo $prodId;
// echo gettype($prodId);


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

// var_dump($item->img_link);

$imageMain = "SELECT * FROM images 
WHERE
    product_id = $prodId
    AND img_main = true
";
$result = $connect->query($imageMain);
$imageMain = $result->fetch_object();

$propHook = "SELECT prop_description 
FROM products, categories, properties, prod_prop
WHERE products.category_id = categories.id AND properties.category_id = categories.id 
AND prod_prop.product_id = products.id AND prod_prop.property_id = properties.id 
AND prop_title LIKE 'hook' AND products.id = $prodId
";
$result = $connect->query($propHook);
$propHook = $result->fetch_object();

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