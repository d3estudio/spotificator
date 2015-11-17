<!doctype html>
    <?php
        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        switch ($lang){
        case "pt":
            include("php/language/pt.php");
            break;
        default:
            include("php/language/en.php");
            break;
        }
    ?>
   <html lang="<?= $lang['LANG_ATTR'] ?>">
    <head>
        <meta charset="utf-8">
        <title><?= $lang['PAGE_TITLE'] ?></title>
        <meta name="description" content="<?= $lang['DESCRIPTION'] ?>">
        <meta name="keywords" content="<?= $lang['KEYWORDS'] ?>">

        <meta NAME="author" CONTENT="http://d3.do">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Facebook Share -->
        <meta property="og:image" content="http://www.spotificator.com.br/facebook-image.png">
        <meta property="og:title" content="<?= $lang['PAGE_TITLE'] ?>">
        <meta property="og:description" content="<?= $lang['DESCRIPTION'] ?>">

        <!-- build:css css/main.css -->
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
        <!-- endbuild -->

        <!--[if lt IE 9]>
        <script src="js/vendor/html5shiv.min.js"></script>
        <![endif]-->

    </head>
    <body>
        <div class="wrap">

            <!-- SVG -->
            <svg version="1.1" id="logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 125.9 62.1" class="gif-transition">
            <g class="file-dir">
                <path d="M111.8,0H76.9v62.1h49V13.5L111.8,0z M112.9,6.2l6.5,6.2h-6.5V6.2z M80.6,58.3V3.7h28.5v12.4h13v42.2
                    C122.1,58.3,80.6,58.3,80.6,58.3z"/>
            </g>
            <g class="file-esq">
                <path d="M0,13.5v48.6h49V0H14.1L0,13.5z M13,12.4H6.5L13,6.2V12.4z M3.7,58.3V16.1h13V3.7h28.5v54.6H3.7z"/>
            </g>
            <g class="line4 line">
                <rect x="22.5" y="12.8" width="18" height="3.5"/>
            </g>
            <g class="line3 line">
                <rect x="8.6" y="24.3" width="32" height="3.5"/>
            </g>
            <g class="line2 line">
                <rect x="8.6" y="35.8" width="32" height="3.5"/>
            </g>
            <g class="line1 line">
                <rect x="8.6" y="47.4" width="32" height="3.5"/>
            </g>
            </svg>
            <img src="img/logo-fallback.png" class="img-fallback" alt="playlist transfêrencia" />
            <!-- /SVG -->

            <!-- Cover -->
            <div class="cover" style="display:none">
                <figure>
                    <img src="img/cover-default.jpg" alt="Capa">
                    <span class="shadow"></span>
                </figure>
            </div>
            <!-- /Cover -->
            <h1>Spotificator</h1>


            <!-- Init -->
            <section class="init">
                <h2><?= $lang['HEADLINE'] ?></h2>

                <img src="img/logos-servicos.png" alt="Rdio, Deezer para Spotify" class="service-logos">

                <a href="#" class="button start" id="start"><?= $lang['BUTTON_INIT'] ?></a>
            </section>


            <!-- Choose -->
             <section class="choose">
                <h2><?= $lang['CHOOSE_SERVICE'] ?></h2>

                <ul class="options">
                    <li><a href="" class="button rdio">Rdio</a></li>
                    <li><a href="" class="button deezer">Deezer</a></li>
                </ul>
            </section>

            <!-- List Playlist -->
            <section class="list-playlist">
                <h2>
                    <?= $lang['CHOOSE_PLAYLIST'] ?>
                </h2>

                <ul class="playlists" id="playlists">
                </ul>

            </section>

             <!-- Split Playlist -->
            <section class="sliced-playlist">
                <h2>
                    <?= $lang['CHOOSE_SPLITVIEW'] ?>
                </h2>

                <ul class="playlists" id="sliced-playlist">
                </ul>


                 <a href="#" id="back-again" class="back-button" style=""><?= $lang['BUTTON_BACK'] ?></a>

            </section>

            <!-- Convert -->
            <section class="convert">
                <h2 id="instruction">
                    <?= $lang['WAITING_PROCESS'] ?>
                </h2>
                <a href="" id="btnSaveToSpotify" class="button waiting"></a>
                <p class="tip"><?= $lang['INSTRUCTIONS'] ?><br/><br/>
                <iframe width="416" height="310" src="//www.youtube.com/embed/WqSkxHwAFgI" frameborder="0" allowfullscreen></iframe>
                </p>


                <ul class="musics">
                </ul>

                <a href="#" id="back-playlists" class="back-button" style=""><?= $lang['BUTTON_BACK'] ?></a>
            </section>

            <footer>
                designed and developed by <a href="http://d3.do" target="_blank">D3.do</a>
            </footer>

        </div>

        <!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>-->
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.0.min.js"><\/script>')</script>
        <script src="js/vendor/jquery.velocity.min.js"></script>

        <script type="text/javascript">
            var FINISH_TEXT = '<?= $lang["FINISH_TEXT"] ?>';
            var IMPORT_TITLE = '<?= $lang["IMPORT_TITLE"] ?>';
            var WAINTING_TEXT = '<?= $lang["WAINTING_TEXT"] ?>';
            var BUTTON_TRASNFER = '<?= $lang["BUTTON_TRASNFER"] ?>';
            var BUTTON_STEP = '<?= $lang["BUTTON_STEP"] ?>';
            var BUTTON_OPEN = '<?= $lang["BUTTON_OPEN"] ?>';
            var OF_TEXT = '<?= $lang["OF_TEXT"] ?>';
            var MUSIC_TEXT = '<?= $lang["MUSIC_TEXT"] ?>';
        </script>

        <script src="js/main.js"></script>

        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-51239237-1', 'spotificator.com.br');
          ga('send', 'pageview');
        </script>

    </body>
</html>
