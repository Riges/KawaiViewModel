<?php
global $g_user;
global $g_options;

$this->initializeParameter('string', 'title');
$this->initializeParameter('string', 'body');
$this->initializeParameterDefault('array', 'smallboxes', NULL);
$this->initializeParameterDefault('bool', 'noPageTitle', FALSE);
$this->initializeParameterDefault('bool', 'noFullText', FALSE);


$title = htmlspecialchars($this->title);

//echo "<?xml version=\"1.0\" encoding=\"utf-8\"
?>\n";
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php if (!$this->noPageTitle) echo $title . ' - '; ?>Kawaï Neko Box</title>

    <script type="text/javascript">root_url = "<?php echo ROOT_URL; ?>";</script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type='text/javascript'
            src="<?php echo $this->createUriFromBase($g_options['jquery']['debug'] ? 'script/jquery.js' : 'script/jquery.min.js'); ?>"></script>
    <script type='text/javascript' src="<?php echo $this->createUriFromBase('script/jsrender.js'); ?>"></script>
    <script type='text/javascript'
            src="<?php echo $this->createUriFromBase('script/jquery.observable.js'); ?>"></script>
    <script type='text/javascript' src="<?php echo $this->createUriFromBase('script/jquery.views.js'); ?>"></script>
    <script type='text/javascript'
            src="<?php echo $this->createUriFromBase($g_options['bootstrap']['debug'] ? 'script/bootstrap.js' : 'script/bootstrap.min.js'); ?>"></script>
    <script type='text/javascript'
            src="<?php echo $this->createUriFromBase($g_options['bootstrap']['debug'] ? 'script/jquery.placeholder.js' : 'script/jquery.placeholder.min.js'); ?>"></script>
    <script type='text/javascript' src="<?php echo $this->createUriFromBase('script/knb.js'); ?>"></script>

    <link rel="stylesheet"
          href="<?php echo $this->createUriFromBase($g_options['bootstrap']['debug'] ? 'style/#SKIN#/bootstrap.css' : 'style/#SKIN#/bootstrap.min.css'); ?><?php echo $this->createUriFromBase('style/#SKIN#/screen.css'); ?>"
          type="text/css" media="screen"/>
    <link rel="stylesheet"
          href="<?php echo $this->createUriFromBase($g_options['bootstrap']['debug'] ? 'style/#SKIN#/bootstrap-responsive.css' : 'style/#SKIN#/bootstrap-responsive.min.css'); ?>"
          type="text/css" media="screen"/>
    <link rel="stylesheet" href="<?php echo $this->createUriFromBase('style/#SKIN#/screen.css'); ?>" type="text/css"
          media="screen"/>
    <link rel="stylesheet" href="<?php echo $this->createUriFromBase('style/#SKIN#/print.css'); ?>" type="text/css"
          media="print"/>
    <!--[if IE]>
    <link rel="stylesheet" href="<?php echo $this->createUriFromBase('style/#SKIN#/internet-explorer.css'); ?>"
          type="text/css" media="screen"/>
    <![endif]-->
    <script type="text/javascript">
        $(document).ready(function () {
            $('input, textarea').placeholder();
        });
    </script>
</head>
<body>
<div id="bg-left"></div>
<div id="bg-right"></div>
<div id="header">
    <div id="member">
        <?php if ($g_user->isAnonymous()): ?>
            <form id="loginform" action="<?php echo $this->createUriFromBase('login'); ?>" method="post"
                  onsubmit="doLogin();return false;">
                Utilisateur :
                <input id="login" name="login" type="text"/>
                Mot de passe :
                <input id="password" name="password" type="password"/>
                <input id="submit_login" type="submit" value="Ok"/>
            </form>
        <?php else: ?>
            <div id="userbox">
                Vous êtes connecté en tant que <strong><?php echo $g_user->getFullName(); ?></strong>.
                <br/>
                <a href="<?php echo $this->createUriFromBase('logout'); ?>">Logout</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<div id="mainmenu">
    <ul>
        <li><a href="<?php echo $this->createUriFromBase('news'); ?>"
               style="background-image: url('<?php echo $this->createUriFromBase('img/#SKIN#/mainmenu-btn-news.png'); ?>')">News</a>
        </li>
        <li><a href="<?php echo $this->createUriFromBase('photos'); ?>"
               style="background-image: url('<?php echo $this->createUriFromBase('img/#SKIN#/mainmenu-btn-photos.png'); ?>')">Photos</a>
        </li>
    </ul>
</div>
<div id="main">
    <div id="submenu">
        <?php
        foreach ($this->submenu as $title => $submenuContent) {
            echo "<h2>$title</h2>";
            echo '<ul>';
            foreach ($submenuContent as $text => $link) {
                $link = $this->createUriFromModule($link);
                echo "<li><a href='$link'>$text</a></li>";
            }
            echo '</ul>';
        }
        ?>
    </div>
    <div id="content">
        <h1><?php echo $this->title; ?></h1>
        <?php if (!$this->noFullText): ?>
            <div id="fulltext"><?php echo $this->body; ?></div>
        <?php else:
            echo $this->body;
        endif; ?>
    </div>
</div>
<div id="footer-bg">
    <div id="footer">
        Copyright © 2007 - <?php echo date('Y'); ?> Kawai Neko Box - Tous droits réservés pour tous les pays
    </div>
</div>
<script type="text/javascript">
    var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
    document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
    var pageTracker = _gat._getTracker("UA-5031780-1");
    pageTracker._initData();
    pageTracker._trackPageview();
</script>
</body>
</html>
