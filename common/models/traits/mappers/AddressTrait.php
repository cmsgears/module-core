<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\mappers;

// CMG Imports
use cmsgears\core\common\models\resources\Address;
use cmsgears\core\common\models\mappers\ModelAddress;

/**
 * It provide methods specific to managing address mappings of respective models.
 */
trait AddressTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// AddressTrait --------------------------

	/**
	 * @inheritdoc
	 */
	public function getModelAddresses() {

		$modelAddressTable = ModelAddress::tableName();

		return $this->hasMany( ModelAddress::class, [ 'parentId' => 'id' ] )
			->where( "$modelAddressTable.parentType=:ptype", [ ':ptype' => $this->modelType ] )
			->orderBy( "$modelAddressTable.id DESC" );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveModelAddresses() {

		$modelAddressTable = ModelAddress::tableName();

		return $this->hasMany( ModelAddress::class, [ 'parentId' => 'id' ] )
			->where( "$modelAddressTable.parentType=:ptype AND $modelAddressTable.active=:active", [ ':ptype' => $this->modelType, ':active' => true ] )
			->orderBy( "$modelAddressTable.id DESC" );
	}

	/**
	 * @inheritdoc
	 */
	public function getModelAddressesByType( $type, $active = true ) {

		$modelAddressTable = ModelAddress::tableName();

		return $this->hasOne( ModelAddress::class, [ 'parentId' => 'id' ] )
			->where( "$modelAddressTable.parentType=:ptype AND $modelAddressTable.type=:type AND $modelAddressTable.active=:active", [ ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )
			->orderBy( "$modelAddressTable.id DESC" )
			->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getAddresses() {

		$addressTable		= Address::tableName();
		$modelAddressTable	= ModelAddress::tableName();

		return Address::find()
			->leftJoin( $modelAddressTable, "$modelAddressTable.modelId=$addressTable.id" )
			->where( "$modelAddressTable.parentId=:pid AND $modelAddressTable.parentType=:ptype", [ ':pid' => $this->id, ':ptype' => $this->modelType ] )
			->orderBy( [ "$modelAddressTable.order" => SORT_DESC, "$modelAddressTable.id" => SORT_DESC ] );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveAddresses() {

		$addressTable		= Address::tableName();
		$modelAddressTable	= ModelAddress::tableName();

		return Address::find()
			->leftJoin( $modelAddressTable, "$modelAddressTable.modelId=$addressTable.id" )
			->where( "$modelAddressTable.parentId=:pid AND $modelAddressTable.parentType=:ptype AND $modelAddressTable.active=:active", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':active' => true ] )
			->orderBy( [ "$modelAddressTable.order" => SORT_DESC, "$modelAddressTable.id" => SORT_DESC ] );
	}

	/**
	 * @inheritdoc
	 */
	public function getAddressesByType( $type, $active = true ) {

		$addressTable		= Address::tableName();
		$modelAddressTable	= ModelAddress::tableName();

		return Address::find()
			->leftJoin( $modelAddressTable, "$modelAddressTable.modelId=$addressTable.id" )
			->where( "$modelAddressTable.parentId=:pid AND $modelAddressTable.parentType=:ptype AND $modelAddressTable.type=:type AND $modelAddressTable.active=:active", [ ':pid' => $this->id, ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )
			->orderBy( [ "$modelAddressTable.order" => SORT_DESC, "$modelAddressTable.id" => SORT_DESC ] )
			->all();
	}

	// Some useful methods in case model allows only one address for specific address type.

	/**
	 * @inheritdoc
	 */
	public function getDefaultAddress() {

		$modelAddressTable = ModelAddress::tableName();

		return $this->hasOne( Address::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelAddressTable, [ 'parentId' => 'id' ],
				function( $query, $type = Address::TYPE_DEFAULT ) use( $modelAddressTable ) {
					$query->onCondition( "$modelAddressTable.parentType=:ptype AND $modelAddressTable.type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getPrimaryAddress() {

		$modelAddressTable = ModelAddress::tableName();

		return $this->hasOne( Address::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelAddressTable, [ 'parentId' => 'id' ],
				function( $query, $type = Address::TYPE_PRIMARY ) use( $modelAddressTable ) {
					$query->onCondition( "$modelAddressTable.parentType=:ptype AND $modelAddressTable.type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getResidentialAddress() {

		$modelAddressTable = ModelAddress::tableName();

		return $this->hasOne( Address::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelAddressTable, [ 'parentId' => 'id' ],
				function( $query, $type = Address::TYPE_RESIDENTIAL ) use( $modelAddressTable ) {
					$query->onCondition( "$modelAddressTable.parentType=:ptype AND $modelAddressTable.type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getShippingAddress() {

		$modelAddressTable = ModelAddress::tableName();

		return $this->hasOne( Address::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelAddressTable, [ 'parentId' => 'id' ],
				function( $query, $type = Address::TYPE_SHIPPING ) use( $modelAddressTable ) {
					$query->onCondition( "$modelAddressTable.parentType=:ptype AND $modelAddressTable.type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getBillingAddress() {

		$modelAddressTable = ModelAddress::tableName();

		return $this->hasOne( Address::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelAddressTable, [ 'parentId' => 'id' ],
				function( $query, $type = Address::TYPE_BILLING ) use( $modelAddressTable ) {
					$query->onCondition( "$modelAddressTable.parentType=:ptype AND $modelAddressTable.type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getOfficeAddress() {

		$modelAddressTable = ModelAddress::tableName();

		return $this->hasOne( Address::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelAddressTable, [ 'parentId' => 'id' ],
				function( $query, $type = Address::TYPE_OFFICE ) use( $modelAddressTable ) {
					$query->onCondition( "$modelAddressTable.parentType=:ptype AND $modelAddressTable.type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getMailingAddress() {

		$modelAddressTable = ModelAddress::tableName();

		return $this->hasOne( Address::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelAddressTable, [ 'parentId' => 'id' ],
				function( $query, $type = Address::TYPE_MAILING ) use( $modelAddressTable ) {
					$query->onCondition( "$modelAddressTable.parentType=:ptype AND $modelAddressTable.type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getBranchAddress() {

		$modelAddressTable = ModelAddress::tableName();

		return $this->hasOne( Address::class, [ 'id' => 'modelId' ] )
			->viaTable( $modelAddressTable, [ 'parentId' => 'id' ],
				function( $query, $type = Address::TYPE_BRANCH ) use( $modelAddressTable ) {
					$query->onCondition( "$modelAddressTable.parentType=:ptype AND $modelAddressTable.type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] );
				}
			);
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// AddressTrait --------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
