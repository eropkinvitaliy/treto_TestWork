<!DOCTYPE html>
<html>
<head lang="en"  content="image/jpeg">
    <meta charset="UTF-8">
    <link href="./Assets/css/style.css" rel="stylesheet" type="text/css" media="screen">
    <title></title>
</head>
<body>
<div id="info"></div>
<div id="context" style="display: none"></div>
<div id="wall"></div>

<div id="forma" style="float: none">
        <form name="formdefault" action="#" method="POST">
            <p>Выберите файл (*.txt) со списком изображений</p>
            <p>Путь к картинке должен быть, например, такой  <b>http://mail.ru/img_lb/image.jpg</b></p>
            <p>или, например, такой  <b> D:/image/image.jpg</b></p>
        <input type="file" name="filetxt" accept="*/txt" id="filetxt">
            <input type="submit" id="fileload" value="Вывести на экран">
            <p></p>
            <input type="submit" id="parseyandex" value="Показать на экране картинки с сайта Artzveri.ru"
                   style="background-color: #cceecc; color: red">
            <input type="submit" id="resetsrc" value=" Очистить кэш "
                   style="background-color: red; color: black">
            <div id="filename"></div>
    </form>
</div>

</body>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="./Assets/js/GreatWall.js"></script>
</html>