<?php
    //подключаемся к базе данных
    require_once 'db.php';
    $sql = "SELECT tag_title, cat_description, meta_name_description, meta_name_keywords, meta_name_robots, style_link FROM categories
    WHERE categories.id = 2";  
    $result=$connect->query($sql);
    $item = $result->fetch_object();
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
                <!--Делаем меню типа "Аккордеон"-->
                <aside class="menu-products">
                    <div class="category-description">
                        <p><?=$item->cat_description?></p>
                    </div>
                    <div class="products-filter__title">
                        <p>Фильтры для крюков</p>
                        <img src="icons/slider.png" alt="slider" /></a>
                    </div>  
                </aside>
                <?php
                               
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
                

                $sql = "SELECT article, title, category, brand, model, marka, size_value, size_unit, price_regular, price_special, img_link FROM products 
                        LEFT JOIN categories
                            ON products.category_id = categories.id
                        LEFT JOIN brands
                            ON products.brand_id = brands.id
                        INNER JOIN prices
                            ON prices.product_id = products.id
                        LEFT JOIN sizes
                            ON products.size_id = sizes.id
                        INNER JOIN images
                            ON products.id = images.product_id
                            WHERE categories.id = 2
                                AND img_showcase = true
                        ORDER BY RAND()
                        LIMIT $notesOnPage OFFSET $fromNewPageStart
                  ";
                //выполняем запрос и результат кладём в переменную $result
                $result=$connect->query($sql);
                ?>    
                          
                <section class="assortiment-cards">
                    <div class="assortiment-card">
                    <?php while ($item = $result->fetch_object()):?> 
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
                
                <section class="pagination-products">
                
                <?php
                //делаем новый запрос, который посчитает количество записей в БД
                              
                $sql = "SELECT COUNT(category_id) as count FROM products
                        WHERE category_id = 2
                        "; 
                $result=$connect->query($sql);
                $count = $result->fetch_assoc() ['count']; //и в переменную $count запишем сразу число по ключу ['count'], а не массив
                //считаем количество страниц
                $pagesCount = ceil($count / $notesOnPage);
                
                
                // проверяем для стрелочек влево, что мы на первой странице ... если на первой стрелки деактивируются, но остаются видимыми
                if ($page != 1){
                    $prev = $page - 1; //предыдущая страница
                    echo "<a href=\"?page=$prev\"><<</a> ";
                } else {
                    echo "<< ";
                }
                //запускаем цикл для ссылок на страницы
                for ($i=1; $i <= $pagesCount; $i++) {
                    if ($page == $i) {
                        echo "<a href=\"?page=$i\" class=\"activeProduct\">$i</a> ";
                    } else echo "<a href=\"?page=$i\">$i</a> ";
                }
                
                if ($page != $pagesCount){
                    $next = $page + 1; //следующая страница
                    echo "<a href=\"?page=$next\">>></a> ";
                } else {
                    echo " >>";
                }                
                ?>    
                
                </section>
                <?php require_once "layout/footer.php"; ?>