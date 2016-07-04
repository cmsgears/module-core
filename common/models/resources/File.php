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

use cmsgears\core\common\models\interfaces\IVisibility;

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\traits\CreateModifyTrait;
use cmsgears\core\common\models\traits\interfaces\VisibilityTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * File Entity
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
class File extends \cmsgears\core\common\models\base\Resource implements IVisibility {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

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

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use CreateModifyTrait;
	use VisibilityTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

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

	// yii\base\Model ---------

    /**
     * @inheritdoc
     */
    public function rules() {

        // model rules
        $rules = [
            [ [ 'name', 'extension', 'directory' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ [ 'extension', 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
            [ [ 'title', 'directory' ], 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
            [ [ 'name', 'description', 'altText', 'url', 'medium', 'thumb', 'link' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
            [ [ 'visibility', 'width', 'height', 'mwidth', 'mheight', 'twidth', 'theight' ], 'number', 'integerOnly' => true, 'min' => 0 ],
            [ [ 'size' ], 'number', 'min' => 0 ],
            [ [ 'shared', 'changed' ], 'boolean' ],
            [ [ 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

        // trim if required
        if( Yii::$app->core->trimFieldValue ) {

            $trim[] = [ [ 'name', 'extension', 'directory', 'title', 'description', 'altText', 'url', 'medium', 'thumb', 'link' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

            return ArrayHelper::merge( $trim, $rules );
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'createdBy' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AUTHOR ),
            'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
            'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
            'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
            'extension' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EXTENSION ),
            'directory' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DIRECTORY ),
            'size' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SIZE ),
            'url' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_URL ),
            'visibility' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VISIBILITY ),
            'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
            'link' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LINK )
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// File ----------------------------------

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

    public function getFilePath() {

		if( isset( $this->url ) ) {

			return Yii::$app->fileManager->uploadDir . $this->url;
		}

		return false;
    }

    public function getMediumPath() {

		if( isset( $this->medium ) ) {

			return Yii::$app->fileManager->uploadDir . $this->medium;
		}

		return false;
    }

    public function getThumbPath() {

		if( isset( $this->thumb ) ) {

			return Yii::$app->fileManager->uploadDir . $this->thumb;
		}

		return false;
    }

	/**
	 * Delete all the associated files from disk. Useful while updating file.
	 */
	public function clearDisk() {

		$filePath		= $this->getFilePath();
    	$mediumPath		= $this->getMediumPath();
    	$thumbPath		= $this->getThumbPath();

		// Delete from disk
		if( $filePath ) {

			unlink( $filePath );
		}

		if( $mediumPath ) {

			unlink( $mediumPath );
		}

		if( $thumbPath ) {

			unlink( $thumbPath );
		}
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_FILE;
    }

	// CMG parent classes --------------------

	// File ----------------------------------

	// Read - Query -----------

	// Read - Find ------------

    /**
     * @param File $file
     * @param string $name
     * @return File - after loading from request url
     */
    public static function loadFile( $file, $name ) {

        if( !isset( $file ) ) {

            $file   = new File();
        }

        $file->load( Yii::$app->request->post(), $name );

        return $file;
    }

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}

?>