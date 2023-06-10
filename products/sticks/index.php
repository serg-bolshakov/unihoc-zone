<?php
//подключаемся к базе данных
//echo $_SERVER['DOCUMENT_ROOT'];
require_once $_SERVER['DOCUMENT_ROOT'].'/db.php';
// объявляем функцию getCategory($connect), которая при подключении к БД  выбирает из неё информацию о категории, кот. используется в head и header
function getCategory($connect)
{
    $sql = "SELECT tag_title, cat_description, meta_name_description, meta_name_keywords, 
    meta_name_robots, style_link FROM categories WHERE id = 1";
    $result = $connect->query($sql);
    return $result->fetch_object(); 
}
/** до этого я не объявлял такую функцию, а тупо сразу делал запрос к БД и получал необходимую информацию, вот так:
* $sql = "SELECT tag_title, cat_description, meta_name_description, meta_name_keywords, meta_name_robots, style_link FROM categories
* WHERE categories.id = 2";  
* $result=$connect->query($sql);
* $item = $result->fetch_object();
    */

    
##################################################################################
#                                   getList($name)                               #
#                        --------------------------------                        #
#        ПОЛУЧАЕМ СПИСОК СВОЙСТВ, ЗАПРОШЕННЫХ ПОЛЬЗОВАТЕЛЕМ ЧЕРЕЗ URL          #
#                                                                                #
# Конструкция foreach предоставляет простой способ перебора массивов.            #
# foreach работает только с массивами и объектами, и будет генерировать ошибку   #
# при попытке использования с переменными других типов или                       #
# неинициализированными переменными.                                             #
# Существует два вида синтаксиса:                                                #
# foreach (iterable_expression as $value)                                        #
#          statement                                                             #
# foreach (iterable_expression as $key => $value)                                #
#          statement                                                             #
# Первый цикл перебирает массив, задаваемый с помощью iterable_expression.       #
# На каждой итерации значение текущего элемента присваивается переменной $value. #
# Второй цикл дополнительно присвоит ключ текущего элемента переменной           #
# $key на каждой итераци                                                         #
#                                                                                #
# mysqli_result::fetch_object -- mysqli_fetch_object —                           #
# Выбирает следующую строку из набора результатов в виде объекта                 #    
# Выбирает одну строку данных из набора результатов и возвращает её              #
# как объект, где каждое свойство представляет имя столбца набора результатов.   #
# Каждый последующий вызов этой функции будет возвращать следующую строку в      # 
# наборе результатов или null, если строк больше нет.                            #
#                                                                                #
# rtrim — Удаляет пробелы (или другие символы) из конца строки                   #
# rtrim(string $string, string $characters = " \n\r\t\v\x00"): string            #
#                                                                                #
#                                                                                #
##################################################################################
function getList($name)  // $hook_blade = getList('hook_blade');
{
    $props = "";
    foreach ($_GET[$name] as $prop) {
        $props .= "\"$prop\" , ";
    }
    return rtrim($props, ", "); // string (16) ""left", "right""
}

$page = $_GET['page'] ?? 1; 
//проверяем, чтобы не ввели чего лишнего в адресную строну с номером страницы, чтобы скрипт не завис
//filter_var — Фильтрует переменную с помощью определённого фильтра
$page = filter_var($page, FILTER_VALIDATE_INT, ["options" => ['default' => 1, 'min_range' => 1, 'max_range' => 99]]);

$notesOnPage = 6;
$fromNewPageStart = ($page - 1) * $notesOnPage;

$item = getCategory($connect); // При вызове функции выбирает одну строку данных из набора результатов и 
//возвращает её как объект, где каждое свойство представляет имя столбца набора результатов. 
//Каждый последующий вызов этой функции будет возвращать следующую строку в наборе 
//результатов или null, если строк больше нет. В объекте $item - свойства категории, который применяются в head и header...

$hook = "";
$shaft_flex = "";
$size = "";
$brand = "";


if (isset($_GET['hook'])){
    $hook = getList('hook');
}
if (isset($_GET['shaft_flex'])) {
    $shaft_flex = getList('shaft_flex');
}

if (isset($_GET['size'])){
    $size = getList('size');
}

if (isset($_GET['brand'])){
    $brand = getList('brand');
}

$whereFromProdProp = "1";
if (!empty($hook) || !empty($shaft_flex)){
    if ($hook === "") {
        $hook = "1) OR (1";
    }
    if ($shaft_flex === "") {
        $shaft_flex = "1) OR (1";
    }
    
    // записываем в переменную $whereFromProdProp строку, которая будет вставляться в SQL-запрос при подсчёте кол-ва записей,
    // которые будут выведены в результате запроса (для подсчёта кол-ва страниц, например)... эта строка может выглятеть так:
    // WHERE property_id IN ( SELECT id FROM properties WHERE (prop_title = 'hook' AND prop_value = 'left') AND (prop_title = 'shaft_flex' AND prop_value = '30') )
    // очень, кстати, круто - я бы сам так сделать никогда бы не догадался... не сообразил, что нужно сделать именно так...

    $whereFromProdProp = <<<SQLWHERE
    property_id IN (
    SELECT id FROM properties WHERE (prop_title = 'hook' AND prop_value IN ($hook))
        AND (prop_title = 'shaft_flex' AND prop_value IN ($shaft_flex)) 
    )
SQLWHERE;
}
$whereFromSize = "1";
if (!empty($size)){
    $whereFromSize = <<<SQLWHERE
  size_id IN (
    SELECT id FROM sizes WHERE (size_title = 'shaft_length' AND size_value IN ($size))
    )
SQLWHERE;
}
$whereFromBrand = "1";
if (!empty($brand)){
    $whereFromBrand = <<<SQLWHERE
  brand_id IN (
    SELECT id FROM brands WHERE (brand IN ($brand))
    )
SQLWHERE;
}

//делаем запрос, который посчитает количество записей в БД
$sql = "SELECT COUNT(DISTINCT p.id) as count FROM products p
LEFT JOIN prod_prop pp on p.id = pp.product_id
LEFT JOIN brands b on p.brand_id = b.id
LEFT JOIN images i on p.id = i.product_id
LEFT JOIN sizes s on s.id = p.size_id
LEFT JOIN prices p2 on p.id = p2.product_id
WHERE
    p.category_id = 1
AND $whereFromProdProp
AND $whereFromSize
AND $whereFromBrand
";

$result = $connect->query($sql);
$count = $result->fetch_assoc()['count']; //и в переменную $count запишем сразу число по ключу ['count'], а не массив
//считаем количество страниц
$pagesCount = ceil($count / $notesOnPage);

$select = <<<SQL
SELECT DISTINCT p.*, b.*, i.*, p2.*, s.*  FROM products p
LEFT JOIN prod_prop pp on p.id = pp.product_id
LEFT JOIN brands b on p.brand_id = b.id
LEFT JOIN images i on p.id = i.product_id
LEFT JOIN sizes s on s.id = p.size_id
LEFT JOIN prices p2 on p.id = p2.product_id
WHERE
    p.category_id = 1
AND img_showcase = true
AND $whereFromProdProp
AND $whereFromSize
AND $whereFromBrand
LIMIT $notesOnPage OFFSET $fromNewPageStart
SQL;


$sql = $select;
//выполняем запрос и результат кладём в переменную $products
$products = $connect->query($sql);

include 'layout.php';
