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
# Выбирает следующую строку из набора результатов в виде объекта               #                                                             #
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


$item = getCategory($connect);

$hooks = "";
$shaft_flex = "";
$size = "";
$brand = "";
if (isset($_GET['hook'])){
    $hooks = getList('hook');
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
if (!empty($hooks) || !empty($shaft_flex)){
    if ($hooks === "") {
        $hooks = "1) OR (1";
    }
    if ($shaft_flex === "") {
        $shaft_flex = "1) OR (1";
    }
    $whereFromProdProp = <<<SQLWHERE
  property_id IN (
    SELECT id FROM properties WHERE (prop_title = 'hook' AND prop_value IN ($hooks))
                                 AND (prop_title = 'shaft_flex' AND prop_value IN ($shaft_flex)) 
    )
SQLWHERE;
}
$whereFormSize = "1";
if (!empty($size)){
    $whereFormSize = <<<SQLWHERE
  size_id IN (
    SELECT id FROM sizes WHERE (size_title = 'shaft_length' AND size_value IN ($size))
    )
SQLWHERE;
}
$whereFormBrand = "1";
if (!empty($brand)){
    $whereFormBrand = <<<SQLWHERE
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
AND $whereFormSize
AND $whereFormBrand
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
AND $whereFormSize
AND $whereFormBrand
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
        $params = $_GET;
        unset($params['page']);
        $query = http_build_query($params);
        //$pagesCount = ceil($row_cnt / $notesOnPage);
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