<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use yii\db\ActiveRecord;

// CMG Imports
use cmsgears\core\common\config\CoreProperties;

class CmgFile extends ActiveRecord {

	// Pre-Defined File Types
	const TYPE_PUBLIC		= 0;
	const TYPE_PRIVATE		= 1;

	public static $typeMap = [
		self::TYPE_PUBLIC => "public",
		self::TYPE_PRIVATE => "private"
	];

	public $changed;

	// Instance Methods --------------------------------------------

	// db columns

	public function getId() {
		
		return $this->file_id;
	}

	public function unsetId() {
		
		unset( $this->file_id );
	}

	public function getAuthorId() {
		
		return $this->file_author;
	}

	public function getAuthor() {

		return $this->hasOne( User::className(), [ 'user_id' => 'file_author' ] );
	}

	public function setAuthorId( $authorId ) {
		
		$this->file_author = $authorId;
	}

	public function getName() {
		
		return $this->file_name;	
	}

	public function setName( $name ) {
		
		$this->file_name = $name;	
	}

	public function getDesc() {
		
		return $this->file_desc;	
	}

	public function setDesc( $desc ) {

		$this->file_desc = $desc;	
	}

	public function getExtension() {

		return $this->file_extension;	
	}

	public function setExtension( $extension ) {

		$this->file_extension = $extension;	
	}

	public function getDirectory() {

		return $this->file_directory;	
	}

	public function setDirectory( $directory ) {

		$this->file_directory = $directory;	
	}

	public function getCreatedOn() {

		return $this->file_created_on;	
	}

	public function setCreatedOn( $createdOn ) {

		$this->file_created_on = $createdOn;	
	}

	public function getUpdatedOn() {

		return $this->file_updated_on;	
	}

	public function setUpdatedOn( $updatedOn ) {

		$this->file_updated_on = $updatedOn;	
	}

	public function getType() {

		return $this->file_type;
	}

	public function getTypeStr() {

		return self::$typeMap[ $this->file_type ];	
	}

	public function setType( $type ) {

		$this->file_type = $type;	
	}

	public function getUrl() {

		return $this->file_url;	
	}

	public function setUrl( $url ) {

		$this->file_url = $url;	
	}

	public function getThumb() {
		
		return $this->file_thumb;	
	}

	public function setThumb( $thumb ) {
		
		$this->file_thumb = $thumb;	
	}

	public function getAltText() {
		
		return $this->file_alt_text;	
	}

	public function setAltText( $altText ) {
		
		$this->file_alt_text = $altText;	
	}

	public function getDisplayUrl() {

		if( $this->changed ) {

			return CoreProperties::DIR_TEMP . $this->directory . "/" . $this->name . "." . $this->extension;
		}
		else if( $this->id > 0 ) {

			return $this->url;
		}

		return "";
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'file_name', 'file_author', 'file_extension', 'file_directory', 'file_url' ], 'required' ],
            [ [ 'file_id', 'file_desc', 'file_alt_text', 'file_thumb', 'changed' ], 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'file_name' => 'Name',
			'file_author' => 'Author',
			'file_desc' => 'Description'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord

	public static function tableName() {

		return CoreTables::TABLE_FILE;
	}

	// CmgFile

	public static function findById( $id ) {

		return self::find()->where( 'file_id=:id', [ ':id' => $id ] )->one();
	}
}

?>