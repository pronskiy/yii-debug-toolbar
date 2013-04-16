<?php
/**
 * YiiDebugDbConnection class file.
 *
 * @author Roman Pronskiy <roman@pronskiy.com>
 */

/**
 * YiiDebugDbConnection represents a proxy object to database connection.
 *
 * Though this class is meant to be a proxy, actually it isn't. In fact it's a
 * CDbConnection descendant. This is made because of "instanceof CDbConnection" calls
 * in Yii core files.
 *
 * The only significant difference from the original CDbConnection class is that it instantiates
 * YiiDebugDbCommand instead of CDbCommand in the createCommand().
 *
 * @author Roman Pronskiy <roman@pronskiy.com>
 * @version $Id$
 * @package
 * @since 1.1.7
 */
class YiiDebugDbConnection extends CDbConnection
{
    private $_instance;

    protected $_debugStackTrace = array();

    /**
     * @var String Path which is subtracted from the stack trace file paths
     */
    protected $_basePath;

    public function setInstance($value)
    {
        if (null === $this->_instance && false !== is_object($value))
        {
            $this->_instance = $value;
        }
    }

    public function getInstance()
    {
        return $this->_instance;
    }

    public function init()
    {
        $reflect = new ReflectionClass($this->_instance);

        foreach ($reflect->getProperties(ReflectionProperty::IS_PUBLIC) as $prop)
        {
            $name = $prop->getName();
            $this->$name = $prop->getValue($this->_instance);
        }

        $this->_basePath = dirname(Yii::app()->request->scriptFile);

        parent::init();
    }

    public function getDebugStackTrace()
    {
        return $this->_debugStackTrace;
    }

    public function createCommand($query=null)
    {
        $this->setActive(true);
        return new YiiDebugDbCommand($this,$query);
    }

    public function collectDebugInfo($trace)
    {
        foreach ($trace as &$traceItem)
        {
            if (isset($traceItem['file']))
                $traceItem['file'] = str_replace($this->_basePath, '', $traceItem['file']);
        }
        array_push($this->_debugStackTrace, $trace);
    }
}