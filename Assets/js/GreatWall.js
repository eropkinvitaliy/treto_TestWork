/**
 * Created by on 20.07.2015.
 */
;
$(function () {
    var loadimages = [],
        marginleft = 2,                                  // отступы у картинок
        marginright = 2;
    var GreatWall = function (loadimages) {
        var Create = function () {
            img = document.createElement('img');
            img.style.height = '200px';
            img.style.width = width + 'px';
            img.src = image.src;
            img.style.marginLeft = marginleft + 'px';
            img.style.marginRight = marginright + 'px';
            img.className = 'first';
            arrimages[countelements] = img;
        };
        var margin = marginleft;
        $('#wallimages').remove();
        $('<div id="wallimages"></div>').appendTo('#wall');
        var screenwidth = Math.max(
            document.body.scrollWidth, document.documentElement.scrollWidth,
            document.body.offsetWidth, document.documentElement.offsetWidth,
            document.body.clientWidth, document.documentElement.clientWidth
        );
        var count = loadimages.length;

        if (count) {
            var image, ratio, width, allwidth, imagenext, widthnext, j, img, delta,
                arrimages = [], divwidth = [],
                heigthimage = 200,
                countelements = 0,
                stringwidth = 0,
                i = 0;
            while (i < count) {
                image = loadimages[i];
                width = Math.round(image.width * heigthimage / image.height);
                stringwidth = stringwidth + width + margin * 2;
                delta = Math.abs(screenwidth - stringwidth);
                if ((i + 1) < count) {
                    imagenext = loadimages[i + 1];
                    widthnext = Math.round(imagenext.width * heigthimage / imagenext.height);
                } else {
                    div = document.createElement('div');
                    div.className = 'box';
                    if (countelements > 0) {
                        for (j = 0; j < countelements; j++) {
                            div.appendChild(arrimages[j]);
                        }
                    }
                    Create();
                    div.appendChild(img);
                    $(div).appendTo('#wallimages');
                    return loadimages;
                }
                if (delta >= widthnext) {
                    Create();
                    divwidth[countelements] = width;
                    countelements++;
                }
                else {
                    ratio = delta / widthnext;
                    stringwidth = 0;
                    if (ratio < 0.4) {
                        allwidth = 0;
                        Create();
                        divwidth[countelements] = width;
                        for (j = 0; j <= countelements; j++) {
                            allwidth += divwidth[j];
                        }
                        ratio = delta / allwidth;
                        var widthzoom = 0;
                        for (j = 0; j <= countelements; j++) {
                            arrimages[j].style.width = Math.round((parseInt(arrimages[j].style.width) * (ratio + 1))) + 'px';
                        }
                    }
                    else {
                        Create();
                        i++;
                        countelements++;
                        image = loadimages[i];
                        width = Math.round(image.width * heigthimage / image.height);
                        Create();
                    }
                    div = document.createElement('div');
                    div.className = 'box';
                    for (j = 0; j <= countelements; j++) {
                        div.appendChild(arrimages[j]);
                    }
                    $(div).appendTo('#wallimages');
                    countelements = 0;
                    arrimages = [];
                    divwidth = [];
                }
                i++;
            }
        }
    };


    $(document).on('click', '#resetsrc', function (e) {
        e.preventDefault();
        $.post('./Controllers/Index/ResetSession', {src: 'src'}, (function (data) {
        }))
            .error(function () {
                alert('Ошибка');
            })
    });

    $(document).on('click', '#parseyandex', function (e) {
        e.preventDefault();
        $.post('./Controllers/Index/Parser', {src: 'http://artzveri.ru/', galery: 'Gallery1.html'}, (function (data) {
            $('#wallimages').remove();
            $('<div id="divimg"></div>').appendTo('#context').html(data);
            loadimages = [];
            var i = 0;
            $('img.first').load(function () {
                $(this).src = $(this).prop('src');
                $(this).width = $(this).prop('width');
                $(this).height = $(this).prop('height');
                loadimages[i] = $(this)[0];
                ++i;
            });
            $('.first:last').one("load", function () {
                GreatWall(loadimages);
                $(this).parent().remove();
            });
        }))
            .error(function () {
                alert('Ошибка');
            })
    });

    $(document).on('click', '#fileload', function (e) {
        e.preventDefault();
        if (window.FileReader === undefined) { // Проверяем наличие объекта в браузере
            alert('Нет FileReader!!!???');     // Если нет — негодуем!
            return false;
        }
        var input = document.getElementById('filetxt');
        if (input.value) {
            var fReader = new FileReader();
            fReader.readAsText(input.files[0]);
            fReader.onloadend = function (event) {
                var img = document.getElementById('filename');
                img.src = event.target.result;
                $('<div id="infoload"><h3>Подождите, идёт загрузка</h3></div>').appendTo('#info');
                $('div#forma').hide();
                $.ajax({
                    url: './Controllers/Index/Resize',
                    type: 'POST',
                    data: {src: img.src},
                    success: function (dat) {
                        var srcimages = dat.imagespath,
                            i = 0, j = 0,
                            loadimages = [];
                        $('#wallimages').remove();
                        $.each(srcimages, function (index, value) {
                            img = new Image();
                            img.src = value;
                            img.width = dat.width[i];
                            img.height = dat.height[i];
                            img.marginLeft = marginleft;
                            img.marginRight = marginright;
                            loadimages[i] = img;
                            ++i;
                            if (i >= dat.countimages) {
                                $('#infoload').remove();
                                GreatWall(loadimages);
                                $('div#forma').show();
                                $('#filetxt').val('');
                            }
                        });
                    },
                    dataType: 'json'
                });


                //$.post('./Controllers/Index/Resize', {src: img.src}, (function (data) {
                //    $('#wallimages').remove();
                //    $('<div id="divimg"></div>').appendTo('#context');
                //    //.html(data);
                //    loadimages = [];
                //    var i = 0;
                //    $('img.first').load(function () {
                //        $(this).src = $(this).prop('src');
                //        $(this).width = $(this).prop('width');
                //        $(this).height = $(this).prop('height');
                //        loadimages[i] = $(this)[0];
                //        ++i;
                //    });
                //    $('.first:last').one("load", function () {
                //        GreatWall(loadimages);
                //        $(this).parent().remove();
                //    });
                //}))
                //    .error(function () {
                //        alert('Ошибка');
                //    })
            };
        }

    });

    $(window).resize(function () {
        GreatWall(loadimages);
    });
});