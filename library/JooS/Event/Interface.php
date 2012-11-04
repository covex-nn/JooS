<?php
  
  /**
   * @package JooS
   */
  interface JooS_Event_Interface {
    public static function getInstance();

    public function notify();

    public function attach($observer);

    public function detach($observer);

    public function cancel($message = null, $code = null);
    
    public function name();

    public function observers();
  }
