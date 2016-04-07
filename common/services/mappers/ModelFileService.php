<?php
namespace cmsgears\core\common\services\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\mappers\ModelFile;

use cmsgears\core\common\services\resources\FileService;

/**
 * The class ModelFileService is base class to perform database activities for ModelFile Entity.
 */
class ModelFileService extends \cmsgears\core\common\services\base\Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function findByParentType( $parentType ) {

		return ModelFile::findByParentType( $parentType );
	}

	public static function findByParentId( $parentId ) {

		return ModelFile::findByParentId( $parentId );
	}

	public static function findByFileId( $parentId, $parentType, $fileId ) {

		return ModelFile::findByFileId( $parentId, $parentType, $fileId );
	}

	public static function findByFileTitle( $parentId, $parentType, $fileTitle ) {

		return ModelFile::findByFileTitle( $parentId, $parentType, $fileTitle );
	}

	public static function findByFileTitleLike( $parentId, $parentType, $likeTitle ) {

		return ModelFile::findByFileTitleLike( $parentId, $parentType, $likeTitle );
	}

	// Create ----------------

	public static function create( $model ) {

		$model->save();
	}

	// Update ----------------

	public static function createOrUpdateByTitle( $parent, $parentType, $file ) {

		if( isset( $file ) && isset( $file->title ) ) {

			$fileModel	= ModelFile::findByFileTitle( $parent->id, $parentType, $file->title );

			if( isset( $fileModel ) ) {

				FileService::saveFile( $file, [ 'model' => $fileModel, 'attribute' => 'fileId' ] );

				$fileModel->update();
			}
			else {

				$fileModel				= new ModelFile();

				$fileModel->parentId	= $parent->id;
				$fileModel->parentType	= $parentType;

				FileService::saveFile( $file, [ 'model' => $fileModel, 'attribute' => 'fileId' ] );

				$fileModel->save();
			}

			return $fileModel;
		}
 	}

	// Delete ----------------

	public static function delete( $model, $deleteFile = true ) {

		// Find File
		$file	= $model->file;

		// Delete Model File
		$model->delete();

		// Delete File
		if( $deleteFile ) {

			FileService::delete( $file );
		}

		return true;
	}
}

?>