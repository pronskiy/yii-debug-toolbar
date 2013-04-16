<?php
/**
 * YiiDebugDbCommand class file.
 *
 * @author Roman Pronskiy <roman@pronskiy.com>
 */

/**
 * YiiDebugDbCommand represents an SQL statement to execute against a database.
 *
 * It is a decorator for original CDbCommand however implemented as a descendant
 * in order to be fully compatible with Yii core.
 *
 * @author Roman Pronskiy <roman@pronskiy.com>
 * @version $Id$
 * @package YiiDebugToolbar
 * @since 1.1.7
 */
class YiiDebugDbCommand extends CDbCommand
{
    protected function saveDebugStackTrace()
    {
        Yii::app()->db->collectDebugInfo(array_slice(debug_backtrace(), 1));
    }

    public function execute($params=array())
    {
        $this->saveDebugStackTrace();
        return parent::execute($params);
    }

    public function query($params=array())
    {
        $this->saveDebugStackTrace();
        return parent::query($params);
    }

    public function queryAll($fetchAssociative=true,$params=array())
    {
        $this->saveDebugStackTrace();
        return parent::queryAll($fetchAssociative, $params);
    }

    public function queryRow($fetchAssociative=true,$params=array())
    {
        $this->saveDebugStackTrace();
        return parent::queryRow($fetchAssociative, $params);
    }

    public function queryScalar($params=array())
    {
        $this->saveDebugStackTrace();
        return parent::queryScalar($params);
    }

    public function queryColumn($params=array())
    {
        $this->saveDebugStackTrace();
        return parent::queryColumn($params);
    }
}