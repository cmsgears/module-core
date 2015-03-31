<?php
namespace cmsgears\core\common\models\entities;

// CMG Imports
use cmsgears\core\common\config\CoreProperties;

class CmgFile extends CmgEntity {

	// Pre-Defined File Types
	const TYPE_PUBLIC		= 0;
	const TYPE_PRIVATE		= 1;

	public static $typeMap = [
		self::TYPE_PUBLIC => "public",
		self::TYPE_PRIVATE => "private"
	];

	public $changed;

	// Instance Methods --------------------------------------------

	public function getAuthor() {

		return $this->hasOne( User::className(), [ 'id' => 'authorId' ] );
	}

	public function getTypeStr() {

		return self::$typeMap[ $this->type ];	
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
            [ [ 'name', 'authorId', 'extension', 'directory', 'url' ], 'required' ],
            [ [ 'id', 'description', 'altText', 'thumb', 'changed' ], 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'name' => 'Name',
			'authorId' => 'Author',
			'description' => 'Description'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord

	public static function tableName() {

		return CoreTables::TABLE_FILE;
	}

	// CmgFile

	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}
}

?>