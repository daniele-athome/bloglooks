<?php

class PostController extends Controller
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
			array('allow',
				'actions'=>array('index','feed','archives','view'),
				'users'=>array('*'),
			),
			array('allow',
				'actions'=>array('new','edit', 'admin','delete'),
		        'roles' => array('admin', 'editor'),
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex($tag=null,$author=null)
	{
	    $criteria=new CDbCriteria;
	    $criteria->order = 't.published DESC';
	    $criteria->addCondition('t.published IS NOT NULL');
	    $criteria->addColumnCondition(array('t.language' => $this->language));
	    if ($tag)
	        $criteria->addSearchCondition('t.tags', $tag);
	    if ($author)
	        $criteria->addColumnCondition(array('t.author_id' => $author));

	    $dataProvider=new CActiveDataProvider('Post', array(
	        'pagination'=>array(
	            'pageSize'=>$this->config['posts_per_page'],
	            'pageVar' => 'page',
	        ),
	        'criteria'=>$criteria,
	    ));

	    $this->render('index',array(
	        'dataProvider'=>$dataProvider,
	        'tag' => $tag,
	        'author' => User::model()->findByPk($author),
	    ));
	}

	public function actionFeed($type='atom', $language=null)
	{
	    Yii::import('ext.feed.*');
	    $types = array('atom' => EFeed::ATOM, 'rss' => Efeed::RSS2);

	    if (!array_key_exists($type, $types)) throw new CHttpException(400, 'Bad request.');

	    // extract the latest N posts from database
	    $n = intval($this->config['feed_posts']);
	    $criteria = new CDbCriteria();
	    $criteria->select = array('id', 'title', 'content', 'published', 'author_id');
	    $criteria->with = 'author';
	    $criteria->addCondition('published IS NOT NULL');
	    $criteria->addColumnCondition(array('language' => $language ? $language : $this->language));
	    $criteria->limit = $n;
	    $criteria->order = '`published` DESC';
	    $posts = Post::model()->findAll($criteria);

	    $f = new EFeed($types[$type]);
	    $f->title = $this->config['blog_name'];
	    $f->description = $this->config['blog_description'];
	    $f->link = Yii::app()->getBaseUrl(true);

	    if ($type == 'rss') {
	        if (count($posts) > 0) {
	            $f->addChannelTag('language', $language);
	            $f->addChannelTag('updated', date(DATE_RSS, strtotime($posts[0]->published)));

	            foreach ($posts as $post) {
	                $item = $f->createNewItem();
	                $item->title = $post->title;
	                $item->link = $post->getUrl(true);
	                $item->date = date(DATE_RSS, strtotime($posts[0]->published));
	                $item->addTag('author', $post->author->name);

	                /*
	                $markup = new CMarkdown();
	                ob_start();
	                $markup->processOutput($post->content);
	                $item->description = ob_get_clean();
	                */

	                $f->addItem($item);
	            }
	        }
	    }

	    elseif ($type == 'atom') {
	        if (count($posts) > 0) {
	            $f->addChannelTag('language', $language);
	            $f->addChannelTag('updated', date(DATE_ATOM, strtotime($posts[0]->published)));

	            foreach ($posts as $post) {
	                $item = $f->createNewItem();
	                $item->title = $post->title;
	                $item->link = $post->getUrl(true);
	                $item->author = $post->author->name;
	                $item->date = date(DATE_ATOM, strtotime($posts[0]->published));

	                $markup = new CMarkdown();
	                ob_start();
	                $markup->processOutput($post->content);
	                $item->content = ob_get_clean();

	                $f->addItem($item);
	            }
	        }
	    }

	    $f->generateFeed();
	    Yii::app()->end();
	}

	public function actionArchives($year, $month)
	{
	    $criteria=new CDbCriteria;
	    $criteria->order = 'published DESC';
	    $criteria->addCondition('published IS NOT NULL');
	    $criteria->addColumnCondition(array('language' => $this->language));
	    $criteria->addCondition('published BETWEEN :date AND (DATE_ADD(:date, INTERVAL 1 MONTH) - INTERVAL 1 SECOND)');
	    $criteria->params[':date'] = sprintf('%d-%02d-01', intval($year), intval($month));

	    $dataProvider=new CActiveDataProvider('Post', array(
	        'pagination'=>array(
	            'pageSize'=>$this->config['posts_per_page'],
	        ),
	        'criteria'=>$criteria,
	    ));

	    $this->render('archives',array(
	        'dataProvider'=>$dataProvider,
	        'year' => $year,
	        'month' => $month,
	    ));
	}

	public function actionView($id, $language = null)
	{
	    $model = $this->loadModel($id, $language, true);

	    // controllare se il post e' stata pubblicato prima di visualizzarla
	    if (!$model->published and $model->author->id != Yii::app()->user->getId() and Yii::app()->user->checkAccess('admin'))
	        throw new CHttpException(404, 'The requested page does not exist.');

	    $comment=$this->newComment($model);
	    $this->render('view',array(
			'model'=>$model,
		    'comment'=>$comment,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionNew($id=null, $language=null)
	{
		$model=new Post;
		if ($id)
		    $model->id = $id;
		$model->language = $language ? $language : $this->language;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Post']))
		{
			$model->attributes=$_POST['Post'];
			$model->language = $language ? $language : $this->language;

			if ($model->published)
			    $model->published = date('Y-m-d H:i:s');
			else
			    $model->published = null;

			if($model->save())
				$this->redirect(array('view','id'=>$model->id,'language'=>$model->language));
		}

		$this->render('new',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id id of the post
	 * @param string $language language of the post
	 */
	public function actionEdit($id, $language)
	{
		$model=$this->loadModel($id, $language);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Post']))
		{
		    // keep old published value
		    $published = $model->published;

			$model->attributes=$_POST['Post'];

			if ($model->published) {
			    if (!$published)
			        $published = date('Y-m-d H:i:s');
			    $model->published = $published;
			}
			else
			    $model->published = null;

			if($model->save())
				$this->redirect(array('view','id'=>$model->id,'language'=>$model->language));
		}

		$this->render('edit',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id
	 * @param string $language
	 */
	public function actionDelete($id, $language)
	{
		// we only allow deletion via POST request
		$this->loadModel($id, $language)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Post('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Post']))
			$model->attributes=$_GET['Post'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel($id, $language = null, $with_languages = false)
	{
	    $base = Post::model();
	    if ($with_languages)
	        $base = $base->with('languages');

	    if ($language === null){
    		$model=$base->findByPk(array('id' => $id, 'language' => $this->language));
    		if($model===null) {
    		    // try default language
    		    $model=$base->findByPk(array('id' => $id, 'language' => $this->sourceLanguage));
    		    if($model===null) {
    		        // try first language found
    		        $model=$base->findByAttributes(array('id' => $id));
    		    }
            }
	    }
	    else {
	        $model=$base->findByPk(array('id' => $id, 'language' => $language));
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

	/**
	 * Creates a new comment.
	 * This method attempts to create a new comment based on the user input.
	 * If the comment is successfully created, the browser will be redirected
	 * to show the created comment.
	 * @param Post the post that the new comment belongs to
	 * @return Comment the comment instance
	 */
	protected function newComment($post)
	{
	    $comment=new Comment;
	    if(isset($_POST['Comment']))
	    {
	        $comment->scenario = 'resetPasswordWithCaptcha';
	        $comment->attributes=$_POST['Comment'];

	        if ($comment->validate()) {
    	        if($post->addComment($comment, false))
    	        {
    	            if($comment->status==Comment::STATUS_PENDING)
    	                Yii::app()->user->setFlash('commentSubmitted', Yii::t('Post', '<strong>Thanks!</strong> Your comment will be posted once it is approved.'));
    	            $this->refresh();
    	        }
	        }
	    }
	    return $comment;
	}

}
