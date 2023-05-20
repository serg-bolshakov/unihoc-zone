<?php
    //подключаемся к базе данных
    require_once 'db.php';
    $sql = "SELECT tag_title, cat_description, meta_name_description, meta_name_keywords, meta_name_robots, style_link FROM categories
    WHERE id = 1";  
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
                            <?php 
                                require_once 'db.php';

                                // этот запрос тоже работает... аналогично... какой лучше использовать?
                                // $sql = "SELECT DISTINCT prop_title, prop_value, prop_value_view 
                                //         FROM products, categories, properties, prod_prop
                                //         WHERE products.category_id = categories.id AND properties.category_id = categories.id 
                                //         AND prod_prop.product_id = products.id AND prod_prop.property_id = properties.id 
                                //         AND prop_title LIKE 'hook'
                                //         ";

                                $sql = "SELECT DISTINCT prop_title, prop_value, prop_value_view
                                        FROM products
                                            LEFT JOIN categories ON products.category_id = categories.id 
                                            LEFT JOIN properties ON properties.category_id = categories.id
                                            LEFT JOIN prod_prop ON prod_prop.property_id = properties.id
                                        WHERE prop_title LIKE 'hook' 
                                        ";
                                //выполняем запрос и результат кладём в переменную $result
                                $result=$connect->query($sql);    
                            ?>
                            <div class="prop-list">
                                <?php while ($item = $result->fetch_object()):?>    
                                <div>
                                    <input type="checkbox" name="<?= $item->prop_title?>[]" value="<?= $item->prop_value?>">
                                    <label for="<?= $item->prop_title?>"><?= $item->prop_value_view?></label></input>
                                </div>
                                <?php endwhile; ?>
                                </div>    
                            
                            <!-- <div>
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
                            </div> -->
                            <?php 
                                require_once 'db.php';
                                $sql = "SELECT DISTINCT prop_title, prop_value, prop_value_view
                                        FROM products
                                            LEFT JOIN categories ON products.category_id = categories.id 
                                            LEFT JOIN properties ON properties.category_id = categories.id
                                            LEFT JOIN prod_prop ON prod_prop.property_id = properties.id
                                        WHERE prop_title LIKE 'shaft_length' 
                                        ";
                                //выполняем запрос и результат кладём в переменную $result
                                $result=$connect->query($sql);    
                            ?>
                            <div  class="pop-up__checkbox-block-hint">Длина рукоятки (см)
                                <div class="pop-up__checkbox-block-hint-text">
                                    Клюшки подбираются по росту игрока. Если поставить клюшку на пол вдоль туловища (габаритная высота), - макушка клюшки должна быть выше 
                                    пупка не менее, чем на 5-6см, но не выше уровня груди - где-то посередине или выше (помним, что дети за лето вырастают на 3-4-5см). 
                                    Общая габаритная высота (от пола до макушки, если поставить клюшку вдоль туловища)
                                    получается из длины рукоятки плюс 10см (получится общая длина клюшки) и плюс ещё примерно 6см (это закладывается на высоту крюка).
                                    Пример: клюшка с длиной рукоятки 96см (общая длина клюшки 106см, габаритная высота - примерно 112см) - рекомендуется для игроков ростом (165) 170-180см.
                                </div>
                            </div>
                            <div class="prop-list">
                                <div>
                                    <?php while ($item = $result->fetch_object()):?>
                                        <input type="checkbox" name="<?= $item->prop_title?>[]" value="<?= $item->prop_value?>">
                                        <label for="<?= $item->prop_title?>"><?= $item->prop_value_view?></label></input>
                                    <?php endwhile; ?> 
                                </div>    
                            </div>
                            <!-- <div class="prop-list">
                                <div>
                                    <input type="checkbox" name="shaft_length" value="55">
                                    <label for="shaft_length">55</label>  
                                </div>                      
                                <div>
                                    <input type="checkbox" name="shaft_length" value="87">
                                    <label for="shaft_length">87</label>   
                                </div>
                                <div> 
                                    <input type="checkbox" name="shaft_length" value="96">
                                    <label for="shaft_length">96</label>   
                                </div>
                                <div> 
                                    <input type="checkbox" name="shaft_length" value="100">
                                    <label for="shaft_length">100</label>   
                                </div>
                                <div>
                                    <input type="checkbox" name="shaft_length" value="104">
                                    <label for="shaft_length">104</label>    
                                </div>
                            </div> -->

                            <div  class="pop-up__checkbox-block-hint">Индекс жёсткости рукоятки
                                <div class="pop-up__checkbox-block-hint-text">
                                    Показывает на сколько миллиметров прогибается рукоятка под действием силы в 300Н 
                                    (примерно как груз массой 30кг), приложенной к середине рукоятки, которая находится на двух опорах,
                                    расстояние между которыми 60см. Чем выше индекс жёсткости - тем мягче рукоятка, меньше требуется сил, 
                                    чтобы согнуть её. У самой мягкой рукоятки индекс жёсткости - 36мм, у самой жёсткой - 23мм. 
                                    Идеально когда жёсткость рукоятки сочетается с силой рук и уровня мастерства игрока - 
                                    в этом случае достигается максимальный эффект для выполнения броска или удара по мячу.
                                </div>
                            </div>

                            <?php 
                                    require_once 'db.php';
                                    $sql = "SELECT DISTINCT prop_title, prop_value, prop_value_view 
                                        FROM products, categories, properties, prod_prop
                                        WHERE products.category_id = categories.id AND properties.category_id = categories.id 
                                        AND prod_prop.product_id = products.id AND prod_prop.property_id = properties.id 
                                        AND prop_title LIKE 'shaft_flex'    
                                    ";
                                    //выполняем запрос и результат кладём в переменную $result
                                    $result=$connect->query($sql);    
                                ?>
                                
                                <div class="prop-list">
                                    <?php while ($item = $result->fetch_object()):?>
                                    <div>
                                        <input type="checkbox" name="<?= $item->prop_title?>[]" value="<?= $item->prop_value?>">
                                        <label for="<?= $item->prop_title?>"><?= $item->prop_value_view?></label></input>
                                    </div>
                                    <?php endwhile; ?>
                                </div> 
                            
                            <div class="prop-list">    
                                <!-- <div>
                                    <input type="checkbox" name="shaft_flex" value="36">
                                    <label for="shaft_flex">36</label>  
                                </div>                      
                                <div>
                                    <input type="checkbox" name="shaft_flex" value="30">
                                    <label for="shaft_flex">30</label>   
                                </div> -->
                                <!-- <div> 
                                    <input type="checkbox" name="shaft_flex" value="29">
                                    <label for="shaft_flex">29</label>   
                                </div>
                                <div> 
                                    <input type="checkbox" name="shaft_flex" value="28">
                                    <label for="shaft_flex">28</label>   
                                </div>
                                <div>
                                    <input type="checkbox" name="shaft_flex" value="27 value=""">
                                    <label for="shaft_flex">27</label>    
                                </div> -->
                            </div>
                            <div  class="pop-up__checkbox-block-hint">Бренд
                                <div class="pop-up__checkbox-block-hint-text">
                                    Ведущие мировые производители лучшей флорбольной экипировки работают на рынке с 1972 года (компания "Юнихок" / UNIHOC) 
                                    и с 2001 года (компания "Зоун" / ZONE / ZONEFLOORBALL.) - их объединяют общие цели и ценности по эгидой 
                                    Renew Group Sweden AB. UNIHOC - основной технический партнёр и спонсор международной федерации флорбола (IFF). 
                                </div>
                            </div>
                            
                            <div class="prop-list">
                                <div>
                                    <input type="checkbox" name="brand" value="UNIHOC">
                                    <label for="brand">UNIHOC</label>  
                                </div>                      
                                <div>
                                    <input type="checkbox" name="brand" value="ZONEFLOORBALL.">
                                    <label for="brand">ZONE</label>   
                                </div>
                            </div>
                            <div class="prop-list">
                                <button type="submit" class="submit" value="submit">Применить</button>
                                
                                <div class="reset-form">
                                    <button>Сброс</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </aside>
                
                
                <?php
                require_once 'db.php'; 
                
                $page = $_GET['page'] ?? 1;
                //проверяем, чтобы не ввели чего лишнего в адресную строну с номером страницы, чтобы скрипт не завис
                $page = filter_var($page, FILTER_VALIDATE_INT, array("options" =>
                        array('default' => 1, 'min_range' => 1, 'max_range' => 99  )));

                $notesOnPage = 6; //LIMIT количество товаров на странице начиная с 0 позиции, 6 товаров, далее с 6-й шесть товаров...
                $fromNewPageStart = ($page - 1) * $notesOnPage;        

                //проверяем набор чекбоксов, где можно выбрать несколько значений
                if(isset($_GET["hook"])){
                    $hook = $_GET["hook"];   
                }

                // $sql = "SELECT * 
                // FROM products, categories, brands, properties, prod_prop, prices, sizes, images
                // WHERE products.category_id = categories.id AND properties.category_id = categories.id AND products.size_id = sizes.id
                // AND prod_prop.product_id = products.id AND prod_prop.property_id = properties.id AND prices.product_id = products.id
                // AND products.brand_id = brands.id AND images.product_id = products.id
                // AND prop_title LIKE 'hook'
                // LIMIT $notesOnPage OFFSET $fromNewPageStart
                // ";
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
                                      WHERE categories.id = 1
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
                            
                            <?php if ($item->price_special): 
                            /* если не назначена акция (специальная цена, то полный блок цен не выводится, его пропускаем - 
                              выводится только актуальная цена (следующий блок)*/    
                            ?> 
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
                              
                // $sql = "SELECT COUNT(*) as count 
                // FROM products, categories, brands, properties, prod_prop, prices, sizes, images
                // WHERE products.category_id = categories.id AND properties.category_id = categories.id AND products.size_id = sizes.id
                // AND prod_prop.product_id = products.id AND prod_prop.property_id = properties.id AND prices.product_id = products.id
                // AND products.brand_id = brands.id AND images.product_id = products.id
                // AND prop_title LIKE 'hook'
                // LIMIT $notesOnPage OFFSET $fromNewPageStart
                // ";

                $sql = "SELECT COUNT(category_id) as count FROM products
                WHERE category_id = 1
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