<?php
################################################################################
##                            aside_with_filters.php                           #
##                            -----------------------                          #
##                           НЕЗАВИСИМЫЙ ПЕРЕКЛЮЧАТЕЛЬ                         #
##                                  "checkbox"                                 #
##                                                                             #
################################################################################
################################################################################


/**
 * @var $connect
 */

################################################################################
#                         getFiltersHookBlade($connect)                        #
#                   ---------------------------------------                    #
# ПОЛУЧАЕТ ДОСТУПНЫЕ В БАЗЕ ДАННЫХ ВАРИАНТЫ ХВАТОВ ДЛЯ КРЮКОВ (ПРАВЫЙ/ЛЕВЫЙ...)#
#                                                                              #
# $connect - подключаемся к БД                                                 #
# Выполняем SQL-запрос                                                         #
# В качестве результата возвращается выборка интересующих позиций              #
#                                                                              #
################################################################################

function getFiltersHookBlade($connect){
    $sql = "SELECT DISTINCT prop_title, prop_value, prop_value_view 
                FROM products, categories, properties, prod_prop
                WHERE products.category_id = categories.id AND properties.category_id = categories.id 
                AND prod_prop.product_id = products.id AND prod_prop.property_id = properties.id 
                AND prop_title LIKE 'hook_blade'
            ";
    return $connect->query($sql);
}

function getFiltersBladeStiffness($connect){
    $sql = "SELECT DISTINCT prop_title, prop_value, prop_value_view 
                FROM products, categories, properties, prod_prop
                WHERE products.category_id = categories.id AND properties.category_id = categories.id 
                AND prod_prop.product_id = products.id AND prod_prop.property_id = properties.id 
                AND prop_title LIKE 'blade_stiffness'    
            ";
    return $connect->query($sql);
}


################################################################################
#                           getFiltersBrand($connect)                          #
#                   ---------------------------------------                    #
# ПОЛУЧАЕТ ДОСТУПНЫЕ В БАЗЕ ДАННЫХ ВАРИАНТЫ БРЕНДОВ ДЛЯ КЛЮШЕК                 #
#                                                                              #
# $connect - подключаемся к БД                                                 #
# Выполняем SQL-запрос                                                         #
# В качестве результата возвращается выборка интересующих позиций              #
#                                                                              #
################################################################################
function getFiltersBrand($connect){

    $sql = "SELECT DISTINCT brand, brand_view
                FROM products
                    INNER JOIN brands ON products.brand_id = brands.id 
            ";
    return $connect->query($sql);
}


################################################################################
#                          checkParams($params, $value)                        #
#                        --------------------------------                      #
#               ПРОВЕРЯЕТ БЫЛ ЛИ ВЫБРАН В КАЧЕСТВЕ ФИЛЬТРА ПАРАМЕТР            #
#                                                                              #
# $params = $_GET;                                                             #
#                                                                              #
# in_array — Проверяет, присутствует ли в массиве значение                     #
#                                                                              #
# in_array(mixed $needle, array $haystack, bool $strict = false): bool         #
#                                                                              #
# Ищет в haystack значение needle. Если strict не установлен, то при поиске    #
# будет использовано нестрогое сравнение.                                      #
# Если needle - строка, сравнение будет произведено с учётом регистра.         #
# Возвращает true, если needle был найден в массиве,                           #
# и false в противном случае.                                                  #
#                                                                              #
################################################################################
function checkParams($params, $value){
    if (isset($_GET[$params])) {
        return in_array($value, $_GET[$params]) ? "checked" : "";
    }
    return "";
   }

$filters_hook_blade = getFiltersHookBlade($connect); //кладём в переменную результат sql-запроса 
//на выборку из БД доступных вариантов хвата для КРЮКОВ (Правый/Левый/Нейтральный)
     
$filters_blade_stiffness = getFiltersBladeStiffness($connect); //кладём в переменную результат 
//sql-запроса на выборку из БД доступных вариантов жёсткости крюков (soft/medium/hard)
   
$filters_brand = getFiltersBrand($connect); //кладём в переменную результат sql-запроса 
//на выборку из БД доступных вариантов брендов клюшек (Unihoc/Zone)
   
$params = $_GET; //в переменную $params кладётся массив $_GET - Ассоциативный массив параметров, переданных скрипту через URL.

/**
* @var $item
*/

