<?php
namespace cmsgears\core\common\services\traits;

// Yii Imports
use \Yii;
use yii\db\Query;
/**
 * Used by services with base model having unique name.
 */
trait MultiSiteTrait {

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// SlugTrait -----------------------------

	// Data Provider ------

	// Read ---------------

	// Read - Models ---

	// Read - Lists ----

	// Read - Maps -----

	// Read - Others ---

	public function getIdNameList( $config = [] ) {

		$query		= new Query;
		$siteId		= Yii::$app->core->siteId;
		$condition	= $query->andWhere(['siteId' => $siteId]);
		$config['query'] = $condition;
		
		return static::findIdNameList( $config );
	}
	
	// Create -------------

	// Update -------------

	// Delete -------------

	// Static Methods ----------------------------------------------

	// CMG parent classes --------------------

	// SlugTrait -----------------------------

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
