<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use yii\db\ActiveRecord;

use cmsgears\core\common\utilities\MessageUtil;

class Newsletter extends NamedActiveRecord {

	// Instance Methods --------------------------------------------
	
	// db columns
	
	public function getId() {

		return $this->newsletter_id;
	}

	public function getName() {
		
		return $this->newsletter_name;	
	}

	public function setName( $name ) {
		
		$this->newsletter_name = $name;
	}

	public function getDesc() {
		
		return $this->newsletter_desc;	
	}

	public function setDesc( $desc ) {
		
		$this->newsletter_desc = $desc;
	}

	public function getContent() {
		
		return $this->newsletter_content;
	}

	public function setContent( $content ) {
		
		$this->newsletter_content = $content;	
	}

	public function getCreatedOn() {
		
		return $this->newsletter_created_on;
	}
	
	public function setCreatedOn( $date ) {
		
		$this->newsletter_created_on = $date;
	}

	public function getUpdatedOn() {
		
		return $this->newsletter_updated_on;
	}
	
	public function setUpdatedOn( $updatedOn ) {
		
		$this->newsletter_updated_on = $updatedOn;
	}

	public function getLastSentOn() {
		
		return $this->newsletter_last_sent_on;
	}
	
	public function setLastSentOn( $updatedOn ) {
		
		$this->newsletter_last_sent_on = $updatedOn;
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'newsletter_name' ], 'required' ],
            [ 'newsletter_name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'newsletter_name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ 'newsletter_name', 'alphanumhyphenspace' ],
            [ [ 'newsletter_id', 'newsletter_desc', 'newsletter_content' ], 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'newsletter_name' => 'Name',
			'newsletter_desc' => 'Description',
			'newsletter_content' => ''
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord

	public static function tableName() {

		return CoreTables::TABLE_NEWSLETTER;
	}

	// Newsletter

	public static function findById( $id ) {

		return Newsletter::find()->where( 'newsletter_id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByName( $name ) {

		return Newsletter::find()->where( 'newsletter_name=:name', [ ':name' => $name ] )->one();
	}
}

?>