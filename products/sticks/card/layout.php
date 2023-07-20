<?php require_once $_SERVER['DOCUMENT_ROOT'].'/elems/head.php'; ?>
</head>

<body>
  <div class = "main-container">

    <?php require_once $_SERVER['DOCUMENT_ROOT'] ."/elems/header.php"; ?>

    <section class="nav-bar">
          <?php include $_SERVER['DOCUMENT_ROOT'].'/elems/nav-bar.php'?>
    </section>

    <div class = "cardProduct-line__block"> 
      <div class = "cardProduct-block__title">    
          <h1><?= $item->title ?> </h1>
      </div>
      <div class="cardProduct-block__logo-article">
          <img class="cardProduct-logo" src="<?= $item->url?>"  alt="<?= $item->brand_view?>. logo" title ="<?= $item->brand_view?>. logo">
          <p class="cardProduct-article__title">Артикульный<br>номер:</p>
          <p class="cardProduct-article__number"><?= $item->article?></p>
      </div>
    </div>

    <div class = "cardProduct-line__block">
      <?php if ($imageMain): ?> 
      <a href="/<?= $imageMain->img_link?>">
      <img class="cardProduct-mainImg" src="/<?= $imageMain->img_link?>" alt="<?= $item->category?> <?= $item->brand?> <?= $item->model ?> <?= $item->marka ?>" title ="Кликни на изображение, чтобы посмотреть его на всём экране.">
      </a>
      <?php else: ?>
      <a href="/<?= $item->img_link?>">
        <img class="cardProduct-mainImg" src="/<?= $item->img_link?>" <?= $item->category?> <?= $item->brand?> <?= $item->model ?> <?= $item->marka ?>">
      </a>
      <?php endif; ?>

      <div class="cardProduct-props">
        <div class="cardProduct-props__item">
            <div class="cardProduct-props__item-hook-title pop-up__cardProduct-hint">Хват (игровая сторона): 
                <div class="pop-up__cardProduct-hint-text">
                    <?=include $_SERVER['DOCUMENT_ROOT'] . "/elems/articles/hook-side.php";?>
                </div>
            </div>
            <div class="cardStick-props__item-hook-left">Левый</div>
            <div class="cardStick-props__item-hook-right">Правый</div>
        </div>
        <div class="cardProduct-props__item">
          <div class="cardStick-props__item-shaftLength-title pop-up__cardProduct-hint">Длина рукоятки (см):
            <div class="pop-up__cardProduct-hint-text">
                <?=include $_SERVER['DOCUMENT_ROOT'] . "/elems/articles/shaft-length.php";?>
            </div>
          </div>
          <div class="cardStick-shaftLength__item cardStick-shaftLength__item-missing">87</div>
          <div class="cardStick-shaftLength__item cardStick-shaftLength__item-missing">92</div>
          <div class="cardStick-shaftLength__item">96</div>
          <div class="cardStick-shaftLength__item cardStick-shaftLength__item-missing">100</div>
          <div class="cardStick-shaftLength__item cardStick-shaftLength__item-missing">104</div>
        </div>

          <div class="cardProduct-productPrice__block">   
            <div>Лучшая цена</div>
              <?php if ($item->price_special): ?> 
            <div class="cardProduct-priceCurrentSale"><nobr><?= number_format($price= $item->price_special, 0,",", " " ); ?> <sup>&#8381;</sup></nobr></div>
            <div class="cardProduct-priceBeforSale"><nobr><?= number_format($price= $item->price_regular, 0,",", " " ); ?> <sup>&#8381;</sup></nobr></div>
            <div class="cardProduct-priceDiscountInPercentage"><nobr>- <?= $discount = ceil(100 - ($price= $item->price_special) / ($price= $item->price_regular) * 100); ?>&#37;</nobr></div>
              <?php else: ?>
            <div class="cardProduct-priceCurrent"><nobr><?= number_format($price= $item->price_regular, 0,",", " " ); ?> <sup>&#8381;</sup></nobr></div>
              <?php endif; ?>
              <?php if ($item->date_end): ?> 
            <div class="cardProduct-priceValidPeriod">действует до: <?= $item->date_end?></div>
              <?php endif; ?>
          </div>
      </div>
    </div>

    <div class="cardProduct-descDetails__block"> 
      <div class="cardProduct-props">  
        <?php if ($item->prod_desc): ?>
        <div class = "cardProduct-description"><?=$item->prod_desc?></div>
        <?php endif; ?>
        <div class="cardProduct-productTarget">   
          <span><b>Кому идеально подойдёт эта клюшка?</b><br>Просматриваемая модель с длиной рукоятки <?=$item->size_value?><?=$item->size_unit?> 
          <?=$item->size_recommendation?>, играющих <?=$propHook->prop_description?></span>
        </div>
      </div>
      <div class = "cardProduct-details">
        <div class = "cardProduct-details__title">
          <p>Спецификация товара</p>
        </div>
          
        <?php if ($item->weight): ?>
        <div class = "cardProduct-detail__name">Вес клюшки (г):</div>
        <div class = "cardProduct-detail__value"><?= $item->weight?></div>
        <?php endif; ?>
                
        <?php if ($item->material): ?>
        <div class = "cardProduct-detail__name">Материал рукоятки:</div>
        <div class = "cardProduct-detail__value"><?= $item->material?></div>
        <?php endif; ?>
                
        <?php if ($propFlex): ?>
        <div class="cardProduct-detail__name pop-up__cardProduct-hint">Жёсткость рукоятки (индекс, мм):
          <div class="pop-up__cardProduct-hint-text">
          <?=include $_SERVER['DOCUMENT_ROOT'] . "/elems/articles/shaft-flex.php";?>
          </div>
        </div>
        <div class = "cardProduct-detail__value"><?= $propFlex->prop_value_view?></div>
        <?php endif; ?>

        <?php if ($propProfile): ?>
        <div class = "cardProduct-detail__name">Профиль рукоятки:</div>
        <div class = "cardProduct-detail__value"><?= $propProfile->prop_value_view?></div>
        <?php endif; ?>

        <?php if ($propGrip): ?>
        <div class = "cardProduct-detail__name">Тип обмотки:</div>
        <div class = "cardProduct-detail__value"><?= $propGrip->prop_value_view?></div>
        <?php endif; ?>

        <?php if ($propBlade): ?>
        <div class = "cardProduct-detail__name">Модель крюка:</div>
        <div class = "cardProduct-detail__value"><?= $propBlade->prop_value_view?></div>
        <?php endif; ?>
    
        <?php if ($item->iff): ?>
        <div class="cardProduct-detail__name pop-up__cardProduct-hint">Наличие сертификата IFF:
            <div class="pop-up__cardProduct-hint-text">
            <?=include $_SERVER['DOCUMENT_ROOT'] . "/elems/articles/iff.php";?>
            </div>
        </div>
        <div class = "cardProduct-detail__value"><?= $item->iff?></div>
        <?php endif; ?>

        <?php if ($propSeries): ?>
        <div class = "cardProduct-detail__name">Специальное направление:</div>
        <div class = "cardProduct-detail__value"><?= $propSeries->prop_value_view?></div>
        <?php endif; ?>

        <?php if ($propCollection): ?>
        <div class = "cardProduct-detail__name">Коллекция:</div>
        <div class = "cardProduct-detail__value"><?= $propCollection->prop_value_view?></div>
        <?php endif; ?>

        <?php if ($prodStatus): ?>
        <div class="card-product__detail-status-block">
          <?php if ($prodStatus->in_stock): ?>
          <div class = "card-product__detail-status">В наличии. </div>
          <?php endif; ?>
          <?php if ($prodStatus->on_sale): ?>
          <div class = "card-product__detail-status">Продаётся. </div>
          <?php endif; ?>
          <?php if ($prodStatus->reserved): ?>
          <div class = "card-product__detail-status">В резерве. </div>
          <?php endif; ?>
          <?php if ($prodStatus->on_order): ?>
          <div class = "card-product__detail-status">На заказ. </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>  

    <div class = "cardProduct-imgPromo">
        <?php while ($imagePromo = $resultImagePromo->fetch_object()):?> 
        <a href="/<?= $imagePromo->img_link?>">
            <img src="/<?= $imagePromo->img_link?>" alt="<?= $item->category?> <?= $item->brand?> <?= $item->model ?> <?= $item->marka ?>" title ="Кликни на изображение, чтобы посмотреть его на всём экране.">
        </a>
        <?php endwhile; ?>
    </div>  

    <?php require_once $_SERVER['DOCUMENT_ROOT'] ."/elems/footer.php"; ?>
    
    </div>
  </body>
</html>