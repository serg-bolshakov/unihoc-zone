<?php require_once $_SERVER['DOCUMENT_ROOT'].'/elems/head.php'; ?>
</head>

<body>
    <container class="container-products">
        <?php require_once $_SERVER['DOCUMENT_ROOT'].'/elems/header.php'; ?>    
        <section class="content-products">
            <div class="products-type">
                <img
                    src="/images/main/sticks.jpg"
                    alt="blades-title_banner"
                />
                <div class="products-item product-title">
                    <h1>Флорбольные клюшки</h1>
                </div>
            </div>  
        </section>
        
        <section class="nav-bar">
            <?php include $_SERVER['DOCUMENT_ROOT'].'/elems/nav-bar.php'?>
        </section>
        
        <?php require_once $_SERVER['DOCUMENT_ROOT'].'/products/sticks/aside_with_filters.php'?>
       
        <section class="assortiment-cards">
            <?php include $_SERVER['DOCUMENT_ROOT'].'/elems/assortiment-cards.php'?>
        </section>
            
        <?php /*если количество страниц больше, чем 1 - запускаем пагинацию страниц*/if($pagesCount > 1): ?>
        <section class="pagination-products">
            <?php include $_SERVER['DOCUMENT_ROOT'].'/elems//pagination.php'?>
        </section>
        <?php endif; ?>
        
        <?php require_once $_SERVER['DOCUMENT_ROOT'].'/elems/footer.php'; ?>