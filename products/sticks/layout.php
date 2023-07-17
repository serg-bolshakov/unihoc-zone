<?php require_once $_SERVER['DOCUMENT_ROOT'].'/elems/head.php'; ?>
</head>

<body>
    <div class="container-products">
        <?php require_once $_SERVER['DOCUMENT_ROOT'].'/elems/header.php'; ?>    
        <section class="content-products">
            <div class="products-type">
                <img
                    src="/images/main/sticks.jpg"
                    alt="sticks-title_banner"
                />
                <div class="products-item product-title">
                    <h1>Флорбольные клюшки</h1>
                </div>
            </div>  
        </section>
        
        <section class="nav-bar">
            <?php include $_SERVER['DOCUMENT_ROOT'].'/elems/nav-bar.php'?>
        </section>
        
        <content class = "products-content">
        <?php require_once $_SERVER['DOCUMENT_ROOT'].'/products/sticks/aside_with_filters.php'?>

    
        <section class="assortiment-cards">
        <?php require_once $_SERVER['DOCUMENT_ROOT'].'/elems/assortiment-cards.php'?>           
        </section>
        
        </content>

        <?php /*если количество страниц больше, чем 1 - запускаем пагинацию страниц*/if($pagesCount > 1): ?>
        <section class="pagination-products">
            <?php include $_SERVER['DOCUMENT_ROOT'].'/elems//pagination.php'?>
        </section>
        <?php endif; ?>
        
        <?php require_once $_SERVER['DOCUMENT_ROOT'].'/elems/footer.php'; ?>

        </div>
    </body>
</html>