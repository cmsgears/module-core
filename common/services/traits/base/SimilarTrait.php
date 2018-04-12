<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\services\traits\base;

/**
 * SimilarTrait provide methods specific to featured models.
 *
 * @since 1.0.0
 */
trait SimilarTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// SimilarTrait --------------------------

	// Data Provider ------

	/**
	 * @return - DataProvider or array of models with applied configuration.
	 */
	public function getPageForSimilar( $config = [] ) {

		$config[ 'query' ] = $this->generateSimilarQuery( $config );

		return $this->getPublicPage( $config );
	}

	// Similar query considering category and tag for similarity
	protected function generateSimilarQuery( $config = [] ) {

		// Model Class and Table
		$modelClass	= static::$modelClass;
		$modelTable = $modelClass::tableName();

		$parentType			= isset( $config[ 'parentType' ] ) ? $config[ 'parentType' ] : static::$parentType;
		$modelId			= isset( $config[ 'modelId' ] ) ? $config[ 'modelId' ] : null;
		$mcategoryTable		= Yii::$app->factory->get( 'modelCategoryService' )->getModelTable();
		$categoryTable		= Yii::$app->factory->get( 'categoryService' )->getModelTable();
		$mtagTable			= Yii::$app->factory->get( 'modelTagService' )->getModelTable();
		$tagTable			= Yii::$app->factory->get( 'tagService' )->getModelTable();
		$filter				= null;

		// Search Query
		$query				= isset( $config[ 'query' ] ) ? $config[ 'query' ] : $modelClass::find();
		$hasOne				= isset( $config[ 'hasOne' ] ) ? $config[ 'hasOne' ] : false;

		// Use model joins
		if( $hasOne ) {

			$query = $modelClass::queryWithHasOne();
		}

		// Tags
		if( isset( $config[ 'tags' ] ) && count( $config[ 'tags' ] ) > 0 ) {

			$query->leftJoin( $mtagTable, "$modelTable.id=$mtagTable.parentId AND $mtagTable.parentType='$parentType' AND $mtagTable.active=TRUE" )
			->leftJoin( $tagTable, "$mtagTable.modelId=$tagTable.id" );

			// Exclude current model
			if( $modelId != null ) {

				$query->andWhere( "$mtagTable.parentId != :modelId", [ ':modelId' => $modelId ] );
			}

			$filter	= "$tagTable.id in( " . join( ",", $config[ 'tags' ] ). ")";
		}

		// Categories
		if( isset( $config[ 'categories' ] ) && count( $config[ 'categories' ] ) > 0 ) {

			$query->leftJoin( "$mcategoryTable", "$modelTable.id=$mcategoryTable.parentId AND $mcategoryTable.parentType='$parentType' AND $mcategoryTable.active=TRUE" )
			->leftJoin( "$categoryTable", "$mcategoryTable.modelId=$categoryTable.id" );

			// Exclude current model
			if( $modelId != null ) {

				if( count( $config[ 'tags' ] ) > 0 ) {

					$query->orWhere( "$mcategoryTable.parentId != :modelId", [ ':modelId' => $modelId ] );
				}
				else {

					$query->andWhere( "$mcategoryTable.parentId != :modelId", [ ':modelId' => $modelId ] );
				}
			}

			$filter	= "$categoryTable.id in( " . join( ",", $config[ 'categories' ] ). ")";
		}

		// Mixed
		if( isset( $config[ 'tags' ] ) && count( $config[ 'tags' ] ) > 0 && isset( $config[ 'categories' ] ) && count( $config[ 'categories' ] ) > 0 ) {

			$filter	= "( $tagTable.id in( " . join( ",", $config[ 'tags' ] ). ") OR $categoryTable.id in( " . join( ",", $config[ 'categories' ] ). ") )";
		}

		if( isset( $filter ) ) {

			$query->andWhere( $filter );
		}

		return $query;
	}

	// Read ---------------

	// Read - Models ---

	public function getSimilar( $config = [] ) {

		$limit	= isset( $config[ 'limit' ] ) ? $config[ 'limit' ] : 5;
		$query	= $this->generateSimilarQuery( $config );

		$query->limit( $limit );

        return $query->all();
	}

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	// Create -------------

	// Update -------------

	// Delete -------------

	// Bulk ---------------

	// Notifications ------

	// Cache --------------

	// Additional ---------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// SimilarTrait --------------------------

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
