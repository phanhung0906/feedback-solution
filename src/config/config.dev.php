<?php
define ('DB_SERVER','localhost');
define ('DB_USER','root');
define ('DB_PASSWORD','');
define ('DB_NAME','redpen');
mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD);
mysql_select_db(DB_NAME);
define("ROOT_URL",$_SERVER['SERVER_NAME']);
define("IMAGES_URL",'images.redpen.local');
define("IMAGES_DIR",'D:/projects/redpen/picture/uploads');