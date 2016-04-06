<?php
namespace cmsgears\core\common\models\resources;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\config\CoreProperties;

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\traits\CreateModifyTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * CmgFile Entity
 *
 * @property long $id
 * @property long $createdBy
 * @property long $modifiedBy
 * @property string $title
 * @property string $description
 * @property string $name
 * @property string $extension
 * @property string $directory
 * @property float $size
 * @property integer $visibility
 * @property string $type
 * @property string $url
 * @property string $medium
 * @property string $thumb
 * @property string $altText
 * @property string $link
 * @property boolean $shared
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 */
class CmgFile extends \cmsgears\core\common\models\base\CmgEntity {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    const VISIBILITY_PUBLIC     =  0;
    const VISIBILITY_PRIVATE    = 10;

    public static $typeMap = [
        self::VISIBILITY_PUBLIC => 'public',
        self::VISIBILITY_PRIVATE => 'private'
    ];

    // Public -------------

    /**
     * @property boolean - used to detect whether the file is changed by user.
     */
    public $changed;

    // optional properties for image processing
    public $width;
    public $height;
    public $mwidth;
    public $mheight;
    public $twidth;
    public $theight;

    // Private/Protected --

    // Traits ------------------------------------------------------

    use CreateModifyTrait;

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

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
            [ [ 'name', 'extension', 'directory' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ [ 'extension', 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->mediumText ],
            [ [ 'title', 'name', 'directory' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->largeText ],
            [ [ 'description', 'altText', 'url', 'thumb', 'link' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->extraLargeText ],
            [ [ 'visibility', 'width', 'height', 'mwidth', 'mheight', 'twidth', 'theight' ], 'number', 'integerOnly' => true, 'min' => 0 ],
            [ [ 'size' ], 'number', 'min' => 0 ],
            [ [ 'shared', 'changed' ], 'boolean' ],
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
            'size' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_SIZE ),
            'url' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_URL ),
            'visibility' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_VISIBILITY ),
            'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
            'link' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_LINK )
        ];
    }

    // CmgFile ----------------------------

    public function getTypeStr() {

        return self::$typeMap[ $this->type ];
    }

    public function getSharedStr() {

        return Yii::$app->formatter->asBoolean( $this->shared );
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

    public function getMediumUrl() {

        if( $this->changed ) {

            return Yii::$app->fileManager->uploadUrl . CoreProperties::DIR_TEMP . $this->directory . "/" . $this->name . "-medium." . $this->extension;
        }
        else if( $this->id > 0 ) {

            return Yii::$app->fileManager->uploadUrl . $this->medium;
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

    // Static Methods ----------------------------------------------

    // yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_FILE;
    }

    /**
     * @param CmgFile $file
     * @param string $name
     * @return CmgFile - after loading from request url
     */
    public static function loadFile( $file, $name ) {

        if( !isset( $file ) ) {

            $file   = new CmgFile();
        }

        $file->load( Yii::$app->request->post(), $name );

        return $file;
    }

    // Create -------------

    // Read ---------------

    // Update -------------

    // Delete -------------
}

?>