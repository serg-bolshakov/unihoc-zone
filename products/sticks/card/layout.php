<?php require_once $_SERVER['DOCUMENT_ROOT'].'/elems/head.php'; ?>
</head>
<body>
    <container class="container-stick">
        <?php require_once $_SERVER['DOCUMENT_ROOT'].'/elems/header.php'; ?>    
               
        <section class="nav-bar">
            <?php include $_SERVER['DOCUMENT_ROOT'].'/elems/nav-bar.php'?>
        </section>
        
        <div class="card-stick">
            <h1><?= $item->title ?> </h1>
            <img class="card-stick__logo" src="<?= $item->url?>"  alt="<?= $item->brand?>" title ="<?= $item->description?> LOGO">
            <p class="card-stick__article">Артикульный<br>номер:</p>
            <p class="card-stick__article-number"><?= $item->article?></p>
            
            <?php if ($imageMain): ?> 
                <a class="card-stick__main-img" href="/<?= $imageMain->img_link?>">
                <img class="card-stick__main-img" src="/<?= $imageMain->img_link?>" alt="<?= $item->category?> <?= $item->brand?> <?= $item->model ?> <?= $item->marka ?>" title ="<?= $item->category?> <?= $item->brand?> <?= $item->model ?> <?= $item->marka ?>">
                </a>
            <?php else: ?>
                <a class="card-stick__main-img" href="/<?= $item->img_link?>">
                    <img class="card-stick__main-img" src="/<?= $item->img_link?>"  title ="<?= $item->category?> <?= $item->brand?> <?= $item->model ?> <?= $item->marka ?>">
                </a>
            <?php endif; ?>
            <div class="card-stick__hook pop-up__card-stick-hint">Хват (игровая сторона)
                <div class="pop-up__card-stick-hint-text">
                    <?=include $_SERVER['DOCUMENT_ROOT'] . "/elems/articles/hook-side.php";?>
                </div>
            </div>
            <div class="card-stick__hook-left">Левый</div>
            <div class="card-stick__hook-right">Правый</div>
            
            <div class="card-stick__shaft-length pop-up__card-stick-hint">Длина рукоятки (см)
                <div class="pop-up__card-stick-hint-text">
                    <?=include $_SERVER['DOCUMENT_ROOT'] . "/elems/articles/shaft-length.php";?>
                </div>
            </div>
            <div class="card-stick__shaft-length-items">
                <div class="card-stick__shaft-length-item card-stick__shaft-length-item-missing">87</div>
                <div class="card-stick__shaft-length-item card-stick__shaft-length-item-missing">92</div>
                <div class="card-stick__shaft-length-item">96</div>
                <div class="card-stick__shaft-length-item card-stick__shaft-length-item-missing">100</div>
                <div class="card-stick__shaft-length-item card-stick__shaft-length-item-missing">104</div>
            </div>
            <div class = "card-stick__details">
                <div class = "card-stick__details-title">
                    <p>Спецификация товара</p>
                </div>
                
            <?php if ($item->weight): ?>
                <div class = "card-stick__detail-name">Вес клюшки (г):</div>
                <div class = "card-stick__detail-value"><?= $item->weight?></div>
            <?php endif; ?>
                
            <?php if ($item->material): ?>
                <div class = "card-stick__detail-name">Материал рукоятки:</div>
                <div class = "card-stick__detail-value"><?= $item->material?></div>
            <?php endif; ?>
                
            <?php if ($propFlex): ?>
                <div class="card-stick__detail-name pop-up__card-stick-hint">Жёсткость рукоятки (индекс, мм):
                    <div class="pop-up__card-stick-hint-text">
                    <?=include $_SERVER['DOCUMENT_ROOT'] . "/elems/articles/shaft-flex.php";?>
                    </div>
                </div>
                <div class = "card-stick__detail-value"><?= $propFlex->prop_value_view?></div>
            <?php endif; ?>

            <?php if ($propProfile): ?>
                <div class = "card-stick__detail-name">Профиль рукоятки:</div>
                <div class = "card-stick__detail-value"><?= $propProfile->prop_value_view?></div>
            <?php endif; ?>

            <?php if ($propGrip): ?>
                <div class = "card-stick__detail-name">Тип обмотки:</div>
                <div class = "card-stick__detail-value"><?= $propGrip->prop_value_view?></div>
            <?php endif; ?>

            <?php if ($propBlade): ?>
                <div class = "card-stick__detail-name">Модель крюка:</div>
                <div class = "card-stick__detail-value"><?= $propBlade->prop_value_view?></div>
            <?php endif; ?>
            
            <?php if ($item->iff): ?>
                <div class="card-stick__detail-name pop-up__card-stick-hint">Наличие сертификата IFF:
                    <div class="pop-up__card-stick-hint-text">
                    <?=include $_SERVER['DOCUMENT_ROOT'] . "/elems/articles/iff.php";?>
                    </div>
                </div>
                <div class = "card-stick__detail-value"><?= $item->iff?></div>
            <?php endif; ?>

            <?php if ($propSeries): ?>
                <div class = "card-stick__detail-name">Специальное направление:</div>
                <div class = "card-stick__detail-value"><?= $propSeries->prop_value_view?></div>
            <?php endif; ?>

            <?php if ($propCollection): ?>
                <div class = "card-stick__detail-name">Коллекция:</div>
                <div class = "card-stick__detail-value"><?= $propCollection->prop_value_view?></div>
            <?php endif; ?>

            <?php if ($prodStatus): ?>
                <div class="card-stick__detail-status-block">
                    <?php if ($prodStatus->in_stock): ?>
                    <div class = "card-stick__detail-status">В наличии. </div>
                    <?php endif; ?>
                    <?php if ($prodStatus->on_sale): ?>
                    <div class = "card-stick__detail-status">Продаётся. </div>
                    <?php endif; ?>
                    <?php if ($prodStatus->reserved): ?>
                    <div class = "card-stick__detail-status">В резерве. </div>
                    <?php endif; ?>
                    <?php if ($prodStatus->on_order): ?>
                    <div class = "card-stick__detail-status">На заказ. </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            </div>
   
            <?php if ($item->prod_desc): ?>
                <div class = "card-stick__description"><?=$item->prod_desc?></div>
            <?php endif; ?>

            <div class="card-stick_productPrice-block">   
            <p>Лучшая цена</p>
            <?php if ($item->price_special): ?> 
                <p class="card-stick_priceCurrentSale"><nobr><?= number_format($price= $item->price_special, 0,",", " " ); ?> <sup>&#8381;</sup></nobr></p>
                <p class="card-stick_priceBeforSale"><nobr><?= number_format($price= $item->price_regular, 0,",", " " ); ?> <sup>&#8381;</sup></nobr></p>
                <p class="card-stick_priceDiscountInPercentage"><nobr>- <?= $discount = ceil(100 - ($price= $item->price_special) / ($price= $item->price_regular) * 100); ?>&#37;</nobr></p>
            <?php else: ?>
                <p class="card-stick_priceCurrent"><nobr><?= number_format($price= $item->price_regular, 0,",", " " ); ?> <sup>&#8381;</sup></nobr></p>
            <?php endif; ?>
            <?php if ($item->date_end): ?> 
            <p class="card-stick__price-valid-period">действует до: <?= $item->date_end?></p>
            <?php endif; ?>
            </div>

        </div>
        
    <?php require_once $_SERVER['DOCUMENT_ROOT'].'/elems/footer.php'; ?>

