<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\config\CoreProperties;

use cmsgears\core\common\behaviors\AuthorBehavior;

use cmsgears\core\common\models\traits\CreateModifyTrait;

/**
 * CmgFile Entity
 *
 * @property int $id
 * @property int $createdBy
 * @property int $modifiedBy
 * @property string $title
 * @property string $description
 * @property string $name
 * @property string $extension
 * @property string $directory
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property integer $visibility
 * @property string $type
 * @property string $url
 * @property string $thumb
 * @property string $altText
 * @property string $link
 */
class CmgFile extends CmgEntity {

	const VISIBILITY_PUBLIC		=  0;
	const VISIBILITY_PRIVATE	= 10;

	public static $typeMap = [
		self::VISIBILITY_PUBLIC => "public",
		self::VISIBILITY_PRIVATE => "private"
	];

	/**
	 * @property boolean - used to detect whether the file is changed by user.
	 */
	public $changed;

	// optional properties for image processing
	public $width;
	public $height;
	public $twidth;
	public $theight;

	use CreateModifyTrait;

	// Instance Methods --------------------------------------------

	public function getTypeStr() {

		return self::$typeMap[ $this->type ];	
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

            'authorBehavior' => [
                'class' => AuthorBehavior::className()
			],
            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'createdAt',
 				'updatedAtAttribute' => 'modifiedAt',
 				'value' => new Expression('NOW()')
            ]
        ];
    }

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

		// model rules
        $rules = [
            [ [ 'createdBy', 'name', 'extension', 'directory', 'url' ], 'required' ],
            [ [ 'id', 'title', 'description', 'altText', 'visibility', 'thumb', 'link', 'changed' ], 'safe' ],
            [ [ 'name', 'directory' ], 'string', 'min' => 1, 'max' => 150 ],
            [ [ 'extension', 'type' ], 'string', 'min' => 1, 'max' => 100 ],
            [ [ 'width', 'height', 'twidth', 'theight' ], 'safe' ],
            [ [ 'width', 'height', 'twidth', 'theight' ], 'number', 'integerOnly' => true, 'min' => 0 ],
            [ [ 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

		// trim if required
		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'name', 'extension', 'directory', 'title', 'description', 'altText' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'createdBy' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_AUTHOR ),
			'title' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'description' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'extension' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_EXTENSION ),
			'directory' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DIRECTORY ),
			'url' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_URL ),
			'visibility' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_VISIBILITY ),
			'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
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

		$file->load( Yii::$app->request->post(), $name );

		return $file;
	}
}

?>