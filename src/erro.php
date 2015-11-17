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
        <title><?= $lang['ERROR_TITLE'] ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

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

            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" class="icon-problem">
               <circle cx="20" cy="20" r="17.5"/>
                <line x1="11.2" y1="28.8" x2="28.8" y2="11.2"/>
                <line x1="28.8" y1="28.8" x2="11.2" y2="11.2"/>
            </svg>
            <h1><?= $lang['ERROR'] ?></h1>
            <section class="problem">
                <h2><?= $lang['ERROR_DESCRIPTION'] ?></h2>

            <footer>
                design and develop by <a href="http://d3.do">D3.do</a>
            </footer>

        </div>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
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
