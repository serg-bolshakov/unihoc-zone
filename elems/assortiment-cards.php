            <div class="assortiment-card">
            <?php while ($item = $products->fetch_object()):?> 
                <div class="assortiment-card__block">
                    <a href = "<?=$item->prod_url_semantic?>"><img src="/<?= $item->img_link?>" alt="<?= $item->category?> <?= $item->brand?> <?= $item->model ?> <?= $item->marka ?>" title="<?= $item->category?> <?= $item->brand?> <?= $item->model ?> <?= $item->marka ?>"></a>
                    <div class="assortiment-card_productName"><?= $item->title?> </div>
                    <div class="assortiment-card_productPrice">
                    
                    <?php
                        /* если не назначена акция (специальная цена, 
                        то полный блок цен не выводится, его пропускаем - 
                        выводится только актуальная цена (следующий блок)*/
                    ?>

                    <?php if ($item->price_special): ?> 
                        <p class="priceCurrentSale"><nobr><?= number_format($price= $item->price_special, 0,",", " " ); ?> <sup>&#8381;</sup></nobr></p>
                        <p class="priceBeforSale"><nobr><?= number_format($price= $item->price_regular, 0,",", " " ); ?> <sup>&#8381;</sup></nobr></p>
                        <p class="priceDiscountInPercentage"><nobr>- <?= $discount = ceil(100 - ($price= $item->price_special) / ($price= $item->price_regular) * 100); ?>&#37;</nobr></p>
                    <?php else: ?>
                        <p class="priceCurrent"><nobr><?= number_format($price= $item->price_regular, 0,",", " " ); ?> <sup>&#8381;</sup></nobr></p>
                    <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
            </div>