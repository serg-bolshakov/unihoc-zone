<?php require_once $_SERVER['DOCUMENT_ROOT'].'/elems/head.php'; ?>
</head>
<body>
    <container class="container-blade">
        <?php require_once $_SERVER['DOCUMENT_ROOT'].'/elems/header.php'; ?>    
               
        <section class="nav-bar">
            <?php include $_SERVER['DOCUMENT_ROOT'].'/elems/nav-bar.php'?>
        </section>
        
        <div class="card-blade">
            <h1><?= $item->title ?> </h1>
            <img class="card-blade__logo" src="<?= $item->url?>"  alt="<?= $item->brand?>" title ="<?= $item->brand?> LOGO">
            <p class="card-blade__article">Артикульный<br>номер:</p>
            <p class="card-blade__article-number"><?= $item->article?></p>
            
            <?php if ($imageMain): ?> 
                <a class="card-blade__main-img" href="/<?= $imageMain->img_link?>">
                <img class="card-blade__main-img" src="/<?= $imageMain->img_link?>" alt="<?= $item->category?> <?= $item->brand?> <?= $item->model ?> <?= $item->marka ?>" title ="<?= $item->category?> <?= $item->brand?> <?= $item->model ?> <?= $item->marka ?>">
                </a>
            <?php else: ?>
                <a class="card-blade__main-img" href="/<?= $item->img_link?>">
                    <img class="card-blade__main-img" src="/<?= $item->img_link?>"  title ="<?= $item->category?> <?= $item->brand?> <?= $item->model ?> <?= $item->marka ?>">
                </a>
            <?php endif; ?>
            <div class="card-blade__hook pop-up__card-blade-hint">Хват (игровая сторона)
                <div class="pop-up__card-blade-hint-text">
                    <?=include $_SERVER['DOCUMENT_ROOT'] . "/elems/articles/hook-side.php";?>
                </div>
            </div>
            <div class="card-blade__hook-left">Левый</div>
            <div class="card-blade__hook-right">Правый</div>
            
            <div class = "card-blade__details">
                <div class = "card-blade__details-title">
                    <p>Спецификация товара</p>
                </div>
                
            <?php if ($item->weight): ?>
                <div class = "card-blade__detail-name">Вес (г):</div>
                <div class = "card-blade__detail-value"><?= $item->weight?></div>
            <?php endif; ?>
                
            <?php if ($item->material): ?>
                <div class = "card-blade__detail-name">Материал:</div>
                <div class = "card-blade__detail-value"><?= $item->material?></div>
            <?php endif; ?>
                
            <?php if ($propFlex): ?>
                <div class="card-blade__detail-name pop-up__card-blade-hint">Степень жёсткости:
                    <div class="pop-up__card-blade-hint-text">
                    <?=include $_SERVER['DOCUMENT_ROOT'] . "/elems/articles/blade-stiffness.php";?>
                    </div>
                </div>
                <div class = "card-blade__detail-value"><?= $propFlex->prop_value_view?></div>
            <?php endif; ?>

            <?php if ($item->iff): ?>
                <div class="card-blade__detail-name pop-up__card-blade-hint">Наличие сертификата IFF:
                    <div class="pop-up__card-blade-hint-text">
                    <?=include $_SERVER['DOCUMENT_ROOT'] . "/elems/articles/iff.php";?>
                    </div>
                </div>
                <div class = "card-blade__detail-value"><?= $item->iff?></div>
            <?php endif; ?>

            
                <div class="card-blade__detail-status-block">
                    <?php if ($item->in_stock): ?>
                    <div class = "card-blade__detail-status">В наличии. </div>
                    <?php endif; ?>
                    <?php if ($item->on_sale): ?>
                    <div class = "card-blade__detail-status">Продаётся. </div>
                    <?php endif; ?>
                    <?php if ($item->reserved): ?>
                    <div class = "card-blade__detail-status">В резерве. </div>
                    <?php endif; ?>
                    <?php if ($item->on_order): ?>
                    <div class = "card-blade__detail-status">На заказ. </div>
                    <?php endif; ?>
                </div>
            
            </div>
   
            <?php if ($item->prod_desc): ?>
                <div class = "card-blade__description"><?=$item->prod_desc?></div>
            <?php endif; ?>

            <div class="card-blade_productPrice-block">   
            <p>Лучшая цена</p>
            <?php if ($item->price_special): ?> 
                <p class="card-blade_priceCurrentSale"><nobr><?= number_format($price= $item->price_special, 0,",", " " ); ?> <sup>&#8381;</sup></nobr></p>
                <p class="card-blade_priceBeforSale"><nobr><?= number_format($price= $item->price_regular, 0,",", " " ); ?> <sup>&#8381;</sup></nobr></p>
                <p class="card-blade_priceDiscountInPercentage"><nobr>- <?= $discount = ceil(100 - ($price= $item->price_special) / ($price= $item->price_regular) * 100); ?>&#37;</nobr></p>
            <?php else: ?>
                <p class="card-blade_priceCurrent"><nobr><?= number_format($price= $item->price_regular, 0,",", " " ); ?> <sup>&#8381;</sup></nobr></p>
            <?php endif; ?>
            <?php if ($item->date_end): ?> 
            <p class="card-blade__price-valid-period">действует до: <?= $item->date_end?></p>
            <?php endif; ?>
            </div>

        </div>
        
    <?php require_once $_SERVER['DOCUMENT_ROOT'].'/elems/footer.php'; ?>

