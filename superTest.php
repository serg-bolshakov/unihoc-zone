<?php
$whereFromProdProp = "1";
$whereFromSize = "1";
$whereFromBrand = "1";
$hook = "1) OR (1";
  


  require_once 'db.php';
  $sql = "SELECT DISTINCT p.*, b.*, i.*, p2.* FROM products p
  LEFT JOIN prod_prop pp on p.id = pp.product_id
  LEFT JOIN brands b on p.brand_id = b.id
  LEFT JOIN images i on p.id = i.product_id
  
  LEFT JOIN prices p2 on p.id = p2.product_id
  WHERE
      p.category_id = 2
  -- AND $whereFromProdProp
  -- AND $whereFromSize
  -- AND $whereFromBrand
  -- AND WHERE (prop_title = 'hook' AND prop_value IN 'left')
  ";

  $result = $connect->query($sql);
  $count = $result->fetch_assoc()['count']; //и в переменную $count запишем сразу число по ключу ['count'], а не массив
  //считаем количество страниц
  
  var_dump($result);

?>

<table border = 1>
  <tr><td>Артикул</td><td>Наименование</td><td>Размер</td><td>Бренд</td><td>категория</td><td>значение</td><td>значение</td><td>Цена</td></tr>
  <?php while ($item = $result->fetch_object()):?>
  <tr><td><?= $item->article?></td><td><?= $item->title?></td><td><?= $item->size_value?></td><td><?= $item->brand?></td><td><?= $item->img_link?></td><td><?= $item->prop_title?></td><td><?= $item->prop_value?></td><td><?= $item->price_regular?></td></tr>
  <?php endwhile; ?>
</table>

<?php
require_once 'db.php';
$sql = "SELECT DISTINCT size_title, size_value
  FROM products
  LEFT JOIN sizes ON products.size_id = sizes.id 
";
$result=$connect->query($sql);    
?>

<table border = 1>
  <tr><td>size_title</td><td>size_value</td></tr>
  <?php while ($item = $result->fetch_object()):?>
  <tr><td><?= $item->size_title?></td><td><?= $item->size_value?></td></tr>
  <?php endwhile; ?>
</table>


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
        FROM products, categories, brands, properties, prod_prop, prices, sizes, images
        WHERE products.category_id = categories.id AND properties.category_id = categories.id AND products.size_id = sizes.id
        AND prod_prop.product_id = products.id AND prod_prop.property_id = properties.id AND prices.product_id = products.id
        AND products.brand_id = brands.id AND products.id = images.product_id
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