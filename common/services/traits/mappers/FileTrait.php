<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\traits\mappers;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\files\components\FileManager;

/**
 * Used by services with base model having [[\cmsgears\core\common\models\traits\mappers\FileTrait]].
 *
 * @since 1.0.0
 */
trait FileTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// CategoryTrait -------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	/**
	 * @inheritdoc
	 */
	public function attachFile( $model, $file, $fileType ) {

		$fileService		= Yii::$app->factory->get( 'fileService' );
		$modelFileService	= Yii::$app->factory->get( 'modelFileService' );

		switch( $fileType ) {

			case FileManager::FILE_TYPE_IMAGE: {

				$file = $fileService->saveImage( $file );

				break;
			}
			case FileManager::FILE_TYPE_MIXED: {

				if( in_array( $file->extension, Yii::$app->fileManager->imageExtensions ) ) {

					$file = $fileService->saveImage( $file );
				}
				else {

					$file = $fileService->saveFile( $file );
				}

				break;
			}
			default: {

				$file = $fileService->saveFile( $file );

				break;
			}
		}

		// Create Model File
		if( $file->id > 0 ) {

			$modelFileService->createByParams( [ 'modelId' => $file->id, 'parentId' => $model->id, 'parentType' => $this->modelType ] );
		}

		return $file;
	}

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// CategoryTrait -------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

}
