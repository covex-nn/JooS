<?php

  set_include_path(implode(PATH_SEPARATOR, array(
    __DIR__ . "/library",
    dirname(__DIR__) . "/library", 
    get_include_path()
  )));
