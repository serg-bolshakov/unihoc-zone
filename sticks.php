<?php
/**
 * @var $connect // $connect = new mysqli($host, $username, $password, $db);
 */
require_once 'db.php'; //подключаемся к базе данных

################################################################################
#                              getCategory($connect)                           #
#                        --------------------------------                      #
#      ВЫБИРАЕТ ИЗ БАЗЫ ДАННЫХ ЗНАЧЕНИЯ ДЛЯ ОПИСАНИЯ КАТЕГОРИИ ТОВАРОВ         #
#                                                                              #
#                                                                              #
# $connect - подключаемся к БД                                                 #
# Выполняем SQL-запрос                                                         #
# Возвращаем результат, который записывается в переменную $result              #
#                                                                              #
# mysqli_result::fetch_object -- mysqli_fetch_object —                         #
# Выбирает следующую строку из набора результатов в виде объекта               # 
#                                                                              #
# Выбирает одну строку данных из набора результатов и возвращает её            #
# как объект, где каждое свойство представляет имя столбца набора результатов. #
# Каждый последующий вызов этой функции будет возвращать следующую строку в    # 
# наборе результатов или null, если строк больше нет.                          #
#                                                                              #
################################################################################
function getCategory($connect)
{
    $sql = "SELECT tag_title, cat_description, meta_name_description, meta_name_keywords, 
    meta_name_robots, style_link FROM categories WHERE id = 1";
    $result = $connect->query($sql);
    return $result->fetch_object(); 
}

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
function getList($name)
{
    $props = "";
    foreach ($_GET[$name] as $prop) {
        $props .= "\"$prop\" , ";
    }
    return rtrim($props, ", ");
}

$page = $_GET['page'] ?? 1; 
$notesOnPage = 6;
$fromNewPageStart = ($page - 1) * $notesOnPage;


$item = getCategory($connect); // При вызове функции выбирает одну строку данных из набора результатов и 
//возвращает её как объект, где каждое свойство представляет имя столбца набора результатов. 
//Каждый последующий вызов этой функции будет возвращать следующую строку в наборе 
//результатов или null, если строк больше нет.

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

//делаем новый запрос, который посчитает количество записей в БД
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

//проверяем, чтобы не ввели чего лишнего в адресную строну с номером страницы, чтобы скрипт не завис
$page = filter_var($page, FILTER_VALIDATE_INT, ["options" => ['default' => 1, 'min_range' => 1, 'max_range' => 99]]);


$select = <<<SQL
SELECT DISTINCT p.*, b.*, i.*, p2.*, s.*  FROM products p
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
LIMIT $notesOnPage OFFSET $fromNewPageStart
SQL;

$sql = $select;
//выполняем запрос и результат кладём в переменную $result
$products = $connect->query($sql);
?>
<?php require_once 'layout/head.php'; ?>
    </head>
    <body>
<div class="container-products">
<?php require_once 'layout/header.php'; ?>
    <section class="content-products">
        <div class="products-type">
            <img
                    src="images/main/sticks.jpg"
                    alt="sticks-title_banner"
            />
            <div class="products-item product-title">
                <h1>Флорбольные клюшки</h1>
            </div>
        </div>
    </section>
    <section class="nav-bar">
        <ul class="breadcrumb">
            <li><a href="/">Главная</a></li>
            <li><a href="products.php">Каталог</a></li>
            <li>Клюшки</li>
            <li><a href="blades.php">Крюки</a></li>
            <li><a href="basic.php">Базовый ассортимент</a></li>
        </ul>
    </section>
