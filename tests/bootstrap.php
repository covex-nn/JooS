<?php

require_once dirname(__DIR__) . "/vendor/autoload.php";

set_include_path(
  implode(
    PATH_SEPARATOR, 
    array(
      __DIR__ . "/src", 
      get_include_path()
    )
  )
);
