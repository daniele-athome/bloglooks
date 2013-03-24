<?php

/**
 * This is the model class for table "attachments".
 *
 * The followings are the available columns in table 'attachments':
 * @property integer $id
 * @property integer $post_id
 * @property string $post_language
 * @property string $filename
 * @property string $mime
 * @property string $path
 */
class Attachment extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Attachment the static model class
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
		return 'attachments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('post_id, post_language, filename, path', 'required'),
			array('post_id', 'numerical', 'integerOnly'=>true),
		    array('post_language', 'length', 'max' => 6),
			array('filename, mime', 'length', 'max'=>100),
			array('path', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, post_id, post_language, filename, mime, path', 'safe', 'on'=>'search'),
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
		    'post' => array(self::BELONGS_TO, 'Post', 'post_id, post_language'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'post_id' => 'Post',
		    'post_language' => 'Language',
			'filename' => 'Filename',
			'mime' => 'Mime',
			'path' => 'Path',
		);
	}

	public function getSize()
	{
	    if (file_exists($this->path))
	        return filesize($this->path);
	    return false;
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
		$criteria->compare('post_id',$this->post_id);
		$criteria->compare('filename',$this->filename,true);
		$criteria->compare('mime',$this->mime,true);
		$criteria->compare('path',$this->path,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}