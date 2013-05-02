<?php

/**
 * @package JooS
 * @subpackage Event
 */
namespace JooS\Event;

/**
 * Интерфейс для события.
 */
interface Event_Interface
{

  /**
   * Create event instance.
   * 
   * @todo Проверить все ли события используют new static ?
   * 
   * @return Event_Interface
   */
  public static function getInstance();

  /**
   * Notify observers.
   * 
   * @return Event_Interface
   */
  public function notify();

  /**
   * Attach observer.
   * 
   * @param callback $observer Observer
   * 
   * @return Event_Interface
   */
  public function attach($observer);

  /**
   * Detach observer.
   * 
   * @param type $observer Observer
   * 
   * @return Event_Interface
   */
  public function detach($observer);

  /**
   * Cancel event.
   * 
   * @param string $message Message for Event_Exception
   * @param int    $code    Code for Event_Exception
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
