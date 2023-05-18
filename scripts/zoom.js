//переменные, которые отвечают за позицию курсора
let globalX = 0;
let globalY = 0;

$(document).on('mousemove', function(e) {
    globalX = e.pageX;
    globalY = e.pageY;
    console.log(globalX, globalY);
});

// делаем переменные каждого блока, курсора и потом будем просчитывать

$('zoom-img-item').on('mousemove', function(){
    let img = $(this).attr('href'); //получим картинку как ссылку
    let imgBlock = $(this).find('img'); // создадим блок
    let imgWidth = imgBlock.width();
    let imgHeight = imgBlock.height();
    let overlay = $('.zoom-img-overlay');
    let cursor = $('.zoom-img-cursor'); 
    let cursorWidth = cursor.outerWidth(); //будем использовать общую ширину (у курсора есть рамка)
    let cursorHeight = cursor.outerHeight(); //то же самое с высотой
    let posX = globalX - $(this).offset().left - cursorWidth / 2;
    let posY = globalY - $(this).offset().top - cursorHeight / 2;

    cursor.css('left', posX + 'px');
    cursor.css('top', posY + 'px');
    cursor.show();

})

