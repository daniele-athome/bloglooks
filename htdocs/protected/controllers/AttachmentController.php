<?php

class AttachmentController extends Controller
{
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
			array('allow',
				'actions'=>array('download'),
				'users'=>array('*'),
			),
			array('allow',
				'actions'=>array('create','update','delete'),
		        'roles' => array('admin', 'editor'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionDownload($id)
	{
	    $model = $this->loadModel($id);
	    if (file_exists($model->path))
	        $this->renderPartial('download', array('model' => $model));
	    else
	        throw new CHttpException(404,'The requested page does not exist.');
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel($id)
	{
		$model=Attachment::model()->findByPk($id);
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
