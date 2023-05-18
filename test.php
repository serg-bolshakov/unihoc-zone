<?php
    //подключаемся к базе данных
    require_once 'db.php';
    $sql = "SELECT tag_title, cat_description, meta_name_description, meta_name_keywords, meta_name_robots, style_link FROM categories
    WHERE id = 1";  
    $result=$connect->query($sql);
    $item = $result->fetch_object();
 ?>   
 <!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="<?=$item->meta_name_description?>" />
    <meta name="keywords" content="<?=$item->meta_name_keywords?>" />
    <meta name="robots" content="<?=$item->meta_name_robots?>" />
    <link type="image/png" sizes="16x16" rel="icon" href="icon/favicon-16x16.png">
    <link type="image/png" sizes="32x32" rel="icon" href="icons/favicon-32x32.png">
    <link type="image/png" sizes="96x96" rel="icon" href="icons/favicon-96x96.png">
    <link type="image/png" sizes="120x120" rel="icon" href="icons/favicon-120x120.png">
    <link type="image/png" sizes="256x256" rel="icon" href="icons/favicon-256x256.png"
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="styles/style.css" />
    <link rel="stylesheet" href="styles/main.css" />
    <link rel="stylesheet" href="styles/<?=$item->style_link?>">
    <link rel="stylesheet" href="products_test.css" />
    <title><?=$item->tag_title?></title>
</head>

<body>
    <container class="container-products">
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
                <aside class="menu-products">
                    <div class="category-description">
                        <p><?=$item->cat_description?></p>
                    </div>
                    <div class="products-filter__title">
                        <p>Фильтры для клюшек</p>
                        <img src="icons/slider.png" alt="slider" /></a>
                    </div>  
                    <div class="filter-products">                  
                        <form class="checkbox-block" action="" method="get">      
                            <div  class="pop-up__checkbox-block-hint">Хват (игровая сторона)
                                <div class="pop-up__checkbox-block-hint-text">
                                    Большинство играет на левую сторону (левый хват) - левая рука внизу, 
                                    крюк с левой стороны и, соответственно, наоборот: правый хват - правая рука внизу (если нормально держать клюшку в обеих руках, клюшка опущена на пол).
                                </div>
                            </div>
                            <div>
                                <input type="checkbox" name="hook[]" value="nuetral">
                                <label for="hook">С обеих сторон</label></input>  
                            </div>                      
                            <div>
                                <input type="checkbox" name="hook[]" value="right">
                                <label for="hook">Правый</label></input>   
                            </div>
                            <div> 
                                <input type="checkbox" name="hook[]" value="left">
                                <label for="hook">Левый</label></input>   
                            </div>
                            


                            <button type="submit" class="submit" value="submit">Применить</button>
                        </form>
                    </div>
                </aside>
                <?php
                require_once 'db.php';
                
                //номер страницы преобразовываем в команду, кот. будет доставать записи по 6 штук на страницу
                //$sql = "SELECT * FROM products LIMIT $fromNewPageStart, $notesOnPage"; 
                // стр 1 -> c 0, 6 записей, стр 2 -> с 6, 6 записей, стр 3 -> с 12, 6 записей...
                //выполняем запрос и результат кладём в переменную $result
                //$result=$connect->query($sql);
                
                if(isset($_GET["Хват"])){
     
                    $technologies = $_POST["technologies"];
                    foreach($technologies as $item) echo "$item<br />";   
                }
                
                $page = $_GET['page'] ?? 1;
                //проверяем, чтобы не ввели чего лишнего в адресную строну с номером страницы, чтобы скрипт не завис
                $page = filter_var($page, FILTER_VALIDATE_INT, array("options" =>
                        array('default' => 1, 'min_range' => 1, 'max_range' => 99  )));

                $notesOnPage = 30; //LIMIT количество товаров на странице начиная с 0 позиции, 6 товаров, далее с 6-й шесть товаров...
                $fromNewPageStart = ($page - 1) * $notesOnPage;                 

                $sql = "SELECT * 
                        FROM products, categories, brands, properties, prod_prop, prices, sizes, images
                        WHERE products.category_id = categories.id AND properties.category_id = categories.id AND products.size_id = sizes.id
                        AND prod_prop.product_id = products.id AND prod_prop.property_id = properties.id AND prices.product_id = products.id
                        AND products.brand_id = brands.id AND images.product_id = products.id
                        AND prop_title LIKE '$name' AND prop_value LIKE '$val'
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
                        WHERE category_id = 1
                "; 
                $result=$connect->query($sql);
                $count = $result->fetch_assoc() ['count']; 
                //и в переменную $count запишем сразу число по ключу ['count'], а не массив
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