?>

        <aside class="menu-products">
            <div class="category-description">
                <p><?= $item->cat_description ?></p>
            </div>
            <div class="products-filter__title">
                <p>Фильтры для крюков</p>
                <img src="icons/slider.png" alt="slider"></a>
            </div>
            <div class="filter-products">
                <form class="checkbox-block" action="" method="get">
                    <div class="pop-up__checkbox-block-hint">Хват (игровая сторона)
                        <div class="pop-up__checkbox-block-hint-text">
                            Большинство играет на левую сторону (левый хват) - левая рука внизу,
                            крюк с левой стороны и, соответственно, наоборот: правый хват - правая рука внизу (если
                            нормально держать клюшку в обеих руках, клюшка опущена на пол).
                        </div>
                    </div>
                    <div class="prop-list">
                        <?php while ($item = $filters_hook_blade->fetch_object()): ?>
                        <?php //описание функции checkParams
                            ##########################################################################
                            # функция объявлена выше, здесь показана для наглядности и анализа:      #
                            # function checkParams($params, $value){                                 #
                            #    if (isset($_GET[$params])) {                                        #
                            #        return in_array($value, $_GET[$params]) ? "checked" : "";       #
                            #    }                                                                   #
                            # return "";                                                             #
                            # }                                                                      #
                            #                            применение:                                 #
                            # // checkParams("hook_blade", $item->prop_value)                        #
                            #                                                                        #
                            # // function checkParams("hook_blade", "left"{                          #
                            # //   if (isset($_GET["hook_blade"])) {                                 #
                            # //       return in_array("left", $_GET["hook_blade") ? "checked" : ""; #
                            # //   }                                                                 #
                            # // return "";                                                          #
                            # // }                                                                   #
                            # // добавляем свойство checked, если данный параметр был выбран         #
                            #    в качестве фильтра                                                  #
                            ########################################################################## 
                            ?>
                        <div>
                            <input type="checkbox" <?= checkParams("hook_blade", $item->prop_value) ?>
                                id="hook_blade_<?= $item->prop_value ?>" name="hook_blade[]" value="<?= $item->prop_value ?>">
                            <label for="hook_blade_<?= $item->prop_value ?>"><?= $item->prop_value_view ?></label>
                        </div>
                        <?php endwhile; ?>
                    </div>

                    <div class="pop-up__checkbox-block-hint">Степень жёсткости крюка
                        <div class="pop-up__checkbox-block-hint-text">
                            По ощущениям на изгиб крюки бывают в основном мягкими (soft), средней жёсткости (medium),
                            жёсткими (hard). Также есть более определённой степени жёсткости: "Супер жёсткие" (super hard) или, например,
                            "средней жёсткости плюс" (medium +). Более мягкие крюки обеспечивают более уверенный приём мяча, его владение, дриблинг.
                            Жёсткие крюки обладают высокой степенью "отдачи" при выполнении бросков или ударов. В конечном итоге игрок сам
                            оценивает наиболее подходящий его манере игры требуемую степень жёсткости - эти ощущения и оценки приходят с опытом. 
                        </div>
                    </div>

                    <div class="prop-list">
                        <?php while ($item = $filters_blade_stiffness->fetch_object()): ?>
                            <?php //описание функции checkParams
                            ##########################################################################
                            # функция объявлена выше, здесь показана для наглядности и анализа:      #
                            # function checkParams($params, $value){                                 #
                            #    if (isset($_GET[$params])) {                                        #
                            #        return in_array($value, $_GET[$params]) ? "checked" : "";       #
                            #    }                                                                   #
                            # return "";                                                             #
                            # }                                                                      #
                            #                            применение:                                 #
                            # // checkParams("hook_blade", $item->prop_value)                        #
                            #                                                                        #
                            # // function checkParams("hook_blade", "left"{                          #
                            # //   if (isset($_GET["hook_blade"])) {                                 #
                            # //       return in_array("left", $_GET["hook_blade") ? "checked" : ""; #
                            # //   }                                                                 #
                            # // return "";                                                          #
                            # // }                                                                   #
                            ########################################################################## 
                        ?> 
                        <div>
                            <input type="checkbox" <?= checkParams("blade_stiffness", $item->prop_value)?> id="blade_stiffness_<?= $item->prop_value ?>" name="blade_stiffness[]" value="<?= $item->prop_value ?>">
                            <label for="blade_stiffness_<?= $item->prop_value ?>"><?= $item->prop_value_view ?></label>
                        </div>
                        <?php endwhile; ?>
                    </div>

                    
                    <div class="pop-up__checkbox-block-hint">Бренд
                        <div class="pop-up__checkbox-block-hint-text">
                            Ведущие мировые производители лучшей флорбольной экипировки работают на рынке с 1972 года
                            (компания "Юнихок" / UNIHOC)
                            и с 2001 года (компания "Зоун" / ZONE / ZONEFLOORBALL.) - их объединяют общие цели и ценности по
                            эгидой
                            Renew Group Sweden AB. UNIHOC - основной технический партнёр и спонсор международной федерации
                            флорбола (IFF).
                        </div>
                    </div>
                    <div class="prop-list">
                        <?php while ($item = $filters_brand->fetch_object()): ?>
                            <div>
                                <input type="checkbox" <?= checkParams("brand", $item->brand) ?> id="brand_<?= $item->brand ?>" name="brand[]" value="<?= $item->brand ?>">
                                <label for="brand_<?= $item->brand ?>"><?= $item->brand_view ?></label>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <div class="prop-list">
                        <button type="submit" class="submit" value="submit">Применить</button>
                        <div class="reset-link">
                            <a href = "blades.php">Сбросить</a>
                        </div>
                    </div>                    
                </form>
            </div>
        </aside>