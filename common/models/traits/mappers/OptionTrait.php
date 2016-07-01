<?php
namespace cmsgears\core\common\models\traits\mappers;

// Yii Import
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Option;
use cmsgears\core\common\models\mappers\ModelOption;

/**
 * OptionTrait can be used to map options to relevant models.
 */
trait OptionTrait {

	public function getModelOptions() {

    	return $this->hasMany( ModelOption::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$this->parentType'" );
	}

	public function getOptions() {

		$optionTable	= CoreTables::TABLE_OPTION;

    	$query = $this->hasMany( Option::className(), [ 'id' => 'modelId' ] )
					->viaTable( CoreTables::TABLE_MODEL_OPTION, [ 'parentId' => 'id' ], function( $query ) {

						$modelOptionTable	= CoreTables::TABLE_MODEL_OPTION;

                      	$query->onCondition( [ "$modelOptionTable.parentType" => $this->parentType ] );
					});

		return $query;
	}

	public function getActiveOptions() {

		$optionTable	= CoreTables::TABLE_OPTION;

    	$query = $this->hasMany( Option::className(), [ 'id' => 'modelId' ] )
					->viaTable( CoreTables::TABLE_MODEL_OPTION, [ 'parentId' => 'id' ], function( $query ) {

						$modelOptionTable	= CoreTables::TABLE_MODEL_OPTION;

                      	$query->onCondition( [ "$modelOptionTable.parentType" => $this->parentType, "$modelOptionTable.active" => true ] );
					});

		return $query;
	}

	public function getOptionIdListByCategory( $category ) {

    	$options 		= $this->getActiveOptions()->where( [ 'categoryId' => $category->id ] )->all();
		$optionsList	= [];

		foreach ( $options as $option ) {

			array_push( $optionsList, $option->id );
		}

		return $optionsList;
	}

	public function getOptionsByCategorySlug( $slug ) {

		$categoryTable	= CoreTables::TABLE_CATEGORY;
    	$options 		= $this->getActiveOptions()->leftJoin( $categoryTable, "$categoryTable.id=categoryId" )->where( [ "$categoryTable.slug" => $slug ] )->all();

		return $options;
	}
}

?>