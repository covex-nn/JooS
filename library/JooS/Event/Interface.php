<?php

/**
 * Интерфейс для события.
 * 
 * @package JooS
 */
interface JooS_Event_Interface
{

  /**
   * Create event instance.
   * 
   * @todo Проверить все ли события используют new static ?
   * 
   * @return JooS_Event_Interface
   */
  public static function getInstance();

  /**
   * Notify observers.
   * 
   * @return JooS_Event_Interface
   */
  public function notify();

  /**
   * Attach observer.
   * 
   * @param callback $observer Обработчик
   * 
   * @return JooS_Event_Interface
   */
  public function attach($observer);

  /**
   * Detach observer.
   * 
   * @param type $observer Обработчик
   * @return JooS_Event_Interface
   */
  public function detach($observer);

  /**
   * Cancel event.
   * 
   * @param string $message Message для JooS_Event_Exception
   * @param int    $code    Code для JooS_Event_Exception
   * 
   * @return null
   */
  public function cancel($message = null, $code = null);

  /**
   * Returns event's name
   * 
   * @return string
   */
  public function name();

  /**
   * Returns list of observers
   * 
   * @return array
   */
  public function observers();

}
