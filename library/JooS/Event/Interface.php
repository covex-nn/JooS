<?php

/**
 * Интерфейс для события.
 * 
 * @package JooS
 */
interface JooS_Event_Interface
{

  /**
   * Создание инстанса события
   * 
   * @todo Проверить все ли события используют new static ?
   */
  public static function getInstance();

  /**
   * Запуск возникновения события
   */
  public function notify();

  /**
   * Присоединить обработчик события
   * 
   * @param callback $observer Обработчик
   * @return JooS_Event_Interface
   */
  public function attach($observer);

  /**
   * Отсоединить обработчик события
   * 
   * @param type $observer Обработчик
   * @return JooS_Event_Interface
   */
  public function detach($observer);

  /**
   * Отмена события
   * 
   * @param string $message Message для JooS_Event_Exception
   * @param int $code Code для JooS_Event_Exception
   */
  public function cancel($message = null, $code = null);

  /**
   * Возвращает имя события
   */
  public function name();

  /**
   * Возвращает список обзёрверов
   */
  public function observers();

}
