<?php

set_include_path(
  get_include_path() . PATH_SEPARATOR . __DIR__ . "/library"
);

require_once "JooS/Config/Adapter/Serialized.php";

$configAdapter = new JooS_Config_Adapter_Serialized(__DIR__ . "/config");

require_once "JooS/Config.php";

JooS_Config::setDataAdapter($configAdapter);

require_once "JooS/Event/Bootstrap.php";

JooS_Event_Bootstrap::getInstance()->notify();
