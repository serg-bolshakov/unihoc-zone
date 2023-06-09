<?php
//подключаемся к базе данных
require_once 'db.php';

// объявляем функцию getCategory($connect), которая при подключении к БД  выбирает из неё информацию о категории, кот. используется в head и header
function getCategory($connect)
{
    $sql = "SELECT tag_title, cat_description, meta_name_description, meta_name_keywords, 
    meta_name_robots, style_link FROM categories WHERE id = 2";
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

$hook_blade = "";
$blade_stiffness = "";
$brand = "";

if (isset($_GET['hook_blade'])){
    $hook_blade = getList('hook_blade'); // строка с перечислением определённых пользователем 
    //с помощью формы чекбоксов фильтров: в нашем случае - это строка string (16) ""left", "right""...
    // строка URL выглядит так: /blades.php?hook_blade%5B%5D=left&hook_blade%5B%5D=right
}

if (isset($_GET['blade_stiffness'])) {
    $blade_stiffness = getList('blade_stiffness');
}

if (isset($_GET['brand'])){
    $brand = getList('brand');
}

    $whereFromProdProp = "1";
if (!empty($hook_blade) || !empty($blade_stiffness)){
    if ($hook_blade === "") {
        $hook_blade = "(1) OR (1)";
    }
    if ($blade_stiffness === "") {
        $blade_stiffness = "(1) OR (1)";
    }
    
    // записываем в переменную $whereFromProdProp строку, которая будет вставляться в SQL-запрос при подсчёте кол-ва записей,
    // которые будут выведены в результате запроса (для подсчёта кол-ва страниц, например)... эта строка может выглятеть так:
    // WHERE property_id IN ( SELECT id FROM properties WHERE (prop_title = 'hook_left' AND prop_value = 'left') AND (prop_title = 'blade_stiffness' AND prop_value = 'medium') )
    // очень, кстати, круто - я бы сам так сделать никогда бы не догадался... не сообразил, что нужно сделать именно так...

    $whereFromProdProp = <<<SQLWHERE
    property_id IN (
    SELECT id FROM properties WHERE (prop_title IN ('hook_blade', 'blade_stiffness') AND prop_value IN ($hook_blade, $blade_stiffness)) 
    )
SQLWHERE;
    //$whereFromProdProp = "property_id IN ( SELECT id FROM properties WHERE 
    //(prop_title = 'hook_blade' AND prop_value IN (1) OR (1)) AND (prop_title = 'blade_stiffness' AND prop_value IN ("hard")) )"
}

    $whereFromBrand = "1";
if (!empty($brand)){
    $whereFromBrand = <<<SQLWHERE
brand_id IN (
    SELECT id FROM brands WHERE (brand IN ($brand))
    )
SQLWHERE; // получаем такую строку: string(75) "brand_id IN ( SELECT id FROM brands WHERE (brand IN ("UNIHOC")) )"
}
    
//делаем запрос, который посчитает количество записей в БД
$sql = "SELECT COUNT(DISTINCT p.id) as count FROM products p
LEFT JOIN prod_prop pp on p.id = pp.product_id
LEFT JOIN brands b on p.brand_id = b.id
LEFT JOIN images i on p.id = i.product_id
LEFT JOIN prices p2 on p.id = p2.product_id
WHERE
    p.category_id = 2
AND $whereFromProdProp
AND $whereFromBrand
";

$result = $connect->query($sql);
$count = $result->fetch_assoc()['count']; //и в переменную $count запишем сразу число по ключу ['count'], а не массив
//считаем количество страниц
$pagesCount = ceil($count / $notesOnPage);

$select = <<<SQL
SELECT DISTINCT p.*, b.*, i.*, p2.*  FROM products p
LEFT JOIN prod_prop pp on p.id = pp.product_id
LEFT JOIN brands b on p.brand_id = b.id
LEFT JOIN images i on p.id = i.product_id
LEFT JOIN prices p2 on p.id = p2.product_id
WHERE
    p.category_id = 2
AND img_showcase = true
AND $whereFromProdProp
AND $whereFromBrand
LIMIT $notesOnPage OFFSET $fromNewPageStart
SQL;

$sql = $select;
//выполняем запрос и результат кладём в переменную $products
$products = $connect->query($sql);
 ?>   

 <?php require_once 'layout/head.php'; ?>

</head>

<body>
    <container class="container-products">
        <?php require_once 'layout/header.php'; ?>    
        <section class="content-products">
            <div class="products-type">
                <img
                    src="images/main/blades.jpg"
                    alt="blades-title_banner"
                />
                <div class="products-item product-title">
                    <h1>Крюки для флорбольных клюшек</h1>
                </div>
            </div>  
        </section>
                
        <section class="nav-bar">
            <ul class="breadcrumb">
                <li><a href="/">Главная</a></li>
                <li><a href="products.php">Каталог</a></li>
                <li><a href="sticks.php">Клюшки</a></li>
                <li>Крюки</li>
                <li><a href="basic.php">Базовый ассортимент</a></li>
            </ul>
        </section>
        
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . "/pages/blades/aside_with_filters.php" ?>
       
        <section class="assortiment-cards">
            <div class="assortiment-card">
            <?php while ($item = $products->fetch_object()):?> 
                <div class="assortiment-card__block">
                    <img src="<?= $item->img_link?>" alt="<?= $item->category?> <?= $item->brand?> <?= $item->model ?> <?= $item->marka ?>">
                    <div class="assortiment-card_productName"><?= $item->title?> </div>
                    <div class="assortiment-card_productPrice">
                    
                    <?php
                        /* если не назначена акция (специальная цена, 
                        то полный блок цен не выводится, его пропускаем - 
                        выводится только актуальная цена (следующий блок)*/
                    ?>

                    <?php if ($item->price_special): ?> 
                        <p class="priceCurrentSale"><nobr><?= number_format($price= $item->price_special, 0,",", " " ); ?> <sup>&#8381;</sup></nobr></p>
                        <p class="priceBeforSale"><nobr><?= number_format($price= $item->price_regular, 0,",", " " ); ?> <sup>&#8381;</sup></nobr></p>
                        <p class="priceDiscountInPercentage"><nobr>- <?= $discount = ceil(100 - ($price= $item->price_special) / ($price= $item->price_regular) * 100); ?>&#37;</nobr></p>
                    <?php else: ?>
                        <p class="priceCurrent"><nobr><?= number_format($price= $item->price_regular, 0,",", " " ); ?> <sup>&#8381;</sup></nobr></p>
                    <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
            </div>
        </section>
            
        <?php if($pagesCount > 1): ?>
        <section class="pagination-products">
        <?php
        $params = $_GET; // в переменную $params кладётся массив $_GET - Ассоциативный массив параметров, переданных скрипту через URL.
        // array(1) { ["page"]=> string(1) "3" } (когда выбрали для просмотра третью страницу)

        unset($params['page']); // результат: array(0) { } 
        // unset() удаляет перечисленные переменные.
        // Если переменная, объявленная глобальной, удаляется внутри функции, то будет удалена только локальная переменная. 
        //Переменная в области видимости вызова функции сохранит то же значение, что и до вызова unset().
        // удаляем один элемент массива: unset($bar['quux']);

        $query = http_build_query($params); // передаём: array(1) { ["page"]=> string(1) "3" } 
                                            // получаем в результате: string(6) "page=3"
        
        
        // проверяем для стрелочек влево, что мы на первой странице ... если на первой стрелки деактивируются, но остаются видимыми
        if ($page != 1){
            $prev = $page - 1; //предыдущая страница
            echo "<a href=\"?page=$prev&{$query}\"><< </a> "; // я до этого делал без {&$query}
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