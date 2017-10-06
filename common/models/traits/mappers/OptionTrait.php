<?php
namespace cmsgears\core\common\models\traits\mappers;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Option;
use cmsgears\core\common\models\mappers\ModelOption;

/**
 * OptionTrait can be used to map options to relevant models.
 */
trait OptionTrait {

	public function getModelOptions() {

		return $this->hasMany( ModelOption::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$this->modelType'" );
	}

	public function getActiveModelOptions() {

		$modelOptionTable	= CoreTables::TABLE_MODEL_OPTION;

		return $this->hasMany( ModelOption::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$this->modelType' AND $modelOptionTable.active=1" );
	}

	public function getOptions() {

		$optionTable	= CoreTables::TABLE_OPTION;

		$query = $this->hasMany( Option::className(), [ 'id' => 'modelId' ] )
					->viaTable( CoreTables::TABLE_MODEL_OPTION, [ 'parentId' => 'id' ], function( $query ) {

						$modelOptionTable	= CoreTables::TABLE_MODEL_OPTION;

						$query->onCondition( [ "$modelOptionTable.parentType" => $this->modelType ] );
					});

		return $query;
	}

	public function getActiveOptions() {

		$optionTable	= CoreTables::TABLE_OPTION;

		$query = $this->hasMany( Option::className(), [ 'id' => 'modelId' ] )
					->viaTable( CoreTables::TABLE_MODEL_OPTION, [ 'parentId' => 'id' ], function( $query ) {

						$modelOptionTable	= CoreTables::TABLE_MODEL_OPTION;

						$query->onCondition( [ "$modelOptionTable.parentType" => $this->modelType, "$modelOptionTable.active" => true ] );
					});

		return $query;
	}

	public function getOptionIdListByCategoryId( $categoryId, $active = true ) {

		$options		= null;
		$optionsList	= [];

		if( $active ) {

			$options = $this->getActiveOptions()->where( [ 'categoryId' => $categoryId ] )->all();
		}
		else {

			$options = $this->getOptions()->where( [ 'categoryId' => $categoryId ] )->all();
		}

		foreach ( $options as $option ) {

			array_push( $optionsList, $option->id );
		}

		return $optionsList;
	}

	public function getOptionsCsvByCategorySlug( $categorySlug, $active = true ) {

		$categoryTable	= CoreTables::TABLE_CATEGORY;
		$options		= null;
		$optionsCsv		= [];

		if( $active ) {

			$options	= $this->getActiveOptions()->leftJoin( $categoryTable, "$categoryTable.id=categoryId" )->where( [ "$categoryTable.slug" => $categorySlug ] )->all();
		}
		else {

			$options	= $this->getOptions()->leftJoin( $categoryTable, "$categoryTable.id=categoryId" )->where( [ "$categoryTable.slug" => $categorySlug ] )->all();
		}

		foreach ( $options as $option ) {

			$optionsCsv[] = $option->name;
		}

		return implode( ", ", $optionsCsv );
	}

	public function getOptionsByCategorySlug( $categorySlug, $active = true ) {

		$categoryTable	= CoreTables::TABLE_CATEGORY;

		if( $active ) {

			return $this->getActiveOptions()->leftJoin( $categoryTable, "$categoryTable.id=categoryId" )->where( [ "$categoryTable.slug" => $categorySlug ] )->all();
		}
		else {

			return $this->getOptions()->leftJoin( $categoryTable, "$categoryTable.id=categoryId" )->where( [ "$categoryTable.slug" => $categorySlug ] )->all();
		}
	}
}
