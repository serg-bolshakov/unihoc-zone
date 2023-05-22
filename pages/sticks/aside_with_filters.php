<?php
/**
 * @var $connect
 */


function getFiltersHook($connect){
    // этот запрос тоже работает... аналогично... какой лучше использовать?
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
function getFiltersSize($connect){
    $sql = "SELECT DISTINCT size_title, size_value
                                        FROM products
                                            INNER JOIN sizes ON products.size_id = sizes.id 
                                        ";
    return $connect->query($sql);
}
function getFiltersShaftFlex($connect){
    $sql = "SELECT DISTINCT prop_title, prop_value, prop_value_view 
                                        FROM products, categories, properties, prod_prop
                                        WHERE products.category_id = categories.id AND properties.category_id = categories.id 
                                        AND prod_prop.product_id = products.id AND prod_prop.property_id = properties.id 
                                        AND prop_title LIKE 'shaft_flex'    
                                    ";
    //выполняем запрос и результат кладём в переменную $result
    return $connect->query($sql);
}
function getFiltersBrand($connect){

    $sql = "SELECT DISTINCT brand, brand_view
                                        FROM products
                                            INNER JOIN brands ON products.brand_id = brands.id 
                                        ";
    return $connect->query($sql);
}

function checkParams($params, $value){
 if (isset($_GET[$params])) {
     return in_array($value, $_GET[$params]) ? "checked" : "";
 }
 return "";
}


$filters_hook = getFiltersHook($connect);
$filters_size = getFiltersSize($connect);
$filters_shaft_flex = getFiltersShaftFlex($connect);
$filters_brand = getFiltersBrand($connect);

$params = $_GET;
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
                        <input type="checkbox" <?= checkParams("hook", $item->prop_value) ?> id="hook_<?= $item->prop_value ?>" name="hook[]" value="<?= $item->prop_value ?>">
                        <label for="hook_<?= $item->prop_value ?>"><?= $item->prop_value_view ?></label>
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
                        <input type="checkbox" <?= checkParams("size", $item->size_value) ?> id="size_<?= $item->size_value ?>" name="size[]" value="<?= $item->size_value ?>">
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
                <div class="reset-form">
                    <button>Сброс</button>
                </div>
            </div>
        </form>
    </div>
</aside>


