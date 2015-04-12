<?php
namespace cmsgears\core\common\models\entities;

// CMG Imports
use cmsgears\core\common\config\CoreProperties;

/**
 * Config Entity
 *
 * @property integer $id
 * @property integer $authorId
 * @property string $name
 * @property string $description
 * @property string $extension
 * @property string $directory
 * @property datetime $createdAt
 * @property datetime $updatedAt
 * @property integer $type
 * @property string $url
 * @property string $thumb
 * @property string $altText
 */
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

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'authorId', 'name', 'extension', 'directory', 'url' ], 'required' ],
            [ [ 'id', 'type', 'description', 'altText', 'thumb', 'changed' ], 'safe' ]
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

	// yii\db\ActiveRecord ----------------

	public static function tableName() {

		return CoreTables::TABLE_FILE;
	}

	// CmgFile ----------------------------

	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByAuthorId( $authorId ) {

		return self::find()->where( 'authorId=:id', [ ':id' => $authorId ] )->all();
	}
}

?>