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
?>переменную $result
    $result=$connect->query($sql);    
?>


<table border = 1>
  <tr><td>prop_title</td><td>prop_value</td><td>prop_value_view</td></tr>
  <?php while ($item = $result->fetch_object()):?>
  <tr><td><?= $item->prop_title?></td><td><?= $item->prop_value?></td><td><?= $item->prop_value_view?></td></tr>
  <?php endwhile; ?>
</table>




<?php 
require_once 'db.php';

$sql = "SELECT DISTINCT prop_title, prop_value, prop_value_view 
        FROM products, categories, properties, prod_prop
        WHERE products.category_id = categories.id AND properties.category_id = categories.id 
        AND prod_prop.product_id = products.id AND prod_prop.property_id = properties.id 
        AND prop_title LIKE 'hook' 
        ";
//выполняем запрос и результат кладём в переменную $result
$result=$connect->query($sql);    
var_dump($result)  
?>

<table border = 1>
  <tr><td>prop_title</td><td>prop_value</td><td>prop_value_view</td></tr>
  <?php while ($item = $result->fetch_object()):?>
  <tr><td><?= $item->prop_title?></td><td><?= $item->prop_value?></td><td><?= $item->prop_value_view?></td></tr>
  <?php endwhile; ?>
</table>



<?php
// $name = $_GET['name'] ?? 'Хват';
// $val = $_GET['value'] ?? 'Левый';

$sql = "SELECT * 
        FROM products, categories, brands, properties, prod_prop, prices, sizes
        WHERE products.category_id = categories.id AND properties.category_id = categories.id AND products.size_id = sizes.id
        AND prod_prop.product_id = products.id AND prod_prop.property_id = properties.id AND prices.product_id = products.id
        AND products.brand_id = brands.id 
        -- AND products.category_id = 3
        -- AND prop_title LIKE 'hook' AND prop_value LIKE 'Левый'
        -- HAVING prop_title LIKE 'Серия' AND prop_value LIKE 'BASIC COLLECTION'
        -- AND prop_title LIKE 'Хват крюка' AND prop_value LIKE 'Правый'
        ORDER BY article";
//выполняем запрос и результат кладём в переменную $result
$result=$connect->query($sql);    
var_dump($result)  
?>

<table border = 1>
  <tr><td>Артикул</td><td>Наименование</td><td>Размер</td><td>Бренд</td><td>категория</td><td>значение</td><td>значение</td><td>Цена</td></tr>
  <?php while ($item = $result->fetch_object()):?>
  <tr><td><?= $item->article?></td><td><?= $item->title?></td><td><?= $item->size_value?></td><td><?= $item->brand?></td><td><?= $item->category?></td><td><?= $item->prop_title?></td><td><?= $item->prop_value?></td><td><?= $item->price_regular?></td></tr>
  <?php endwhile; ?>
</table>