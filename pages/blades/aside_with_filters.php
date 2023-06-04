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
# ПОЛУЧАЕТ ДОСТУПНЫЕ В БАЗЕ ДАННЫХ ВАРИАНТЫ ХВАТОВ ДЛЯ КРЮКОВ (ПРАВЫЙ/ЛЕВЫЙ...)#
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
                AND prop_title LIKE 'hook_blade'
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