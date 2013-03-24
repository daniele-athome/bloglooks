<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    /** Configuration. */
    public $config;
    /** List of published pages. */
    public $pages;
    /** List of archived months. */
    public $archives;
    /** Current (short-form: en) language.*/
    public $language;
    /** Current source language (fallback). */
    public $sourceLanguage;
    /** Available languages (distinct of post.language) */
    public $availableLanguages;

	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column2',
	 * meaning using a single column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	public $roles = array(
	    // normal user - can comment
	    'user',
	    // editor - can create/edit pages and posts
	    'editor',
	    // admin - superuser
	    'admin',
    );

	public function init()
	{
	    // language: user preference (cookie)
	    $language = self::getUserLanguage();
	    // language: user preference (accept-language header)
	    if (!$language)
	        $language = Yii::app()->request->preferredLanguage;

	    if ($language)
	        Yii::app()->language = self::getLanguage($language);

	    $this->language = self::getLanguage();
	    $this->sourceLanguage = self::getSourceLanguage();

	    // carica la configurazione
	    $this->config = Configuration::load();

	    // carica i privilegi
	    $auth=Yii::app()->getAuthManager();
	    foreach ($this->roles as $role)
	        $auth->createRole($role);

	    $user = Yii::app()->user;
	    if (!$user->isGuest)
	        $auth->assign($user->role, $user->id);
	}

	public static function setUserLanguage($lang)
	{
        $name = Yii::app()->name . '_lang';
        Yii::app()->request->cookies[$name] = new CHttpCookie($name, $lang, array('path' => Yii::app()->baseUrl));
	}

	public static function getUserLanguage()
	{
	    $name = Yii::app()->name . '_lang';
	    if (isset(Yii::app()->request->cookies[$name]))
	        return Yii::app()->request->cookies[$name]->value;
	}

	/**
	 * Returns current language in short form (e.g. en).
	 * @return string
	 */
	public static function getLanguage($lang=null)
	{
	    if (!$lang) $lang = Yii::app()->language;
	    return Yii::app()->locale->getLanguageID($lang);
	}

	/**
	 * Returns current source language in short form (e.g. en).
	 * @return string
	 */
	public static function getSourceLanguage()
	{
	    return Yii::app()->locale->getLanguageID(Yii::app()->sourceLanguage);
	}

	public function beforeRender($view)
	{
	    $criteria = new CDbCriteria();
        $criteria->addCondition('published IS NOT NULL');
        $criteria->addColumnCondition(array('language' => $this->language));

	    // load pages for sidebar menu
	    $criteria->select = array('name', 'title');
	    $criteria->order = '`order`';
	    $this->pages = Page::model()->findAll($criteria);

	    // load archives for sidebar menu
	    $criteria->distinct = true;
	    $criteria->select = 'year(published) year, month(published) month, count(*) num';
	    // column conditions are already there
            $criteria->group = '`year`, `month`';
	    $criteria->order = '`year` DESC, `month` DESC';
	    $criteria->having = 'num > 0';
	    $command=Post::model()->getCommandBuilder()->createFindCommand(Post::model()->getTableSchema(),$criteria);
	    $this->archives = $command->queryAll();

	    // available languages for footer
	    $this->availableLanguages = array();
	    $command = Yii::app()->db->createCommand('SELECT DISTINCT language FROM posts ORDER BY language');
	    $result = $command->query();
	    foreach ($result as $lang)
	        $this->availableLanguages[] = $lang['language'];

	    return true;
	}

	public function get_page_info($name)
	{
	    foreach ($this->pages as $page)
	        if ($page->name == $name) return $page;
	    return null;
	}

	public function getRevisionUrl()
	{
	    ob_start();
	    system('git rev-parse HEAD');
	    $out = ob_get_clean();
	    if ($out) {
	        return CHtml::link($out,
	            sprintf(Yii::app()->params['revisionUrl'], $out),
	            array('target' => '_blank'));
	    }
	}

}
