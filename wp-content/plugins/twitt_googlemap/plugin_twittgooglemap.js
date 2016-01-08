/*
jQuery( document ).ready(function() {
console.log(jsObj1.radius_twitt_gmap);
});
*/
//______________________________________________________________________________________________________________________
jQuery(document).ready(function() {

var markers = []; // инициализируем массив для маркеров
var radiuses = []; // инициализируем массив для области радиуса вокруг маркера на карте

// Инициализация Google Maps
function initialize() {
    var myLatlng = new google.maps.LatLng(my_width.value, my_length.value); // Изначальные:-34.397  150.644 //my_width.value и my_length.value- обращение к айдишникам полей <input> соответствующие Широте/Долготе
    var myRad = (jsObj1.radius_twitt_gmap);  //console.log(myRad);

    var mapOptions = {
        center: myLatlng, //center: myLatlng – это координаты центра карты
        zoom: 6, // zoom – это увеличение при инициализации(в начале по-умолчанию)
        mapTypeId: google.maps.MapTypeId.ROADMAP, //mapTypeId – тип карты (политическая,физическая,гибрид: ROADMAP/SATELLITE/HYBRID/TERRAIN)
        mapTypeControlOptions: { // доп.настройки для карты:
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU // вместо горизонт.располож.отображения типа карт,- будет вертикальная(DEFAULT/HORIZONTAL_BAR/DROPDOWN_MENU)
        },
        mapTypeControl: true, //параметр определяет показывать или нет элемент управления типом отображения карты.(Default:true-показывать). FALSE- не показывать
        diableDefaultUI: false, //параметр для отключения настроек элем.управления.Для этого его нужно в true (Default:false)
        navigationControl: false, // параметр скрывает или отображает панель навигации, которая как правило расположена в левом верхнем углу карты
        scaleControl: true //будет или нет отображаться элемент управления масштабная линейка(true- она будет)
    };
    var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

    //добавления Маркера и его аннимирование
    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        animation:google.maps.Animation.BOUNCE //анимирование имеющегося маркера(чтобы без анимирования,- убрать эту строку и marker.setMap(map);)
    });
    marker.setMap(map);
    markers.push(marker); //добавляем существующий маркер(маркер,кот.отображается при иннициализации карты) в массив для маркеров

    //добавление cообщения при клике на Маркер
    var infowindow = new google.maps.InfoWindow({
        content: "You are here!"
    });
    google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(map,marker);
    });

    //________________________Создание при клике по карте маркеров с окошком с координатами_________________________
    /*
  google.maps.event.addListener(map, 'click', function(event) { placeMarker(event.latLng);});

        function placeMarker(location) {
        var marker = new google.maps.Marker({
            position: location,
            map: map,
        });
        var infowindow = new google.maps.InfoWindow({ content: 'Latitude: ' + location.lat() + '<br>Longitude: ' + location.lng() });
        infowindow.open(map,marker);
    }
    */
    //добавления радиуса вокруг инициализированного маркера на карте
    var myRadius = new google.maps.Circle({
        center:myLatlng,
        radius:myRad*1000, // размер радиуса вокруг маркера
        strokeColor:"#0000FF", // цвет рамки круга,-т.е. border'а
        strokeOpacity:0.4,    //прозрачность всего радиуса круга
        strokeWeight:1,      //жирность рамки радиуса круга
        fillColor:"#0000FF",    // цвет зоны радиуса круга (без рамки круга,-т.е.без border)
        fillOpacity:0.2,    // прозрачность всего радиуса круга
        editable: true // добавляем на радиус по краям спец.элемент для растягивания радиуса на прям на карте
    });
    //console.log(radius_gmap.value);
    myRadius.setMap(map);

    //блок для 'radius_changed'-изменения Радиуса на карте растягиванием его за спец,элементы по краям__START
    google.maps.event.addListener(myRadius, 'radius_changed', function() {
                console.log(myRadius.getRadius()); // проверяем что пришло
        var getRad= parseInt(myRadius.getRadius()/1000); // Мы в перем.getRad ложим значение из( myRadius.getRadius() ).Чтобы значение было корректное(как нам надо),мы его делим на 1000; а чтобы получить только целое число - оборачиваем все в parseInt(),кот.вытаскивает из значения только целое число
                console.log(getRad); // проверяем что пришло

        jQuery("#slider").slider("option", "value", getRad); // Слайдеру(plugin Slider.UI) передаем значение нашей перем.(getRad)
        jQuery("#radius_gmap").val(getRad); //  полю для Радиуса <input id="#radius_gmap">  передаем значение нашей перем.(getRad)
    });
    //блок для 'radius_changed'-изменения Радиуса на карте растягиванием его за спец,элементы по краям__END

    radiuses.push(myRadius);//добавляем существующий маркер(маркер,кот.отображается при иннициализации карты) в массив для маркеров

    //__________________________________УДАЛЕНИЕ МАРКЕРА и ДОБАВЛЕНИЕ НОВОГО по КЛИКУ___________________________________
    google.maps.event.addListener(map, 'click', function(event) {  //событие по клику
        deleteMarkers(); //удалять существующий маркер
        placeMarker(event.latLng); // ставить новый в нужном месте
    });

    function deleteMarkers() {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
        markers = []; // добавляем новый маркер при клике в наш массив
    }

    function placeMarker(location) {
        //console.dir(location); // проверить что в location- важно
        var marker = new google.maps.Marker({
            position: location,
            map: map,
            content: 'Latitude: ' + location.lat() +
                     'Longitude: ' + location.lng()
                     //animation:google.maps.Animation.BOUNCE анимирование имеющегося маркера(чтобы без анимирования,- убрать эту строку и marker.setMap(map);)
        });
        markers.push(marker);
        var infowindow = new google.maps.InfoWindow({ content: 'Latitude: ' + location.lat() + '<br>Longitude: ' + location.lng() }); // добавляем окошко с координатами на новом маркере
        infowindow.open(map,marker);

        jQuery('#my_width').val(location.lat()); // полю Широта-> Координаты текущей позиции маркера
        jQuery('#my_length').val(location.lng()); // полю Долгота-> Координаты текущей позиции маркера

    }
