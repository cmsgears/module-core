<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\resources;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\config\CoreProperties;

use cmsgears\core\common\models\interfaces\base\IAuthor;
use cmsgears\core\common\models\interfaces\base\IMultiSite;
use cmsgears\core\common\models\interfaces\base\IOwner;
use cmsgears\core\common\models\interfaces\base\IVisibility;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IModelMeta;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\base\Resource;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\MultiSiteTrait;
use cmsgears\core\common\models\traits\base\OwnerTrait;
use cmsgears\core\common\models\traits\base\VisibilityTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\ModelMetaTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * File model stores the files either locally or on file servers.
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $tag
 * @property string $title
 * @property string $description
 * @property string $extension
 * @property string $directory
 * @property float $size
 * @property integer $visibility
 * @property string $type
 * @property string $storage
 * @property string $url
 * @property string $medium
 * @property string $thumb
 * @property string $altText
 * @property string $link
 * @property boolean $shared
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $content
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class File extends Resource implements IAuthor, IData, IModelMeta, IMultiSite, IOwner, IVisibility {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	/**
	 * Check whether the file is changed by user.
	 *
	 * @property boolean
	 */
	public $changed;

	// Optional properties for image processing.
	public $width;
	public $height;
	public $mwidth;
	public $mheight;
	public $twidth;
	public $theight;

	// Protected --------------

	protected $modelType = CoreGlobal::TYPE_FILE;

	// Private ----------------

	// Traits ------------------------------------------------------

	use AuthorTrait;
	use DataTrait;
	use ModelMetaTrait;
	use MultiSiteTrait;
	use OwnerTrait;
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
				'class' => AuthorBehavior::class
			],
			'timestampBehavior' => [
				'class' => TimestampBehavior::class,
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

		// Model Rules
		$rules = [
			// Required, Safe
			[ [ 'siteId', 'name', 'extension', 'directory' ], 'required' ],
			[ [ 'id', 'content', 'data', 'gridCache' ], 'safe' ],
			// Text Limit
			[ [ 'extension', 'type', 'storage' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'name', 'tag', 'directory' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ [ 'title', 'url', 'medium', 'thumb', 'altText', 'link' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 1, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'visibility', 'width', 'height', 'mwidth', 'mheight', 'twidth', 'theight' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ 'size', 'number', 'min' => 0 ],
			[ [ 'shared', 'changed', 'gridCacheValid' ], 'boolean' ],
			[ [ 'siteId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name', 'title', 'description', 'extension', 'directory', 'altText', 'url', 'medium', 'thumb', 'link' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'siteId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SITE ),
			'createdBy' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AUTHOR ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'tag' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TAG ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'extension' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EXTENSION ),
			'directory' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DIRECTORY ),
			'size' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SIZE ),
			'url' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_URL ),
			'visibility' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VISIBILITY ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'link' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LINK ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// File ----------------------------------

	/**
	 * Returns string representation of [[$type]].
	 *
	 * @return boolean
	 */
	public function getTypeStr() {

		return self::$typeMap[ $this->type ];
	}

	/**
	 * Returns string representation of [[$shared]].
	 *
	 * @return boolean
	 */
	public function getSharedStr() {

		return Yii::$app->formatter->asBoolean( $this->shared );
	}

	/**
	 * Generate and Return file URL using file attributes.
	 *
	 * @return string
	 */
	public function getFileUrl() {

		if( $this->changed ) {

			return Yii::$app->fileManager->uploadUrl . '/' . CoreProperties::DIR_TEMP . $this->directory . "/" . $this->name . "." . $this->extension;
		}
		else if( $this->id > 0 ) {

			return Yii::$app->fileManager->uploadUrl . '/' . $this->url;
		}

		return "";
	}

	/**
	 * Generate and Return medium file URL using file attributes.
	 *
	 * @return string
	 */
	public function getMediumUrl() {

		if( $this->changed ) {

			return Yii::$app->fileManager->uploadUrl . '/' . CoreProperties::DIR_TEMP . $this->directory . "/" . $this->name . "-medium." . $this->extension;
		}
		else if( $this->id > 0 ) {

			return Yii::$app->fileManager->uploadUrl . '/' . $this->medium;
		}

		return "";
	}

	/**
	 * Generate and Return thumb URL using file attributes.
	 *
	 * @return string
	 */
	public function getThumbUrl() {

		if( $this->changed ) {

			return Yii::$app->fileManager->uploadUrl . '/' . CoreProperties::DIR_TEMP . $this->directory . "/" . $this->name . "-thumb." . $this->extension;
		}
		else if( $this->id > 0 ) {

			return Yii::$app->fileManager->uploadUrl . '/' . $this->thumb;
		}

		return "";
	}

	/**
	 * Return physical storage location of the file.
	 *
	 * @return string|boolean
	 */
	public function getFilePath() {

		if( isset( $this->url ) ) {

			return Yii::$app->fileManager->uploadDir . '/' . $this->url;
		}

		return false;
	}

	/**
	 * Return physical storage location of the medium file.
	 *
	 * @return string|boolean
	 */
	public function getMediumPath() {

		if( isset( $this->medium ) ) {

			return Yii::$app->fileManager->uploadDir . '/' . $this->medium;
		}

		return false;
	}

	/**
	 * Return physical storage location of thumb.
	 *
	 * @return string|boolean
	 */
	public function getThumbPath() {

		if( isset( $this->thumb ) ) {

			return Yii::$app->fileManager->uploadDir . '/' . $this->thumb;
		}

		return false;
	}

	/**
	 * Calculate and set the file size in MB.
	 *
	 * @return void
	 */
	public function resetSize() {

		$filePath	= $this->getFilePath();

		if( $filePath && file_exists( $filePath ) && is_file( $filePath ) ) {

			$size		= filesize( $filePath ); // bytes
			$sizeInMb	= $size / pow( 1024, 2 );

			$this->size = round( $sizeInMb, 4 ); // Round upto 4 precision, expected size at least in kb
		}
	}

	/**
	 * Delete all the associated files from disk. Useful while updating file.
	 *
	 * @return void
	 */
	public function clearDisk() {

		$filePath		= $this->getFilePath();
		$mediumPath		= $this->getMediumPath();
		$thumbPath		= $this->getThumbPath();

		// Delete from disk
		if( $filePath && file_exists( $filePath ) && is_file( $filePath ) ) {

			unlink( $filePath );
		}

		if( $mediumPath && file_exists( $mediumPath ) && is_file( $mediumPath ) ) {

			unlink( $mediumPath );
		}

		if( $thumbPath && file_exists( $thumbPath ) && is_file( $thumbPath ) ) {

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

		return CoreTables::getTableName( CoreTables::TABLE_FILE );
	}

	// CMG parent classes --------------------

	// File ----------------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'creator', 'modifier' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	/**
	 * Load the file attributes submitted by form and return the updated file model.
	 *
	 * @param File $file
	 * @param string $name
	 * @return File - after loading from request url
	 */
	public static function loadFile( $file, $name ) {

		if( !isset( $file ) ) {

			$file	= new File();
		}

		$file->load( Yii::$app->request->post(), $name );

		return $file;
	}

	/**
	 * Load file attributes submitted by form and return the updated file models.
	 *
	 * @param string $name
	 * @param File[] $files
	 * @return File - after loading from request url
	 */
	public function loadFiles( $name, $files = [] ) {

		$filesToLoad	= Yii::$app->request->post( $name );
		$count			= count( $filesToLoad );

		if ( $count > 0 ) {

			$filesToLoad  = [];

			// TODO: Use existing file models using $files param.
			for( $i = 0; $i < $count; $i++ ) {

				$filesToLoad[] = new File();
			}

			File::loadMultiple( $filesToLoad, Yii::$app->request->post(), $name );

			return $filesToLoad;
		}

		return $files;
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
