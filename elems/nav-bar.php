<?php
    /*Исходим из того, что у нас есть чистые html - ссылки, в которых можно руками переопределять клаас
    на каждой странице контента, но это можно делать когда страниц мало, а когда много? Писать руками на каждой странице? 
    
    Думаем: нужна функция, которая будет создавать эти ссылки, класс будем определять для той или иной ссылки 
    в зависимости от того на какой странице мы находимся.
    
    Решение: 
    1) Объявляем и пишем  функцию, которая будет создавать ссылки function creatLink()
    2) в качестве параметров ф-ия будет принимать href-ссылки и её текст (заговоловок) function creatLink ($href, $title)
    3) в результате выполнения действий ф-я должна либо вернуть, либо вывести через эхо ссылку
    4) Главное - это "закодить", чтобы класс эктив был у активной ссылки
    5) получаем текущий адрес страцицы из адресной строк - делаем с помощью суперглобальной переменной $_SERVER['REQUEST_URI']
    6) сравниваем с if... и если совпадает: применяем класс эктив, иначе - нет 

    function creatLink($href, $title) {
        if ($_SERVER['REQUEST_URI'] == $href) {
            echo "<a href=\"$href\" class=\"activeBreadcrumb\">$title</a>";
        } else echo "<a href=\"$href\">$title</a>";
    } 
    
    попробуем "упростить" ... введём переменную $class = 'class="activeBreadcrumb"'

    echo "<a href=\"$href\" class=\"activeBreadcrumb\">$title</a>";
    
    */

    function creatLink($href, $title) {
        if ($_SERVER['REQUEST_URI'] == $href) {
            $class = ' class="activeBreadcrumb"';
        } else $class = "";
        
        echo "<a href=\"$href\"$class>$title</a>";
    }
?> 
            <ul class="breadcrumb">
                <li><?=creatLink('/', 'Главная');?></li>
                <li><?=creatLink('/products/catalog.php', 'Каталог');?></li>
                <li><?=creatLink('/products/sticks/index.php', 'Клюшки');?></li>
                <li><?=creatLink('/products/blades/index.php', 'Крюки');?></li>
                <li><?=creatLink('/products/basic-collection.php', 'Базовый ассортимент');?></li>
            </ul>