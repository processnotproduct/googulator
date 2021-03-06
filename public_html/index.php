<!DOCTYPE html>
<?
    include "configuration.php";
    $tabs = array("home","library","gameboy","nes","settings");
    $tabNames = array("Home","Library","Gameboy","NES","Settings");
    $defaultTab = 0;

    if (!$IS_LOCAL){
        $hostName = $_SERVER["HTTP_HOST"];
        if (strcmp($hostName,$PREFERRED_HOSTNAME) != 0){
            $https = "";
            if (isset($_SERVER['HTTPS'] )  && strcmp($_SERVER['HTTPS'],"off") != 0)
                $https = "s";
            echo "<html>";
            echo "<head>";
            echo "<meta http-equiv='refresh' content='0;URL=http";
            echo $https;
            echo "://";
            echo $PREFERRED_HOSTNAME;
            echo "/'>";
            echo "</head>";
            echo "<body></body>";
            echo "</html>";
            die;
        }
    }
?>
<html manifest="appcache.php">
    <head>
        <link rel="chrome-webstore-item" href="https://chrome.google.com/webstore/detail/lchmgljjkaeadokijkhefbhpfbihhhda">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" href="lib/bootstrap.2.3.0/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="lib/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" href="css/main.css" />
        <link rel="stylesheet" type="text/css" href="lib/jquery-ui-1.10.3.custom.1/css/smoothness/jquery-ui-1.10.3.custom.min.css">
        <?
            echo "<script type='text/javascript'>";
            if ($_GET["state"] == NULL){
                echo "window.driveState = null;";
            }
            else{
                echo "window.driveState = JSON.parse('";
                echo $_GET["state"];
                echo "');";
            }

            echo "</script>"
        ?>
        <script>
            <?
            echo "window.configuration = {google:{clientId:'";
            echo $GOOGLE_CLIENT_OAUTH_CLIENT_ID;
            echo "',apiKey:'";
            echo $GOOGLE_CLIENT_DEVELOPER_KEY;
            echo "'}};";
            ?>
        </script>
        <script src="http://www.google.com/jsapi?key=AIzaSyBObarUyhNSekoMdLbUlIooxsxVEEJLHQM"></script>
        <script src="https://apis.google.com/js/client.js?onload=googleAPILoaded"></script>
        <script src="lib/jQuery.1.9.1.js"></script>
        <script src="lib/jquery-ui-1.10.3.custom.1/js/jquery-ui-1.10.3.custom.min.js"></script>
        <script src="lib/jquery.fullscreen.1.1.4.js"></script>
        <script src="lib/doTimeout.1.0.js"></script>
        <script src="lib/hogan.2.0.0.js"></script>
        <script src="lib/bootstrap.2.3.0/js/bootstrap.min.js"></script>
        <script src="lib/require.2.1.4.js" data-main="js/main"></script>
        <script src="lib/Gamepad.js"></script>
        <script src="lib/Webcam.js"></script>
        <script src="lib/waapisim.2013.06.13.js"></script>
        <?
            echo $ANALYTICS_TRACKING_CODE;
        ?>
        <title>Googulator</title>
    </head>
    <body onload="htmlLoaded();">
        <div class="container-fluid mainContainer">
            <div class="navbar navbar-fixed-top">
                <div class="navbar-inner">
                    <div class="container" style="width: auto; padding: 0 20px;">
                        <div style="position: absolute; left: 0px; height: 40px; display: inline; right: 0px; text-align: center;">
                            <?
                                echo $GOOGLE_AD_CODE;
                            ?>
                        </div>
                        <ul class="nav">
                            <?
                                for ($i = 0; $i < count($tabs); $i++){
                                    echo '<li';
                                    if ($i == $defaultTab){
                                        echo ' class="active primary"';
                                    }
                                    echo '><a href="#" modulename="';
                                    echo $tabs[$i];
                                    echo '">';
                                    echo $tabNames[$i];
                                    echo '</a></li>';
                                }
                            ?>
                            <li>
                                <a href="https://plus.google.com/communities/108343287295374695153" target="_blank">Google+ Community</a>
                            </li>
                        </ul>

                        <div id="googleUserInfo" style="position:absolute; right:10px; top:0px">
                            <div id="loadText">Loading Google Credentials...</div>
                            <button class="btn hidden" id="loadButton">Login with Google</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="coreModuleContainer" id="coreModuleContainer">
                <?
                $regex = '/<template id="mainDisplay">/i';
                for ($i = 0; $i < count($tabs); $i++){
                    echo '<div class="moduleContainer';
                    if ($i != $defaultTab)
                        echo " hidden";
                    echo '" id="moduleContainer';
                    echo $tabs[$i];
                    echo '">';

                    $templateFile = file_get_contents("js/modules/" . $tabs[$i] . "/template.html");
                    preg_match($regex,$templateFile,$matches,PREG_OFFSET_CAPTURE);
                    $start = $matches[0][1] + strlen($matches[0][0]);
                    $end = strpos($templateFile,"</template>",$start) - $start;
                    $templateFile = substr($templateFile,$start,$end);
                    echo $templateFile;
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </body>
</html>