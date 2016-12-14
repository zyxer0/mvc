$(function(){
    $('.catalog .category_item .switch').on('click', function(){
        $(this).closest('li').children('ul').slideToggle(200);
    });
    
    $('.category_item.selected').parents('ul').show();
    $('#slider').slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        ZautoplaySpeed: 2000,
    });
});
