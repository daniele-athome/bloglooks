<?php

/**
 * This is the model class for table "pages".
 *
 * The followings are the available columns in table 'pages':
 * @property string $name
 * @property string $title
 * @property int $author_id
 * @property string $created
 * @property string $modified
 * @property string $published
 * @property string $content
 * @property integer $order
 */
class Page extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Page the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'pages';
	}

	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, language, title, author_id, created, content', 'required'),
		    array('author_id', 'numerical', 'integerOnly'=>true),
			array('order', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
			array('title', 'length', 'max'=>200),
			array('published', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('name, language, title, created, published, content, order', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
		    // CAUTIOIN: use only on with() queries
		    'languages' => array(self::HAS_MANY, 'Page', 'name', 'select' => array('name', 'language')),
		    'author' => array(self::BELONGS_TO, 'User', 'author_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'name' => Yii::t('Page', 'Name'),
		    'language' => 'Language',
			'title' => Yii::t('Page', 'Title'),
			'created' => 'Created',
		    'modified' => 'Modified',
			'published' => 'Published',
			'content' => 'Content',
			'order' => 'Order',
		);
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

		$criteria->compare('name',$this->name,true);
		$criteria->compare('language',$this->language,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('published',$this->published,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('order',$this->order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function byLanguage()
	{
		$criteria=new CDbCriteria;
		$criteria->select = "t.name, ifnull(b.title, t.title) title, group_concat(t.language) language";
		$criteria->join = "left outer join pages b on t.name = b.name and b.language = :language";
		$criteria->group = 't.name, ifnull(b.title, t.title)';
		$criteria->params = array(':language' => Controller::getLanguage());
		$criteria->order = 't.name';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
