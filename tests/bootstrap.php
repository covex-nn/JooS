<?php

  set_include_path(implode(PATH_SEPARATOR, array(
    dirname(__FILE__) . "/library",
    dirname(dirname(__FILE__)) . "/library", 
    get_include_path()
  )));
