<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <title>Document</title>
</head>

<body>
    <header>

    <h1>Оставить заявку на получение файлов</h1>
    </header>
    <main>
        Нажмите чтобы получить файлы <br><br>
        <button class="btn-getfile">Кликни</button>
    </main>
    <div class="popup-back">
        <div class="popup">
            <button class="popup-btn-close">×</button>
            <div class="popup-img-block">
                <div class="gf-files">
                    <img src="img/formats/doc.png" alt="doc">
                    <img src="img/formats/xls.png" alt="xls">
                    <img src="img/formats/pdf.png" alt="pdf">
                    <img src="img/formats/pdf.png" alt="pdf">
                    <img src="img/formats/pdf.png" alt="pdf">
                    <img src="img/formats/pdf.png" alt="pdf">
                    <img src="img/formats/pdf.png" alt="pdf">

                </div>
                <img class="gf-filebox" src="img/file.png">
            </div>
            <h3>Получите набор файлов для руководителя:</h3>
            <form action="getfiles.php" class="popup-gf-form" id="gf-form">
                <label for="gf-email">Введите Email для получения файлов</label>
                <input type="email" id="gf-email" name="email" required placeholder="E-mail" pattern="[^@\s]+@[^@\s]+\.[^@\s]+">
                <label for="gf-phone">Введите телефон для подтверждения доступа</label>
                <input type="tel" id="gf-phone" name="phone" pattern="\+7\([0-9]{3}\)[0-9]{3}-[0-9]{2}-[0-9]{2}" required placeholder="+7(000)000-00-00">
                <button type="submit" class="gf-button">Скачать файлы <img src="img/hand.png"></button>
                <small>
                    <span>PDF 4,7 MB</span>
                    <span>PDF 4,7 MB</span>
                    <span>XLS 1,2 MB</span>
                </small>
            </form>

        </div>
    </div>
    <div class="noty" style="display:none"><span>sdfsadfsadfasdf</span><button class="noty-btn-close" onclick="NotyCL();">×</button></div>
    <footer onclick="">IntroZorn&copy; <?php date("Y"); ?></footer>
</body>

</html>