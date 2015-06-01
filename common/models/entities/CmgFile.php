<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\config\CoreProperties;

use cmsgears\core\common\models\traits\CreateModifyTrait;

/**
 * CmgFile Entity
 *
 * @property int $id
 * @property int $createdBy
 * @property int $modifiedBy
 * @property string $name
 * @property string $description
 * @property string $extension
 * @property string $directory
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property integer $type
 * @property string $url
 * @property string $thumb
 * @property string $altText
 * @property string $link
 */
class CmgFile extends CmgEntity {

	// Pre-Defined File Types
	const TYPE_PUBLIC		= 0;
	const TYPE_PRIVATE		= 1;

	public static $typeMap = [
		self::TYPE_PUBLIC => "public",
		self::TYPE_PRIVATE => "private"
	];

	/**
	 * @property boolean - used to detect whether the file is changed by user.
	 */
	public $changed;

	use CreateModifyTrait;

	// Instance Methods --------------------------------------------

	public function getTypeStr() {

		return self::$typeMap[ $this->type ];	
	}

	/**
	 * @return boolean - whether given user is owner
	 */
	public function checkOwner( $user ) {
		
		return $this->createdBy	= $user->id;		
	}

	/**
	 * The method returns the file url for the file.
	 */
	public function getFileUrl() {

		if( $this->changed ) {

			return Yii::$app->fileManager->uploadUrl . CoreProperties::DIR_TEMP . $this->directory . "/" . $this->name . "." . $this->extension;
		}
		else if( $this->id > 0 ) {

			return Yii::$app->fileManager->uploadUrl . $this->url;
		}

		return "";
	}

	/**
	 * The method returns the thumb url for the file. It's common usage is for images.
	 */
	public function getThumbUrl() {

		if( $this->changed ) {

			return Yii::$app->fileManager->uploadUrl . CoreProperties::DIR_TEMP . $this->directory . "/" . $this->name . "-thumb." . $this->extension;
		}
		else if( $this->id > 0 ) {

			return Yii::$app->fileManager->uploadUrl . $this->thumb;
		}

		return "";
	}

	// yii\base\Component ----------------

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [

            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'createdAt',
 				'updatedAtAttribute' => 'modifiedAt'
            ]
        ];
    }

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'createdBy', 'name', 'extension', 'directory', 'url' ], 'required' ],
            [ [ 'id', 'type', 'description', 'altText', 'thumb', 'changed', 'link' ], 'safe' ],
            [ [ 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'createdBy' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_AUTHOR ),
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'description' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'extension' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_EXTENSION ),
			'directory' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DIRECTORY ),
			'url' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_URL ),
			'link' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_LINK )
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ----------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_FILE;
	}

	// CmgFile ----------------------------

	/**
	 * @param CmgFile $file
	 * @param string $name
	 * @return CmgFile - after loading from request url
	 */
	public static function loadFile( $file, $name ) {

		if( !isset( $file ) ) {

			$file	= new CmgFile();
		}

		$file->load( Yii::$app->request->post( $name ), "" );

		return $file;
	}

	/**
	 * @param int $id
	 * @return CmgFile - by id
	 */
	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}
}

?>