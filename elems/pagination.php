            <div class = "pagination">
<?php
        $params = $_GET; // в переменную $params кладётся массив $_GET - Ассоциативный массив параметров, переданных скрипту через URL.
        // array(1) { ["page"]=> string(1) "3" } (когда выбрали для просмотра третью страницу)
        // array(1) { ["brand"]=> array(1) { [0]=> string(14) "ZONEFLOORBALL." } } - когда выбрали в фильтре бренд...
            
        unset($params['page']); // результат: array(0) { } 
        // unset() удаляет перечисленные переменные.
        // Если переменная, объявленная глобальной, удаляется внутри функции, то будет удалена только локальная переменная. 
        //Переменная в области видимости вызова функции сохранит то же значение, что и до вызова unset().
        // удаляем один элемент массива: unset($bar['quux']);

        // var_dump($params); - array(2) { ["shaft_flex"]=> array(4) { [0]=> string(2) "36" [1]=> string(2) "30" [2]=> string(2) "29" 
        // [3]=> string(2) "27" } ["brand"]=> array(1) { [0]=> string(6) "UNIHOC" } }

        $query = http_build_query($params); //http_build_query - Генерирует URL-кодированную строку запроса
        //var_dump($query); string(27) "brand%5B0%5D=ZONEFLOORBALL."
        //var_dump($query); - string(103) "shaft_flex%5B0%5D=36&shaft_flex%5B1%5D=30&shaft_flex%5B2%5D=29&shaft_flex%5B3%5D=27&brand%5B0%5D=UNIHOC"   

        // проверяем для стрелочек влево, что мы на первой странице ... если на первой стрелки деактивируются, но остаются видимыми
        if ($page != 1){
            $prev = $page - 1; //предыдущая страница
            echo "<a href=\"?page=$prev&{$query}\">&lt;&lt; </a> "; // я до этого делал без {&$query} - это работате при работе фильтров!!! а без этого - НЕ РАБОТАЕТ!!!
        } else {
            echo "&lt;&lt; ";
        }
        
        //запускаем цикл для ссылок на страницы
        for ($i = 1; $i <= $pagesCount; $i++) {
            if ($page == $i) {
                echo "<a href=\"?page=$i&{$query}\" class=\"activeProduct\">$i</a> ";
            } else echo "<a href=\"?page=$i&{$query}\">$i</a> ";
        }
                
        if ($page != $pagesCount) {
            $next = $page + 1; //следующая страница
            echo "<a href=\"?page=$next&{$query}\"> &gt;&gt;</a> ";
        } else {
            echo " &gt;&gt;";
        }         
    ?>    

        </div>
        