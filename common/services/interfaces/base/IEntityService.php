<?php
namespace cmsgears\core\common\services\interfaces\base;

use yii\db\ActiveRecordInterface;
use yii\db\ActiveRecord;

interface IEntityService {

	public function getModelClass();

	public function getModelTable();

	public function getParentType();

	// Data Provider ------

	public function getDataProvider( $config = [] );

	// Regular Pages

	public function getPage( $config = [] );

	// Public Pages

	public function getPublicPage( $config = [] );

	public function getPageForSimilar( $config = [] );

	// Searching

	public function getPageForSearch( $config = [] );

	// Read ---------------

	// Read - Models ---

	public function getById( $id );

	public function getByIds( $ids = [], $config = [] );

	public function getSimilar( $config = [] );

	public function getModels( $config = [] );

	public function getRandomObjects( $config = [] );

	// Read - Lists ----

	public function getList( $config = [] );

	public function getIdList( $config = [] );

	public function getNameValueList( $config = [] );

	public function getIdNameList( $config = [] );

	// Read - Maps -----

	public function getNameValueMap( $config = [] );

	public function getIdNameMap( $config = [] );

	public function getObjectMap( $config = [] );

	// Create -------------

	/**
	 * The method create adds a model with provided config.
	 *
	 * @param ActiveRecord $model
	 * @param array $config
	 */
	public function create( $model, $config = [] );

	/**
	 * The method createMultiple itertate the given array and add the models.
	 *
	 * @param array $models
	 * @param array $config
	 */
	public function createMultiple( $models, $config = [] );

	/**
	 * The method createByParams add a model using the params and also use config while adding the model.
	 *
	 * @param array $models
	 * @param array $config
	 */
	public function createByParams( $params = [], $config = [] );

	/**
	 * The method add will be used by site admin to add the model and all it's dependent models using transaction.
	 *
	 * @param ActiveRecord $model
	 * @param array $config
	 */
	public function add( $model, $config = [] );

	/**
	 * The method register will be used by site user to add the model and all it's dependent models using transaction.
	 *
	 * @param ActiveRecord $model
	 * @param array $config
	 */
	public function register( $model, $config = [] );

	// Update -------------

	public function update( $model, $config = [] );

	public function updateByParams( $params = [], $config = [] );

	public function updateAll( $model, $config = [] );

	public function updateAttributes( $model, $config = [] );

	public function updateMultiple( $models, $config = [] );

	public function updateByForm( $model, $form, $config = [] );

	public function updateMultipleByForm( $form, $config = [] );

	// Delete -------------

	public function delete( $model, $config = [] );

	public function deleteMultiple( $models, $config = [] );
}