<!--Делаем меню типа "Аккордеон"-->
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/pages/sticks/aside_with_filters.php" ?>
    <section class="assortiment-cards">
        <div class="assortiment-card">
            <?php while ($item = $products->fetch_object()): ?>
                <div class="assortiment-card__block">
                    <img src="<?= $item->img_link ?>"
                         alt="<?= $item->category ?> <?= $item->brand ?> <?= $item->model ?> <?= $item->marka ?>">
                    <div class="assortiment-card_productName"><?= $item->title ?> </div>
                    <div class="assortiment-card_productPrice">

                        <?php if ($item->price_special):
                            /* если не назначена акция (специальная цена, то полный блок цен не выводится, его пропускаем - 
                              выводится только актуальная цена (следующий блок)*/
                            ?>
                            <p class="priceCurrentSale">
                                <nobr><?= number_format($price = $item->price_special, 0, ",", " "); ?>
                                    <sup>&#8381;</sup></nobr>
                            </p>
                            <p class="priceBeforSale">
                                <nobr><?= number_format($price = $item->price_regular, 0, ",", " "); ?>
                                    <sup>&#8381;</sup></nobr>
                            </p>
                            <p class="priceDiscountInPercentage">
                                <nobr>
                                    - <?= $discount = ceil(100 - ($price = $item->price_special) / ($price = $item->price_regular) * 100); ?>
                                    &#37;
                                </nobr>
                            </p>
                        <?php else: ?>
                            <p class="priceCurrent">
                                <nobr><?= number_format($price = $item->price_regular, 0, ",", " "); ?>
                                    <sup>&#8381;</sup></nobr>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
    <?php if($pagesCount > 1): ?>
    <section class="pagination-products">
        <?php
        $params = $_GET; //в переменную $params кладётся массив $_GET - Ассоциативный массив параметров, переданных скрипту через URL.
        unset($params['page']); // unset() удаляет перечисленные переменные.
        // Если переменная, объявленная глобальной, удаляется внутри функции, то будет удалена только локальная переменная. 
        //Переменная в области видимости вызова функции сохранит то же значение, что и до вызова unset().
        // удаляем один элемент массива: unset($bar['quux']);
        $query = http_build_query($params);

        ##############################################################################################################
        #                                                                                                            #
        #  http_build_query — Генерирует URL-кодированную строку запроса                                             #
        #                     из предоставленного ассоциативного (или индексированного) массива.                     #
        #                                                                                                            #
        #  http_build_query(                                                                                         #
        #      array|object $data,                                                                                   #
        #      string $numeric_prefix = "",                                                                          #
        #      ?string $arg_separator = null,                                                                        #
        #      int $encoding_type = PHP_QUERY_RFC1738                                                                #
        #  ): string                                                                                                 #
        #                                                                                                            #
        #       data                                                                                                 #
        # Может быть массив или объект, содержащий свойства. Если data массив, то он может быть простой одномерной   #
        # структурой или массивом массивов (который, в свою очередь, может содержать другие массивы).                #
        #                                                                                                            #
        # Если data объект, тогда только общедоступные свойства будут включены в результат.                          #                                                                                                     #
        #                                                                                                            #
        #   numeric_prefix                                                                                           #
        # Если числовые индексы используются в базовом массиве и этот параметр указан, то он будет добавлен          #
        # к числовому индексу для элементов только в базовом массиве. Это позволяет обеспечить допустимые имена      #
        # переменных, в которые позже данные будут декодированы PHP или другим CGI-приложением.                      #                                                                                   #
        #                                                                                                            #
        #   arg_separator                                                                                            #
        # Разделитель аргументов. Если не задан или null, то для разделения аргументов используется                  #
        # arg_separator.output.                                                                                      #
        #                                                                                                            #
        #     encoding_type                                                                                          #
        # По умолчанию PHP_QUERY_RFC1738. Если encoding_type равен PHP_QUERY_RFC1738, тогда кодирование              #
        # осуществляется по » RFC 1738 и типу контента application/x-www-form-urlencoded, что подразумевает, что     #
        # пробелы кодируются как символы "плюс" (+).                                                                 #
        # Если enc_type равен PHP_QUERY_RFC3986, тогда кодирование осуществляется в соответствии с » RFC 3986,       #
        # а пробелы будут закодированы в процентах (%20).                                                            #
        #                                                                                                            #
        #     Возвращает URL-кодированную строку.                                                                    #
        # ПРИМЕР ИСПОЛЬЗОВАНИЯ:<?php                                                                                 #
        # $data = array(                                                                                             #
        #     'foo' => 'bar',                                                                                        #
        #     'baz' => 'boom',                                                                                       #
        #     'cow' => 'milk',                                                                                       #
        #     'null' => null,                                                                                        #
        #     'php' => 'hypertext processor'                                                                         #
        # );                                                                                                         #
        #                                                                                                            #
        # echo http_build_query($data) . "\n";                                                                       #
        # echo http_build_query($data, '', '&amp;');                                                                 #
        #                                                                                                            #
        # Результат выполнения данного примера:                                                                      #
        #                                                                                                            #
        # foo=bar&baz=boom&cow=milk&php=hypertext+processor                                                          #
        # foo=bar&amp;baz=boom&amp;cow=milk&amp;php=hypertext+processor                                              #
        #                                                                                                            #
        ##############################################################################################################



        
        // проверяем для стрелочек влево, что мы на первой странице ... если на первой стрелки деактивируются, но остаются видимыми
        if ($page != 1) {
            $prev = $page - 1; //предыдущая страница
            echo "<a href=\"?page=$prev&{$query}\"><< </a> ";
        } else {
            echo "<< ";
        }
        //запускаем цикл для ссылок на страницы
        for ($i = 1; $i <= $pagesCount; $i++) {
            if ($page == $i) {
                echo "<a href=\"?page=$i&{$query}\" class=\"activeProduct\">$i</a> ";
            } else echo "<a href=\"?page=$i&{$query}\">$i</a> ";
        }

        if ($page != $pagesCount) {
            $next = $page + 1; //следующая страница
            echo "<a href=\"?page=$next&{$query}\">>></a> ";
        } else {
            echo " >>";
        }
        ?>

    </section>
    <?php endif; ?>
<?php require_once "layout/footer.php"; ?>