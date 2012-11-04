<?php

/**
 * @package JooS
 */
require_once "JooS/Helper/Interface.php";

abstract class JooS_Helper_Abstract implements JooS_Helper_Interface
{

    /**
     * @var JooS_Helper_Subject
     */
    private $_subject = null;

    public static function init()
    {
        
    }

    /**
     * @param JooS_Helper_Subject $subject
     * @return JooS_Helper_Abstract
     */
    final public function setSubject($subject)
    {
        $this->_subject = $subject;
        return $this;
    }

    /**
     * @return JooS_Helper_Subject
     */
    final protected function getSubject()
    {
        return $this->_subject;
    }

}
