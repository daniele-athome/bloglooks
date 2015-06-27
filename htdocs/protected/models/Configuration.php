<?php

/**
 * This is the model class for table "configuration".
 *
 * The followings are the available columns in table 'configuration':
 * @property string $name
 * @property string $language
 * @property string $value
 * @property string $help
 */
class Configuration extends CActiveRecord
{
    private static $cache;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Configuration the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'configuration';
	}

	/**
	 * Load configuration from database.
	 */
	public static function load() {
	    // load default language first
	    $data = self::model()->findAllByAttributes(array('language' => Controller::getSourceLanguage()));
	    $cache = array();
	    foreach ($data as $row)
	        $cache[$row['name']] = $row['value'];

	    // override language
	    $data = self::model()->findAllByAttributes(array('language' => Controller::getLanguage()));
	    foreach ($data as $row)
	        $cache[$row['name']] = $row['value'];

	    self::$cache = $cache;
	    return self::$cache;
	}

	/** Access to the static configuration cache. */
	public static function get($name, $default=null)
	{
	    return array_key_exists($name, self::$cache) ? self::$cache[$name] : $default;
	}

    public static function getBoolean($name, $default=false)
    {
        // not using get() because we need a special behaviour here
        if (array_key_exists($name, self::$cache))
            return self::$cache[$name] ? true : false;
        else
            return $default;
    }

    public static function isCommentsDisabled()
    {
        return self::getBoolean('comments_disabled');
    }
}
