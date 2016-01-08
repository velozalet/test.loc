//jQuery( document ).ready(function() {
//    alert(jsObject3.title); //alert(jsObject3.text);
//});

jQuery(document).ready(function () {
    var tpl = jQuery('#popup-box-1'); //в переменную tpl отдаем весь наш html-код PopUp-окна
    function explode() {     //console.log(jsObject3); //console.log(jsObject3.text);
        tpl.fadeIn();

// Если нету параметра время отображения PopUp-окна,то ничего не делаем, если есть, то ..
        if(jsObject3.time_show_popap === "") { }
        else {
            setTimeout(function(){ jQuery("#popup-box-1").hide('fast') }, jsObject3.time_show_popap*1000); //устанавливаем setTimeout для окна-PopUp(#popup-box-1) с исчезанием быстро(fast) и время через которое надо исчезнуть-передаем свой параметр
        }

//Ecли есть парам.'close_button'(наличие кнопки закрыть) отрисовываем div //
        if (parseInt(jsObject3.close_button) == 1) {  // alert('safas'); parseInt- парсит только число
            tpl.find('.close').css('display', 'block');
        } else { tpl.find('.close').css('display', 'none'); }

//Ecли есть парам.'close_popap_overlay'(значение закрывать окно по нажатию на область overlay),- устанавливаем ф-ю: jQuery('.overlay').on('click', function())- закрытия всего окна по клику
        if (parseInt(jsObject3.close_popap_overlay) == 1) {
            jQuery('.overlay').on('click', function () { // Он-клик событие на элементе ксласса(.close) - закрыть все PopUp-окно
                tpl.css('display', 'none'); //cкрываем все все PopUp-окно //Можно писать(обращатья) и так: jQuery(tpl).find('.overlay').css('display', 'none');
            });
        } else { }

//Ecли есть парам.'title'(Заголовок PopUp-окна),-передаем его в HTML-код.Нету,-выводит текст 'Ввведите заголовок окна PopUp в Админ Панели'
        if (jsObject3.title) {  // alert('safas');
            tpl.find('h2').text(jsObject3.title); //var title = jsObject3.title; -тогда эта строка:tpl.find('h2').text(title); //Заносим в переменную title значение из jsObject3.title(заголовок PopUp'a из переданного массива агрументов через(wp_localize_script))
        } else { tpl.find('h2').text('Ввведите заголовок окна PopUp в Админ Панели'); }

//Ecли есть парам.'text'(Текст(содержимое) PopUp-окна),-передаем его в HTML-код.Нету,-выводит текст 'Ввведите заголовок окна PopUp в Админ Панели'
        if (jsObject3.text) {  // alert('safas');
            tpl.find('.bottom').text(jsObject3.text); //var title = jsObject3.title; -тогда эта строка:tpl.find('h2').text(title); //Заносим в переменную title значение из jsObject3.title(заголовок PopUp'a из переданного массива агрументов через(wp_localize_script))
        } else { tpl.find('.bottom').text('Ввведите текст окна PopUp в Админ Панели'); }

    } //function explode__END

    if (jsObject3.start_time_popap === "") { // время задержки отображения появления PopUp-окна
    } else { setTimeout(explode, jsObject3.start_time_popap*1000); }

   //jQuery("#popup-box-1").show(1000);
  // jQuery("#popup-box-1").hide(6000);

    // будет периодически появляться снова в зависимости от парам.time_show_popap после того,как окно закрывают вручную
    //if (jsObject3.time_show_popap === "") { // время отображения PopUp-окна
    //} else { setInterval(explode, jsObject3.time_show_popap*1000); }

    // jQuery(".overlay").show(1000); jQuery("#popup-box-1").show(3000);
   // clearInterval(explode, jsObject3.time_show_popap*1000);

  jQuery('.close').on('click', function() { // Он-клик событие на элементе ксласса(.close) - закрыть все PopUp-окно
        tpl.fadeIn(); //Делает видимыми все элементы(tpl)
            // debugger;
        tpl.css('display', 'none'); //cкрываем все все PopUp-окно //Можно писать(обращатья) и так: jQuery(tpl).find('.overlay').css('display', 'none');
    });

}); // (document).ready(function()__END    clearInterval(intervalID);},2500)


// скрытие PopUp-окна по нажатию(Esc):
if (jsObject3.close_esc_key === "") { } // если нет параметра по кот.надо скрывать PopUp-окно(close_esc_key),- не делаем ничего
else { // иначе..
    jQuery(document).keydown(function(eventObject){
        if (eventObject. which == 27) //
            jQuery ('.overlay').hide();
    });
}