//_______________________________________________________________________________________________________________
/*
    //добавления радиуса вокруг инициализированного маркера на карте
    var myRadius = new google.maps.Circle({
        center:myLatlng,
        radius:80000, // размер радиуса вокруг маркера
        strokeColor:"#0000FF", // цвет рамки круга,-т.е. border'а
        strokeOpacity:0.4,    //прозрачность всего радиуса круга
        strokeWeight:1,      //жирность рамки радиуса круга
        fillColor:"#0000FF",    // цвет зоны радиуса круга (без рамки круга,-т.е.без border)
        fillOpacity:0.2    // прозрачность всего радиуса круга
    });
    myRadius.setMap(map);
    */
//------------------------УДАЛЕНИЕ РАДИУСА и ДОБАВЛЕНИЕ НОВОГО по КЛИКУ---------------------------
    google.maps.event.addListener(map, 'click', function(event) {  //событие по клику
        deleteRadius(); //удалять существующий маркер
        addRadius(event.latLng); //ставить новый в нужном месте

    });

    function deleteRadius() {
        for (var i = 0; i < radiuses.length; i++) {
            radiuses[i].setMap(null);
        }
        radiuses = [];
    }

    function addRadius(location) {
        var myRadius = new google.maps.Circle({
            center:location,
            radius:myRad*1000, // размер радиуса вокруг маркера
            strokeColor:"#0000FF", // цвет рамки круга,-т.е. border'а
            strokeOpacity:0.4,    //прозрачность всего радиуса круга
            strokeWeight:1,      //жирность рамки радиуса круга
            fillColor:"#0000FF",    // цвет зоны радиуса круга (без рамки круга,-т.е.без border)
            fillOpacity:0.2,    // прозрачность всего радиуса круга
            editable: true    // добавляем на радиус по краям спец.элемент для растягивания радиуса на прям на карте
        });
        myRadius.setMap(map);

        //блок для 'radius_changed'-изменения Радиуса на карте растягиванием его за спец,элементы по краям__START
        google.maps.event.addListener(myRadius, 'radius_changed', function() {
            console.log(myRadius.getRadius()); // проверяем что пришло
            var getRad= parseInt(myRadius.getRadius()/1000); // Мы в перем.getRad ложим значение из( myRadius.getRadius() ).Чтобы значение было корректное(как нам надо),мы его делим на 1000; а чтобы получить только целое число - оборачиваем все в parseInt(),кот.вытаскивает из значения только целое число
            console.log(getRad); // проверяем что пришло

            jQuery("#slider").slider("option", "value", getRad); // Слайдеру(plugin Slider.UI) передаем значение нашей перем.(getRad)
            jQuery("#radius_gmap").val(getRad); //  полю для Радиуса <input id="#radius_gmap">  передаем значение нашей перем.(getRad)
        });
        //блок для 'radius_changed'-изменения Радиуса на карте растягиванием его за спец,элементы по краям__END
        radiuses.push(myRadius);
    }

