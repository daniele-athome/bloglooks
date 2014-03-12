<?php

/**
 * This is the model class for table "comments".
 *
 * The followings are the available columns in table 'comments':
 * @property integer $id
 * @property integer $post_id
 * @property string $content
 * @property string $status
 * @property string $timestamp
 * @property integer $author_id
 * @property integer $reply_to
 */
class Comment extends CActiveRecord
{

    const STATUS_PENDING='P';
    const STATUS_APPROVED='A';

    public $captcha;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Comment the static model class
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
		return 'comments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.

		$rules = array(
			array('content', 'required'),
			array('post_id, author_id, reply_to', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'max'=>1),
		    array('anon_name', 'length', 'max' => 50),
		    array('anon_email', 'length', 'max' => 100),
		    array('captcha', 'application.extensions.recaptcha.EReCaptchaValidator',
		        'privateKey' => Yii::app()->params['recaptcha']['privateKey'],
		        'on' => 'resetPasswordWithCaptcha'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, post_id, content, status, timestamp, author_id', 'safe', 'on'=>'search'),
		);

		if (Yii::app()->user->isGuest) {
		    $rules[] = array('anon_name', 'required');
		    $rules[] = array('anon_email', 'required');
		}

		return $rules;
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
		    'author' => array(self::BELONGS_TO, 'User', 'author_id'),
			'follow_up' => array(self::BELONGS_TO, 'Comment', 'reply_to'),
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
			'content' => 'Content',
			'status' => 'Status',
			'timestamp' => 'Timestamp',
			'author_id' => 'Author',
		    'anon_name' => 'Name',
		    'anon_email' => 'Email',
			'reply_to' => 'Reply to',
		);
	}

	/**
	 * Approves a comment.
	 */
	public function approve()
	{
	    $this->status=self::STATUS_APPROVED;
	    $this->update(array('status'));
	    $this->notifyUsers();
	}

	/**
	 * @param Post the post that this comment belongs to. If null, the method
	 * will query for the post.
	 * @return string the permalink URL for this comment
	 */
	public function getUrl($post=null, $absolute=false)
	{
	    if($post===null)
	        $post=$this->post;
	    return $post->getUrl($absolute).'#c'.$this->id;
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
		$criteria->compare('content',$this->content,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('timestamp',$this->timestamp,true);
		$criteria->compare('author_id',$this->author_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * This is invoked before the record is saved.
	 * @return boolean whether the record should be saved.
	 */
	protected function beforeSave()
	{
	    if(parent::beforeSave())
	    {
	        if($this->isNewRecord) {
	            $this->timestamp = date('Y-m-d H:i:s');
	            $this->author_id = Yii::app()->user->id;
	        }
	        return true;
	    }
	    else
	        return false;
	}

	public function notifyAdmins()
	{
	    // send comment notification e-mail to admins
	    $admins = User::model()->findAllByAttributes(array('role' => 'admin'));
	    $to = array();
	    foreach ($admins as $adm) {
	        // do not send email to the author
	        if ($adm->id != $this->author->id)
    	        $to[$adm->login] = $adm->name;
	    }

	    $mail = new YiiMailer('comment', array('comment' => $this));
	    $mail->setFrom(Yii::app()->params['adminEmail']);
	    $mail->setTo($to);
	    $mail->setSubject(Yii::t("Post", "New comment to post #{id}: {title}",
	        array('{id}' => $this->post_id, '{title}' => $this->post->title)));

	    return $mail->send();
	}

	/**
	 * Sends e-mail notifications to all users that have posted a comment to
	 * this post.
	 */
	public function notifyUsers()
	{
	    $users = array();
	    foreach ($this->post->comments as $comment) {
	        $to = false;

	        if ($comment->author) {
	            if ($comment->author->role != 'admin')
	                $to = $comment->author->login;
	        }
	        else {
	            $to = $comment->anon_email;
	        }

	        if ($to and !in_array($to, $users))
	            $users[] = $to;
	    }

	    foreach ($users as $to) {
	        $mail = new YiiMailer('comment', array('comment' => $this));
	        $mail->setFrom(Yii::app()->params['adminEmail']);
	        $mail->setTo($to);
	        $mail->setSubject(Yii::t("Post", "New comment to post: {title}",
	                array('{title}' => $this->post->title)));

	        return $mail->send();
	    }
	}

}
