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
#                           getFiltersHook($connect)                           #
#                   ---------------------------------------                    #
# ПОЛУЧАЕТ ДОСТУПНЫЕ В БАЗЕ ДАННЫХ ВАРИАНТЫ ХВАТОВ ДЛЯ КЛЮШЕК (ПРАВЫЙ/ЛЕВЫЙ...)#
#                                                                              #
# $connect - подключаемся к БД                                                 #
# Выполняем SQL-запрос                                                         #
# В качестве результата возвращается выборка интересующих позиций              #
#                                                                              #
################################################################################

function getFiltersHook($connect){
    $sql = "SELECT DISTINCT prop_title, prop_value, prop_value_view 
                FROM products, categories, properties, prod_prop
                WHERE products.category_id = categories.id AND properties.category_id = categories.id 
                AND prod_prop.product_id = products.id AND prod_prop.property_id = properties.id 
                AND prop_title LIKE 'hook'
            ";
// $sql = "SELECT DISTINCT prop_title, prop_value, prop_value_view
//         FROM products
//             LEFT JOIN categories ON products.category_id = categories.id
//             LEFT JOIN properties ON properties.category_id = categories.id
//             LEFT JOIN prod_prop ON prod_prop.property_id = properties.id
//         WHERE prop_title LIKE 'hook'
//         ";
//выполняем запрос и результат кладём в переменную $result
    return $connect->query($sql);
}

################################################################################
#                           getFiltersSize($connect)                           #
#                   ---------------------------------------                    #
# ПОЛУЧАЕТ ДОСТУПНЫЕ В БАЗЕ ДАННЫХ ВАРИАНТЫ ДЛИН РУКОЯТОК КЛЮШЕК               #
#                                                                              #
# $connect - подключаемся к БД                                                 #
# Выполняем SQL-запрос                                                         #
# В качестве результата возвращается выборка интересующих позиций              #
#                                                                              #
################################################################################

function getFiltersSize($connect){
    $sql = "SELECT DISTINCT size_title, size_value
                FROM products
                    INNER JOIN sizes ON products.size_id = sizes.id 
                ";
    return $connect->query($sql);
}

################################################################################
#                        getFiltersShaftFlex($connect)                         #
#                   ---------------------------------------                    #
# ПОЛУЧАЕТ ДОСТУПНЫЕ В БАЗЕ ДАННЫХ ВАРИАНТЫ ЖЁСТКОСТИ РУКОЯТОК КЛЮШЕК          #
#                                                                              #
# $connect - подключаемся к БД                                                 #
# Выполняем SQL-запрос                                                         #
# В качестве результата возвращается выборка интересующих позиций              #
#                                                                              #
################################################################################

