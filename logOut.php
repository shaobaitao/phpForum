<?php
session_start();
session_destroy();
setcookie("userInfoName", "");
setcookie("userInfoPass", "");