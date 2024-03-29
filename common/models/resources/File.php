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
use cmsgears\core\common\models\interfaces\resources\IGridCache;

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\MultiSiteTrait;
use cmsgears\core\common\models\traits\base\OwnerTrait;
use cmsgears\core\common\models\traits\base\VisibilityTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;

use cmsgears\files\components\FileManager;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * File model stores the files either locally or on file servers.
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $userId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $code
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
 * @property string $small
 * @property string $thumb
 * @property string $placeholder
 * @property string $smallPlaceholder
 * @property string $ogg
 * @property string $webm
 * @property string $caption
 * @property string $altText
 * @property string $link
 * @property boolean $shared
 * @property string $srcset
 * @property string $sizes
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
class File extends \cmsgears\core\common\models\base\Resource implements IAuthor, IData,
	IGridCache, IMultiSite, IOwner, IVisibility {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	public static $typeMap = [
		FileManager::FILE_TYPE_IMAGE => 'Image',
		FileManager::FILE_TYPE_AUDIO => 'Audio',
		FileManager::FILE_TYPE_VIDEO => 'Video',
		FileManager::FILE_TYPE_DOCUMENT => 'Document'
	];

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
	public $swidth;
	public $sheight;
	public $twidth;
	public $theight;

	// Protected --------------

	protected $modelType = CoreGlobal::TYPE_FILE;

	// Private ----------------

	// Traits ------------------------------------------------------

	use AuthorTrait;
	use DataTrait;
	use GridCacheTrait;
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
			[ [ 'name', 'extension', 'directory' ], 'required' ],
			[ 'changed', 'required', 'on' => 'upload', 'message' => 'Please provide a valid file.' ],
			[ [ 'id', 'content' ], 'safe' ],
			// Unique
			[ 'code', 'unique' ],
			// Text Limit
			[ [ 'extension', 'type', 'storage', 'srcset' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'code', 'sizes' ], 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ [ 'directory', 'altText' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ [ 'name', 'url', 'medium', 'small', 'thumb', 'placeholder', 'smallPlaceholder', 'ogg', 'webm', 'caption' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ [ 'title', 'link' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 1, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'visibility', 'width', 'height', 'mwidth', 'mheight', 'swidth', 'sheight', 'twidth', 'theight' ], 'number', 'integerOnly' => true, 'min' => 0 ],
			[ 'size', 'number', 'min' => 0 ],
			[ [ 'shared', 'changed', 'gridCacheValid' ], 'boolean' ],
			[ [ 'siteId', 'userId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name', 'title', 'description', 'extension', 'directory', 'altText', 'link' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];
			$trim[] = [ [ 'url', 'medium', 'small', 'thumb', 'placeholder', 'smallPlaceholder', 'ogg', 'webm', 'caption' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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
			'userId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'createdBy' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AUTHOR ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'code' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CODE ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'extension' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EXTENSION ),
			'directory' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DIRECTORY ),
			'size' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SIZE ),
			'url' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_URL ),
			'visibility' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VISIBILITY ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'storage' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_STORAGE ),
			'caption' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CAPTION ),
			'altText' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ALT_TEXT ),
			'link' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LINK ),
			'srcset' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_IMG_SRCSET ),
			'sizes' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_IMG_SIZES ),
			'shared' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SHARED ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
		];
	}

	// yii\db\BaseActiveRecord

    /**
     * @inheritdoc
     */
	public function beforeSave( $insert ) {

	    if( parent::beforeSave( $insert ) ) {

			// Default Site
			if( empty( $this->siteId ) || $this->siteId <= 0 ) {

				$this->siteId = Yii::$app->core->siteId;
			}

			// Default User
			if( empty( $this->userId ) || $this->userId <= 0 ) {

				$this->userId = null;
			}

			// Default Type - Default
			$this->type = $this->type ?? FileManager::FILE_TYPE_DOCUMENT;

	        return true;
	    }

		return false;
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

	public function getFileName() {

		return "{$this->name}.{$this->extension}";
	}

	public function getTempUrl() {

		return Yii::$app->fileManager->uploadUrl . '/' . CoreProperties::DIR_TEMP . $this->directory . "/" . $this->name . "." . $this->extension;
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
	 * Generate and Return small file URL using file attributes.
	 *
	 * @return string
	 */
	public function getSmallUrl() {

		if( $this->changed ) {

			return Yii::$app->fileManager->uploadUrl . '/' . CoreProperties::DIR_TEMP . $this->directory . "/" . $this->name . "-small." . $this->extension;
		}
		else if( $this->id > 0 ) {

			return Yii::$app->fileManager->uploadUrl . '/' . $this->small;
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
	 * Generate and Return placeholder URL using file attributes.
	 *
	 * @return string
	 */
	public function getPlaceholderUrl() {

		if( $this->changed ) {

			return Yii::$app->fileManager->uploadUrl . '/' . CoreProperties::DIR_TEMP . $this->directory . "/" . $this->name . "-pl." . $this->extension;
		}
		else if( $this->id > 0 ) {

			return Yii::$app->fileManager->uploadUrl . '/' . $this->placeholder;
		}

		return "";
	}

	/**
	 * Generate and Return small placeholder URL using file attributes.
	 *
	 * @return string
	 */
	public function getSmallPlaceholderUrl() {

		if( $this->changed ) {

			return Yii::$app->fileManager->uploadUrl . '/' . CoreProperties::DIR_TEMP . $this->directory . "/" . $this->name . "-small-pl." . $this->extension;
		}
		else if( $this->id > 0 ) {

			return Yii::$app->fileManager->uploadUrl . '/' . $this->smallPlaceholder;
		}

		return "";
	}

	public function getOggUrl() {

		if( !empty( $this->ogg ) ) {

			return Yii::$app->fileManager->uploadUrl . '/' . $this->ogg;
		}

		return "";
	}

	public function getWebmUrl() {

		if( !empty( $this->webm ) ) {

			return Yii::$app->fileManager->uploadUrl . '/' . $this->webm;
		}

		return "";
	}

	public function getTempPath() {

		return Yii::$app->fileManager->uploadDir . '/' . CoreProperties::DIR_TEMP . $this->directory . "/" . $this->name . "." . $this->extension;
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
	 * Return physical storage location of the small file.
	 *
	 * @return string|boolean
	 */
	public function getSmallPath() {

		if( isset( $this->small ) ) {

			return Yii::$app->fileManager->uploadDir . '/' . $this->small;
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
	 * Return physical storage location of placeholder.
	 *
	 * @return string|boolean
	 */
	public function getPlaceholderPath() {

		if( isset( $this->placeholder ) ) {

			return Yii::$app->fileManager->uploadDir . '/' . $this->placeholder;
		}

		return false;
	}

	/**
	 * Return physical storage location of small placeholder.
	 *
	 * @return string|boolean
	 */
	public function getSmallPlaceholderPath() {

		if( isset( $this->smallPlaceholder ) ) {

			return Yii::$app->fileManager->uploadDir . '/' . $this->smallPlaceholder;
		}

		return false;
	}

	public function getOggPath() {

		if( isset( $this->ogg ) ) {

			return Yii::$app->fileManager->uploadDir . '/' . $this->ogg;
		}

		return false;
	}

	public function getWebmPath() {

		if( isset( $this->webm ) ) {

			return Yii::$app->fileManager->uploadDir . '/' . $this->webm;
		}

		return false;
	}

	/**
	 * Calculate and set the file size in MB.
	 *
	 * @return void
	 */
	public function resetSize() {

		$filePath = $this->getFilePath();

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

		$filePath	= $this->getFilePath();
		$mediumPath	= $this->getMediumPath();
		$smallPath	= $this->getSmallPath();
		$thumbPath	= $this->getThumbPath();
		$plPath		= $this->getPlaceholderPath();
		$plsPath	= $this->getSmallPlaceholderPath();
		$oggPath	= $this->getOggPath();
		$webmPath	= $this->getWebmPath();

		// Delete from disk
		if( $filePath && file_exists( $filePath ) && is_file( $filePath ) ) {

			unlink( $filePath );
		}

		if( $mediumPath && file_exists( $mediumPath ) && is_file( $mediumPath ) ) {

			unlink( $mediumPath );
		}

		if( $smallPath && file_exists( $smallPath ) && is_file( $smallPath ) ) {

			unlink( $smallPath );
		}

		if( $thumbPath && file_exists( $thumbPath ) && is_file( $thumbPath ) ) {

			unlink( $thumbPath );
		}

		if( $plPath && file_exists( $plPath ) && is_file( $plPath ) ) {

			unlink( $plPath );
		}

		if( $plsPath && file_exists( $plsPath ) && is_file( $plsPath ) ) {

			unlink( $plsPath );
		}

		if( $oggPath && file_exists( $oggPath ) && is_file( $oggPath ) ) {

			unlink( $oggPath );
		}

		if( $webmPath && file_exists( $webmPath ) && is_file( $webmPath ) ) {

			unlink( $webmPath );
		}
	}

	public function generateSrcset( $background = false ) {

		$smallUrl	= !empty( $this->small ) ? $this->getSmallUrl() : null;
		$mediumUrl	= !empty( $this->medium ) ? $this->getMediumUrl() : null;
		$imageUrl	= $this->getFileUrl();

		if( $background ) {

			$srcset = [ $imageUrl ];

			if( !empty( $this->medium ) ) {

				$srcset[] = $mediumUrl;
			}

			if( !empty( $this->small ) ) {

				$srcset[] = $smallUrl;
			}

			return join( ',', $srcset );
		}
		else {

			$sizes	= preg_split( "/,/", $this->srcset );
			$srcset = !empty( $sizes[ 0 ] ) ? [ $imageUrl . ' ' . $sizes[ 0 ] . 'w' ] : [];

			if( !empty( $this->medium ) && !empty( $sizes[ 1 ] ) ) {

				$srcset[] = $mediumUrl . ' ' . $sizes[ 1 ] . 'w';
			}

			if( !empty( $this->small ) && !empty( $sizes[ 2 ] ) ) {

				$srcset[] = $smallUrl . ' ' . $sizes[ 2 ] . 'w';
			}

			return join( ',', $srcset );
		}
	}

	public function getEmbeddableCode() {

		$fileUrl	= $this->getFileUrl();
		$title		= isset( $this->title ) ? $this->title : $this->name;

		switch( $this->type ) {

			case FileManager::FILE_TYPE_IMAGE: {

				$alt = isset( $this->altText ) ? $this->altText : $this->name;

				$mediumUrl	= $this->getMediumUrl();
				$smallUrl	= $this->getSmallUrl();

				$plUrl	= $this->getPlaceholderUrl();
				$plsUrl	= $this->getSmallPlaceholderUrl();

				ob_start();
?>
<!-- Simple File -->
<div class="image-wrap">
	<img class="fluid" src="<?= $fileUrl ?>" title="<?= $title ?>" alt="<?= $alt ?>" />
<?php if( !empty( $this->caption ) ) { ?>
	<p class="image-caption"><?= $this->caption ?></p>
<?php } ?>
<?php if( !empty( $this->description ) ) { ?>
	<p class="image-desc"><?= $this->description ?></p>
<?php } ?>
</div>

<!-- Responsive -->
<div class="image-wrap">
	<img class="fluid" src="<?= $smallUrl ?>" srcset="<?= $smallUrl ?> 1x, <?= $mediumUrl ?> 1.5x, <?= $fileUrl ?> 2x" title="<?= $title ?>" sizes="(min-width: 1025px) 2x, (min-width: 481px) 1.5x, 1x" alt="<?= $alt ?>" />
<?php if( !empty( $this->caption ) ) { ?>
	<p class="image-caption"><?= $this->caption ?></p>
<?php } ?>
<?php if( !empty( $this->description ) ) { ?>
	<p class="image-desc"><?= $this->description ?></p>
<?php } ?>
</div>

<!-- Responsive & Lazy -->
<div class="image-wrap">
	<img class="fluid" src="<?= $plsUrl ?>" data-src="<?= $smallUrl ?>" data-srcset="<?= $smallUrl ?> 1x, <?= $mediumUrl ?> 1.5x, <?= $fileUrl ?> 2x" sizes="(min-width: 1025px) 2x, (min-width: 481px) 1.5x, 1x" title="<?= $title ?>" alt="<?= $alt ?>" />
<?php if( !empty( $this->caption ) ) { ?>
	<p class="image-caption"><?= $this->caption ?></p>
<?php } ?>
<?php if( !empty( $this->description ) ) { ?>
	<p class="image-desc"><?= $this->description ?></p>
<?php } ?>
</div>
<?php
				$code = ob_get_clean();

				return $code;
			}
			default: {

				return null;
			}
		}
	}

	public function getVideoTag( $config = [] ) {

		$class		= isset( $config[ 'class' ] ) ? 'class="' . $config[ 'class' ] . '"' : null;
		$autoplay	= isset( $config[ 'autoplay' ] ) ? ( $config[ 'autoplay' ] ? 'autoplay' : null ) : 'autoplay';
		$loop		= isset( $config[ 'loop' ] ) ? ( $config[ 'loop' ] ? 'loop' : null ) : 'loop';
		$muted		= isset( $config[ 'muted' ] ) ? ( $config[ 'muted' ] ? 'muted' : null ) : 'muted';
		$inline		= isset( $config[ 'inline' ] ) ? ( $config[ 'inline' ] ? 'plays-inline' : null ) : 'plays-inline';
		$controls	= isset( $config[ 'controls' ] ) ? ( $config[ 'controls' ] ? 'controls' : null ) : null;
		$poster		= isset( $config[ 'poster' ] ) ? 'poster="' . $config[ 'poster' ] . '"' : null;
		$width		= isset( $config[ 'width' ] ) ? 'width="' . $config[ 'width' ] . '"' : null;
		$height		= isset( $config[ 'height' ] ) ? 'height="' . $config[ 'height' ] . '"' : null;

		$mp4Url		= $this->getFileUrl();
		$oggUrl		= $this->getOggUrl();
		$webmUrl	= $this->getWebmUrl();
?>
<video <?= $class ?> <?= $autoplay ?> <?= $loop ?> <?= $muted ?> <?= $inline ?> <?= $controls ?> <?= $poster ?> <?= $width ?> <?= $height ?>>
	<?php if( !empty( $mp4Url ) ) { ?>
		<source src="<?= $mp4Url ?>" type="video/mp4">
	<?php } ?>
	<?php if( !empty( $oggUrl ) ) { ?>
		<source src="<?= $oggUrl ?>" type="video/ogg">
	<?php } ?>
	<?php if( !empty( $webmUrl ) ) { ?>
		<source src="<?= $webmUrl ?>" type="video/webm">
	<?php } ?>
</video>
<?php
	}

	public function isImage() {

		return $this->type == FileManager::FILE_TYPE_IMAGE || in_array( $this->extension, Yii::$app->fileManager->imageExtensions );
	}

	public function isVideo() {

		return $this->type == FileManager::FILE_TYPE_VIDEO || in_array( $this->extension, Yii::$app->fileManager->videoExtensions );
	}

	public function isAudio() {

		return $this->type == FileManager::FILE_TYPE_AUDIO || in_array( $this->extension, Yii::$app->fileManager->audioExtensions );
	}

	public function isDocument() {

		return $this->type == FileManager::FILE_TYPE_DOCUMENT || in_array( $this->extension, Yii::$app->fileManager->documentExtensions );
	}

	public function isCompressed() {

		return $this->type == FileManager::FILE_TYPE_COMPRESSED || in_array( $this->extension, Yii::$app->fileManager->compressedExtensions );
	}

	public function getFileIcon() {

		$fileIcon = 'icon cmti cmti-file';

		if( in_array( $this->extension, Yii::$app->fileManager->imageExtensions ) ) {

			$fileIcon = 'icon cmti cmti-file-image';
		}
		else if( in_array( $this->extension, Yii::$app->fileManager->videoExtensions ) ) {

			$fileIcon = 'icon cmti cmti-file-video';
		}
		else if( in_array( $this->extension, Yii::$app->fileManager->audioExtensions ) ) {

			$fileIcon = 'icon cmti cmti-file-audio';
		}
		else if( in_array( $this->extension, Yii::$app->fileManager->compressedExtensions ) ) {

			$fileIcon = 'icon cmti cmti-file-archive';
		}
		else if( in_array( $this->extension, Yii::$app->fileManager->documentExtensions ) ) {

			switch( $this->extension ) {

				case 'doc':
				case 'docx':
				case 'odt': {

					$fileIcon = 'icon cmti cmti-file-doc';

					break;
				}
				case 'ppt':
				case 'pptx':
				case 'odp': {

					$fileIcon = 'icon cmti cmti-file-ppt';

					break;
				}
				case 'xls':
				case 'ods': {

					$fileIcon = 'icon cmti cmti-file-xls';

					break;
				}
				case 'xlsx': {

					$fileIcon = 'icon cmti cmti-file-xlsx';

					break;
				}
				case 'pdf': {

					$fileIcon = 'icon cmti cmti-file-pdf';

					break;
				}
			}
		}

		return $fileIcon;
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

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'user' ];

		$config[ 'relations' ] = $relations;

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

			$file = new File();
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

		$filesToLoad = Yii::$app->request->post( $name );

		$count = count( $filesToLoad );

		if ( $count > 0 ) {

			$filesToLoad = [];

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