function getFiltersShaftFlex($connect){
    $sql = "SELECT DISTINCT prop_title, prop_value, prop_value_view 
                FROM products, categories, properties, prod_prop
                WHERE products.category_id = categories.id AND properties.category_id = categories.id 
                AND prod_prop.product_id = products.id AND prod_prop.property_id = properties.id 
                AND prop_title LIKE 'shaft_flex'    
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

$filters_hook = getFiltersHook($connect); //кладём в переменную результат sql-запроса 
//на выборку из БД доступных вариантов хвата для клюшек (Правый/Левый/Нейтральный)

$filters_size = getFiltersSize($connect); //кладём в переменную результат sql-запроса 
//на выборку из БД доступных вариантов длин рукояток для клюшек (55/60/65...)

$filters_shaft_flex = getFiltersShaftFlex($connect); //кладём в переменную результат 
//sql-запроса на выборку из БД доступных вариантов жёсткости рукояток для клюшек (23/24/25...)

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
        <p>Фильтры для клюшек</p>
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
                <?php while ($item = $filters_hook->fetch_object()): ?>
                    <div>
                    <?php
                        ##########################################################################
                        # функция объявлена выше, здесь показана для наглядности и анализа:      #
                        # function checkParams($params, $value){                                 #
                        #    if (isset($_GET[$params])) {                                        #
                        #        return in_array($value, $_GET[$params]) ? "checked" : "";       #
                        # }                                                                      #
                        # return "";                                                             #
                        # }                                                                      #
                        #                                                                        #
                        #  $params = $_GET;                                                      #
                        #                                                                        #
                        ########################################################################## 
                    ?>   
                        <input type="checkbox" <?= checkParams("hook", $item->prop_value) ?> // добавляем свойство checked, если данный параметр был выбран в качестве фильтра
                               id="hook_<?= $item->prop_value ?>" name="hook[]" value="<?= $item->prop_value ?>">
                        <label for="hook_<?= $item->prop_value ?>"><?= $item->prop_value_view ?></label>
                    </div>
                <?php endwhile; ?>
            </div>

            <?php 
            /** изначально я "руками" делал так:
            * <!-- <div>
            *    <input type="checkbox" name="hook[]" value="nuetral">
            *    <label for="hook">С обеих сторон</label></input>
            *</div>
            *<div>
            *    <input type="checkbox" name="hook[]" value="right">
            *    <label for="hook">Правый</label></input>
            *</div>
            *<div>
            *    <input type="checkbox" name="hook[]" value="left">
            *    <label for="hook">Левый</label></input>
            *</div> -->
            *
            * и не смог разобраться как обрабатываются "чекбоксы"
            * */
            ?>

            <div class="pop-up__checkbox-block-hint">Длина рукоятки (см)
                <div class="pop-up__checkbox-block-hint-text">
                    Клюшки подбираются по росту игрока. Если поставить клюшку на пол вдоль туловища (габаритная
                    высота), - макушка клюшки должна быть выше
                    пупка не менее, чем на 5-6см, но не выше уровня груди - где-то посередине или выше (помним, что
                    дети за лето вырастают на 3-4-5см).
                    Общая габаритная высота (от пола до макушки, если поставить клюшку вдоль туловища)
                    получается из длины рукоятки плюс 10см (получится общая длина клюшки) и плюс ещё примерно 6см
                    (это закладывается на высоту крюка).
                    Пример: клюшка с длиной рукоятки 96см (общая длина клюшки 106см, габаритная высота - примерно
                    112см) - рекомендуется для игроков ростом (165) 170-180см.
                </div>
            </div>
            <div class="prop-list">
                <?php while ($item = $filters_size->fetch_object()): ?>
                    <div>
                        <input type="checkbox" <?= checkParams("size", $item->size_value) ?> id="size_<?= $item->size_value ?>" 
                        name="size[]" value="<?= $item->size_value ?>">
                        <label for="size_<?= $item->size_value ?>"><?= $item->size_value ?></label>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="pop-up__checkbox-block-hint">Индекс жёсткости рукоятки
                <div class="pop-up__checkbox-block-hint-text">
                    Показывает на сколько миллиметров прогибается рукоятка под действием силы в 300Н
                    (примерно как груз массой 30кг), приложенной к середине рукоятки, которая находится на двух
                    опорах,
                    расстояние между которыми 60см. Чем выше индекс жёсткости - тем мягче рукоятка, меньше требуется
                    сил,
                    чтобы согнуть её. У самой мягкой рукоятки индекс жёсткости - 36мм, у самой жёсткой - 23мм.
                    Идеально когда жёсткость рукоятки сочетается с силой рук и уровня мастерства игрока -
                    в этом случае достигается максимальный эффект для выполнения броска или удара по мячу.
                </div>
            </div>

            <div class="prop-list">
                <?php while ($item = $filters_shaft_flex->fetch_object()): ?>
                    <div>
                        <input type="checkbox" <?= checkParams("shaft_flex", $item->prop_value) ?> id="shaft_flex_<?= $item->prop_value ?>" name="shaft_flex[]" value="<?= $item->prop_value ?>">
                        <label for="shaft_flex_<?= $item->prop_value ?>"><?= $item->prop_value_view ?></label>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="prop-list">
                
            </div>
            <div class="pop-up__checkbox-block-hint">Бренд
                <div class="pop-up__checkbox-block-hint-text">
                    <?=include 'layout/articles/brands-about.php';?>
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
                <button type="reset" class="submit" value="submit">Сбросить</button>
            </div>
        </form>
    </div>
</aside>


