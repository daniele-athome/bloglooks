<?php

class PageController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		    'postOnly + delete', // allow delete only via POST
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('new','edit'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
		        'roles' => array('admin', 'editor'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionView($name, $language = null)
	{
	    $model = $this->loadModel($name, $language, true);

	    // controllare se la pagina e' stata pubblicata prima di visualizzarla
	    if (!$model->published and $model->author->id != Yii::app()->user->getId() and Yii::app()->user->checkAccess('admin'))
	        throw new CHttpException(404, 'The requested page does not exist.');

		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionNew($language=null)
	{
		$model=new Page;
		$model->language = $language ? $language : $this->language;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Page']))
		{
			$model->attributes=$_POST['Page'];
			$model->language = $language ? $language : $this->language;
			$model->author_id = Yii::app()->user->id;
			$model->created = $model->modified = date('Y-m-d H:i:s');

			if ($model->published)
			    $model->published = date('Y-m-d H:i:s');
			else
			    $model->published = null;

			if($model->save())
				$this->redirect(array('view','name'=>$model->name,'language'=>$model->language));
		}

		$this->render('new',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param string $name name of the page
	 * @param string $language language of the page.
	 */
	public function actionEdit($name, $language)
	{
		$model=$this->loadModel($name, $language);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Page']))
		{
		    // keep old published value
		    $published = $model->published;

			$model->attributes=$_POST['Page'];
			// update modified timestamp
			if ($model->modified)
			    $model->modified = date('Y-m-d H:i:s');

			if ($model->published) {
			    if (!$published)
			        $published = date('Y-m-d H:i:s');
			    $model->published = $published;
			}
			else
			    $model->published = null;

			if($model->save())
				$this->redirect(array('view','name'=>$model->name,'language'=>$model->language));
		}

		$this->render('edit',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param string $name
	 * @param string $language
	 */
	public function actionDelete($name, $language)
	{
		// we only allow deletion via POST request
		$this->loadModel($name, $language)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actionIndex()
	{
	    $this->redirect(array('site/index'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Page('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Page']))
			$model->attributes=$_GET['Page'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel($name, $language = null, $with_languages = false)
	{
	    $base = Page::model();
	    if ($with_languages)
	        $base = $base->with('languages');

	    if ($language === null){
    		$model=$base->findByPk(array('name' => $name, 'language' => $this->language));
    		if($model===null) {
    		    // try default language
    		    $model=$base->findByPk(array('name' => $name, 'language' => $this->sourceLanguage));
    		    if($model===null) {
    		        // try first language found
    		        $model=$base->findByAttributes(array('name' => $name));
    		    }
            }
	    }
	    else {
	        $model=$base->findByPk(array('name' => $name, 'language' => $language));
	    }
        if ($model===null)
	        throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='page-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
