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
        