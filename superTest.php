<?php

require_once 'db.php';
$prodCard = "SELECT id FROM products
WHERE marka = 'SNIPER'
";
$resultprodCard = $connect->query($prodCard);
// $imagePromo = $result->fetch_object();

var_dump($resultprodCard);

?>
<table border = 1>
  <tr><td>Id</td><td>Наименование</td><td>Бренд</td><td>ссылка</td><td>значение</td><td>значение</td><td>Цена</td></tr>
  <?php while ($prodCard = $resultprodCard->fetch_object()):?>
  <tr><td><?= $prodCard->id?></td></tr>
  <?php endwhile; ?>
</table>

<?php
$hook_blade = "";
$blade_stiffness = "";
$brand = "";

  $whereFromProdProp = "1";
if (!empty($hook_blade) || !empty($blade_stiffness)){
    if ($hook_blade === "") {
        $hook_blade = "1) OR (1";
    }
    if ($blade_stiffness === "") {
        $blade_stiffness = "1) OR (1";
        }
        
    $whereFromProdProp = <<<SQLWHERE
    property_id IN (
    SELECT id FROM properties WHERE (prop_title IN ('hook_blade', 'blade_stiffness') 
     AND prop_value IN ('right')) 
    )
SQLWHERE;
}
var_dump($whereFromProdProp);
  $whereFromBrand = "1";
if (!empty($brand)){
  $whereFromBrand = <<<SQLWHERE
  brand_id IN (
  SELECT id FROM brands WHERE (brand IN ($brand))
  )
SQLWHERE; // получаем такую строку: string(75) "brand_id IN ( SELECT id FROM brands WHERE (brand IN ("UNIHOC")) )"
}
    

//делаем запрос свойств
$prop = "SELECT id FROM properties 
          WHERE (prop_title IN ('hook_blade', 'blade_stiffness') AND prop_value IN ('right', 'hard')) 
            
            -- AND (prop_title = 'blade_stiffness' AND prop_value IN ('medium'))
            -- AND (prop_title = 'blade_stiffness' AND prop_value IN (1) OR (1))
";
$result = $connect->query($prop);
?>
<table border = 1>
  <tr><td>Id</td><td>Наименование</td><td>Бренд</td><td>ссылка</td><td>значение</td><td>значение</td><td>Цена</td></tr>
  <?php while ($item = $result->fetch_object()):?>
  <tr><td><?= $item->id?></td><td><?= $item->prop_title?></td><td><?= $item->prop_value?></td></tr>
  <?php endwhile; ?>
</table>

<?php
    //делаем запрос, который посчитает количество записей в БД
$sql = "SELECT COUNT(DISTINCT p.id) as count FROM products p
LEFT JOIN prod_prop pp on p.id = pp.product_id
LEFT JOIN brands b on p.brand_id = b.id
LEFT JOIN images i on p.id = i.product_id
LEFT JOIN prices p2 on p.id = p2.product_id
WHERE
    p.category_id = 2


AND $whereFromBrand
";
$result = $connect->query($sql);
$count = $result->fetch_assoc()['count']; //и в переменную $count запишем сразу число по ключу ['count'], а не массив
//считаем количество страниц
var_dump($count);
// $pagesCount = ceil($count / $notesOnPage);

$select = <<<SQL
    SELECT DISTINCT p.*, b.*, i.*, p2.*  FROM products p
    LEFT JOIN categories c on p.category_id = c.id
    RIGHT JOIN prod_prop pp on p.id = pp.product_id
    LEFT JOIN brands b on p.brand_id = b.id
    LEFT JOIN images i on p.id = i.product_id
    LEFT JOIN prices p2 on p.id = p2.product_id
    WHERE
        p.category_id = 2
    AND img_showcase = true
    AND property_id IN ( SELECT id FROM properties WHERE (prop_title IN ('hook_blade', 'blade_stiffness') AND prop_value IN ('right', 'medium') 
    ))
    AND $whereFromBrand
    -- LIMIT $notesOnPage OFFSET $fromNewPageStart
SQL;
    
//$sql = $select;
//выполняем запрос и результат кладём в переменную $products
//$products = $connect->query($sql);

//выполняем запрос и результат кладём в переменную $products
$product = $connect->query($select);
?>   

<table border = 1>
  <tr><td>Артикул</td><td>Наименование</td><td>Бренд</td><td>ссылка</td><td>значение</td><td>значение</td><td>Цена</td></tr>
  <?php while ($item = $product->fetch_object()):?>
  <tr><td><?= $item->article?></td><td><?= $item->title?></td><td><?= $item->brand?></td><td><?= $item->img_link?></td><td><?= $item->prop_title?></td><td><?= $item->prop_value?></td><td><?= $item->price_regular?></td></tr>
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

$sql = "SELECT DISTINCT * FROM products
          LEFT JOIN categories on products.category_id = categories.id
          LEFT JOIN brands on products.brand_id = brands.id
          RIGHT JOIN images on images.product_id = products.id
          RIGHT JOIN properties on properties.category_id = categories.id
          RIGHT JOIN prod_prop on prod_prop.product_id = products.id
          RIGHT JOIN prices on prices.product_id = products.id 
        WHERE  prod_prop.property_id = properties.id 
          AND products.category_id = 2
          AND img_showcase = true
          AND properties.id = 71 
          -- AND properties.id = 74
          -- AND prop_title LIKE 'hook_blade' AND prop_value LIKE 'right'
          AND prop_title LIKE 'blade_stiffness'
        ";
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