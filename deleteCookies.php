<?php
setcookie("userInfoName", "", time()-60*60*24*30);
setcookie("userInfoPass", "", time()-60*60*24*30);
echo $_COOKIE['userInfoName'];
echo 1;