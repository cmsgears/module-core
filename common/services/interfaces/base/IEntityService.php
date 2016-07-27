<?php
namespace cmsgears\core\common\services\interfaces\base;

interface IEntityService {

	public function getModelClass();

	public function getModelTable();

	public function getParentType();

	// Data Provider ------

	public function getDataProvider( $config = [] );

	public function getPage( $config = [] );

	public function getPageForSearch( $config = [] );

	// Read ---------------

    // Read - Models ---

	public function getById( $id );

	public function getByIds( $ids = [], $config = [] );

	public function getModels( $config = [] );

    // Read - Lists ----

	public function getList( $config = [] );

	public function getNameValueList( $config = [] );

	public function getIdNameList( $config = [] );

    // Read - Maps -----

	public function getNameValueMap( $config = [] );

	public function getIdNameMap( $config = [] );

	public function getObjectMap( $config = [] );

	// Create -------------

 	public function create( $model, $config = [] );

	public function createByParams( $params = [], $config = [] );

	// Update -------------

	public function update( $model, $config = [] );

	public function updateAll( $model, $config = [] );

	public function updateAttributes( $model, $config = [] );

	public function updateMultiple( $models, $config = [] );

	public function updateByForm( $model, $form, $config = [] );

	public function updateMultipleByForm( $form, $config = [] );

	// Delete -------------

	public function delete( $model, $config = [] );
}
