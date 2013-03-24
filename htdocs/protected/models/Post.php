<?php

/**
 * This is the model class for table "posts".
 *
 * The followings are the available columns in table 'posts':
 * @property integer $id
 * @property string $language
 * @property string $title
 * @property integer $author_id
 * @property string $tags
 * @property string $created
 * @property string $modified
 * @property string $published
 * @property string $content
 */
class Post extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Post the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'posts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, language, content', 'required'),
			array('language', 'length', 'max'=>6),
		    array('author_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>200),
			array('tags, published', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, language, title, author_id, tags, created, modified, published, content', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		    // CAUTIOIN: use only on with() queries
		    'languages' => array(self::HAS_MANY, 'Post', 'id', 'select' => array('id', 'language')),
		    'author' => array(self::BELONGS_TO, 'User', 'author_id'),
		    'comments' => array(self::HAS_MANY, 'Comment', 'post_id, post_language', 'condition'=>"comments.status='".Comment::STATUS_APPROVED."'", 'order'=>'comments.timestamp DESC'),
		    'commentCount' => array(self::STAT, 'Comment', 'post_id, post_language', 'condition'=>"status='".Comment::STATUS_APPROVED."'"),
		    'attachments' => array(self::HAS_MANY, 'Attachment', 'post_id, post_language', 'order'=>'filename'),
		    'attachmentCount' => array(self::STAT, 'Attachment', 'post_id, post_language'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'language' => 'Language',
			'title' => Yii::t('Post', 'Title'),
			'tags' => 'Tags',
			'created' => 'Created',
			'modified' => 'Modified',
			'published' => Yii::t('Post', 'Published'),
			'content' => 'Content',
		);
	}

	/** Create an url for this post according to url manager rules. */
	public function getUrl($absolute = false, $language = null)
	{
	    $params = array('id' => $this->id);
	    if ($this->published) {
	        $date = strtotime($this->published);
	        $params['year'] = date('Y', $date);
	        $params['month'] = date('m', $date);
	        $params['title'] = strtolower(preg_replace(array('/\s+/', '/[^A-Za-z0-9\-]/'), array('-', ''), $this->title));
	    }
	    if ($language)
	        $params['language'] = $language;
	    return ($absolute ? Yii::app()->getBaseUrl(true) : '') . Yii::app()->createUrl('post/view', $params);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('language',$this->language,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('modified',$this->modified,true);
		$criteria->compare('published',$this->published,true);
		$criteria->compare('content',$this->content,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function byLanguage()
	{
	    $criteria=new CDbCriteria;
	    $criteria->select = "t.id, ifnull(b.title, t.title) title, max(t.published) published, group_concat(t.language) language";
	    $criteria->join = "left outer join posts b on t.id = b.id and b.language = :language";
	    $criteria->group = 't.id, ifnull(b.title, t.title)';
	    $criteria->params = array(':language' => Controller::getLanguage());
	    $criteria->order = 't.published IS NULL DESC, t.published DESC, t.id DESC';

	    return new CActiveDataProvider($this, array(
	        'criteria'=>$criteria,
	    ));
	}

	/**
	 * @return array a list of links that point to the post list filtered by every tag of this post
	 */
	public function getTagLinks($class='label label-info')
	{
	    $links=array();
	    foreach(Tag::string2array($this->tags) as $tag)
	        $links[]=CHtml::link(CHtml::encode($tag), array('post/index', 'tag'=>$tag), array('class' => $class));
	    return $links;
	}

	/**
	 * Normalizes the user-entered tags.
	 */
	public function normalizeTags($attribute,$params)
	{
	    $this->tags=Tag::array2string(array_unique(Tag::string2array($this->tags)));
	}

	/**
	 * This is invoked before the record is saved.
	 * @return boolean whether the record should be saved.
	 */
	protected function beforeSave()
	{
	    if(parent::beforeSave())
	    {
	        if($this->isNewRecord)
	        {
	            $this->created = $this->modified = date('Y-m-d H:i:s');
	            $this->author_id = Yii::app()->user->id;
	        }
	        else
	            $this->modified = date('Y-m-d H:i:s');
	        return true;
	    }
	    else
	        return false;
	}

	/**
	 * Adds a new comment to this post.
	 * This method will set status and post_id of the comment accordingly.
	 * @param Comment the comment to be added
	 * @return boolean whether the comment is saved successfully
	 */
	public function addComment($comment)
	{
	    $comment->status=Configuration::get('comment_initial_status', Comment::STATUS_APPROVED);
	    $comment->post_id=$this->id;
	    $comment->post_language=$this->language;
	    return $comment->save();
	}

}
