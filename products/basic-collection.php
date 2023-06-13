<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Всё для флорбола. Наборы клюшек, мячи, флорбольные ворота от ведущего мирового производителя." />
    <meta name="keywords" content="Базовый набор для флорбола. Флорбольные клюшки, мячи, борта, ворота. Для вратарей." />
    <meta name="robots" content="INDEX,FOLLOW" />
    <link type="image/png" sizes="16x16" rel="icon" href="/icon/favicon-16x16.png">
    <link type="image/png" sizes="32x32" rel="icon" href="/icons/favicon-32x32.png">
    <link type="image/png" sizes="96x96" rel="icon" href="/icons/favicon-96x96.png">
    <link type="image/png" sizes="120x120" rel="icon" href="/icons/favicon-120x120.png">
    <link type="image/png" sizes="256x256" rel="icon" href="/icons/favicon-256x256.png"
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="/styles/style.css" />
    <link rel="stylesheet" href="/styles/main.css" />
    <link rel="stylesheet" href="/styles/products.css"> 
    <title>UnihocZoneRussia. Флорбол. Базовая коллекция</title>
</head>

<body>
    <container class="container-products">
    <?php require_once $_SERVER['DOCUMENT_ROOT'].'/elems/header.php'; ?>   
        <section class="content-products">
            <div class="products-type">
                <img
                    src="/images/main/basic-collection.jpg"
                    alt="basic-collection_banner"
                />
                <div class="products-item product-title">
                    <h1>Базовый набор и оборудование для флорбола</h1>
                </div>
            </div>  
        </section>
                
        <section class="nav-bar">
            <?php include $_SERVER['DOCUMENT_ROOT'].'/elems/nav-bar.php'?>
        </section>
                
        <aside class="menu-products">
            <div class="category-description">
                <p>Базовая коллекция от лидера мирового производства флорбольной экипировки UNIHOC создана, 
                чтобы способствовать распространению этого фантастического вида спорта по всему миру. Флорбол 
                с каждым годом становится все более и более узнаваемым и распространенным, и чтобы иметь возможность 
                быстро начать играть в флорбол, мы представляем ряд продуктов, идеально подходящих для этой цели. 
                Все, от ворот, мячей и моделей клюшек начального уровня, доступно с несколькими вариантами на выбор.</p>
            </div>
            <nav class="aside-products_list">
                <ul>
                    <li><a href="">КЛЮШКИ</a></li>
                    <li><a href="">МЯЧИ</a></li>
                    <li><a href="">ОБОРУДОВАНИЕ</a></li>
                </ul>
            </nav>
        </aside>
        
        <?php require_once $_SERVER['DOCUMENT_ROOT'].'/db.php';
        
        //номер страницы преобразовываем в команду, кот. будет доставать записи по 6 штук на страницу
        //$sql = "SELECT * FROM products LIMIT $fromNewPageStart, $notesOnPage"; 
        // стр 1 -> c 0, 6 записей, стр 2 -> с 6, 6 записей, стр 3 -> с 12, 6 записей...
        //выполняем запрос и результат кладём в переменную $result
        //$result=$connect->query($sql);
        
        $page = $_GET['page'] ?? 1;
        //проверяем, чтобы не ввели чего лишнего в адресную строну с номером страницы, чтобы скрипт не завис
        $page = filter_var($page, FILTER_VALIDATE_INT, array("options" =>
                    array('default' => 1, 'min_range' => 1, 'max_range' => 99  )));

        $notesOnPage = 6; //LIMIT количество товаров на странице начиная с 0 позиции, 6 товаров, далее с 6-й шесть товаров...
        $fromNewPageStart = ($page - 1) * $notesOnPage; 
        
        
        //делаем запрос, который посчитает количество записей в БД
         $sqlCount = "SELECT COUNT(DISTINCT products.id) as count 
         FROM products
            LEFT JOIN prod_prop on products.id = prod_prop.product_id
            LEFT JOIN categories on products.category_id = categories.id
            LEFT JOIN brands on products.brand_id = brands.id
            LEFT JOIN properties on properties.category_id = categories.id
            LEFT JOIN prices on products.id = prices.id
            LEFT JOIN images on products.id = images.product_id
                WHERE images.img_showcase = true  
                    AND prod_prop.property_id = properties.id
                    AND prop_value LIKE 'basic_collection'
        ";

        $resultCount = $connect->query($sqlCount);
        $count = $resultCount->fetch_assoc()['count']; //и в переменную $count запишем сразу число по ключу ['count'], а не массив
        //считаем количество страниц
        $pagesCount = ceil($count / $notesOnPage);
                   
        $select = <<<SQL
        SELECT * 
        FROM products
            LEFT JOIN categories on products.category_id = categories.id
            LEFT JOIN brands on products.brand_id = brands.id
            LEFT JOIN properties on properties.category_id = categories.id
            RIGHT JOIN prod_prop on prod_prop.product_id = products.id
            LEFT JOIN prices on products.id = prices.id
            LEFT JOIN images on products.id = images.product_id
                WHERE prop_value LIKE 'basic_collection'
                    AND prod_prop.property_id = properties.id
                    AND images.img_showcase = true 
                    -- AND products.category_id = 1
                    -- AND prop_title LIKE 'series' AND prop_value LIKE 'basic_collection'
                            LIMIT $notesOnPage OFFSET $fromNewPageStart
SQL;
        $sql = $select;
        //выполняем запрос и результат кладём в переменную $products
        $products = $connect->query($sql);
        ?>    

        <section class="assortiment-cards">
            <?php include $_SERVER['DOCUMENT_ROOT'].'/elems/assortiment-cards.php'?>
        </section>
       
        <?php /*если количество страниц больше, чем 1 - запускаем пагинацию страниц*/if($pagesCount > 1): ?>
        <section class="pagination-products">
            <?php include $_SERVER['DOCUMENT_ROOT'].'/elems/pagination.php'?>
        </section>
        <?php endif; ?>
    <?php require_once $_SERVER['DOCUMENT_ROOT'].'/elems/footer.php'; ?>