// На изменение поля ШИРОТА/ДОЛГОТА вручную - изменяется положение маркера на Карте и радиуса вокруг него
    jQuery('#my_width, #my_length').change( function() {
        //____for Marker:
        var lat = jQuery('#my_width').val(); //console.log(lat);
        var lng = jQuery('#my_length').val(); // console.log(lng);
        var loc = new google.maps.LatLng(lat, lng);
        deleteMarkers(); //вызываем ф-ю удаления Маркера(кот.у нас была описана ранее)
        placeMarker(loc); // вызываем ф-ю установить Маркер(кот.у нас была описана ранее) с новой переменной(loc),в кот.новые координаты(lat, lng)

        //____for Radius:
        deleteRadius(); //вызываем ф-ю удаления Радиуса(кот.у нас была описана ранее для Радиуса)
        addRadius(loc); //вызываем ф-ю удаления Радиуса(кот.у нас была описана ранее для Радиуса)

    });
//------------------------------------------------------------------------------------------------------------------------------

} //END__function initialize
google.maps.event.addDomListener(window, 'load', initialize);
/*__________или так делать инициализацию:
 function initialize() {
 var mapOptions = {center: { lat: -34.397, lng: 150.644},zoom: 6,mapTypeId: google.maps.MapTypeId.ROADMAP };
 var map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
 }
 google.maps.event.addDomListener(window, 'load', initialize);
*/
//------------------------------------------------------------------------------------------------------------------------------
    // Slider плагина Slider.UI: перемещение ползунка на слайдере меняет Радиус поля вокруг маркера
    jQuery("#slider").slider({
        min: 0, // настройка слайдера: минимальн.значение слайдера
        max: 500, // настройка слайдера: максимальное значение слайдера
        step: 1, // шаг ползунка слайдера
        value: jsObj1.radius_twitt_gmap, // в значение слайдера загоняем данные из своей табл(wp_options).БД, чтобы данные загружались от туда
        //range: true, // надо,когда 2 ползунка на слайдере и тогда значение не value у него,а values
        stop: function(event, ui) {
           //console.log(ui);
        },
        slide: function(event, ui) { // вешаем на событие плагина Slider изменение значения поля для Радиуса <input> в зависимости от движения ползунка Слайдера
            jQuery("#radius_gmap").val(ui.value); // передаем значение с ползунка Слайдера(val(ui.value)) в поле для Радиуса <input id="radius_gmap">
            radiuses[0].setRadius(ui.value*1000); // теперь,чтобы значение Радиуса привести в правильные единицы умножаем на 1000 и передаем в наш массив для радиуса(radiuses[0])
        }
    });
//-----------------------------------------------------------------------------
    //____for slider:
    // На изменение поля РАДИУС вручную - изменяется положение ползунка Слайдера  и изменение самого радиуса вокруг маркера по-новому
    jQuery("#radius_gmap").change( function() {
        var item = jQuery("#radius_gmap").val(); // забираем в перем.item значение из поля <input id="radius_gmap">.Забираем,т.к.обращаемся к элементу <input id="radius_gmap"> с пустым val( val() )
        console.log(item); // проверяем что пришло в перем.item

        jQuery("#slider").slider("option", "value", item); // обращаемся к Slider'у плагина Slider.UI и передаем в него новое значение из нашей перем.item

        radiuses[0].setRadius(item*1000); // теперь передаем самому радиусу, добавляя в массив для Радиуса наше новое значение из перем.item == (radiuses[0].setRadius)
    });


}); // END__jQuery(document).ready(function()



