<?php
define ('DB_SERVER','sql300.byethost9.com');
define ('DB_USER','b9_13861543');
define ('DB_PASSWORD','laidaydi');
define ('DB_NAME','b9_13861543_redpen');
mysql_connect(DB_SERVER,DB_USER,DB_PASSWORD);
mysql_select_db(DB_NAME);
define("ROOT_URL",$_SERVER['SERVER_NAME']);
define("IMAGES_URL",'phanhung.byethost9.com');
define("IMAGES_DIR",$_SERVER['DOCUMENT_ROOT'].'/picture/uploads');
