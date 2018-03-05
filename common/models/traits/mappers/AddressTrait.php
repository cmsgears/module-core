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
use cmsgears\core\common\models\base\CoreTables;
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

		$modelAddressTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_ADDRESS );

		return $this->hasMany( ModelAddress::className(), [ 'parentId' => 'id' ] )
			->where( "$modelAddressTable.parentType='$this->modelType'" );
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveModelAddresses() {

		$modelAddressTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_ADDRESS );

		return $this->hasMany( ModelAddress::className(), [ 'parentId' => 'id' ] )
			->where( "$modelAddressTable.parentType='$this->modelType' AND $modelAddressTable.active=1" );
	}

	/**
	 * @inheritdoc
	 */
	public function getModelAddressesByType( $type, $active = true ) {

		$modelAddressTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_ADDRESS );

		return $this->hasOne( ModelAddress::className(), [ 'parentId' => 'id' ] )
			->where( "$modelAddressTable.parentType=:ptype AND $modelAddressTable.type=:type AND $modelAddressTable.active=:active", [ ':ptype' => $this->modelType, ':type' => $type, ':active' => $active ] )->all();
	}

	/**
	 * @inheritdoc
	 */
	public function getAddresses() {

		$modelAddressTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_ADDRESS );

		return $this->hasMany( Address::className(), [ 'id' => 'modelId' ] )
			->viaTable( $modelAddressTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$modelAddressTable ) {

					$query->onCondition( [ "$modelAddressTable.parentType" => $this->modelType ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getActiveAddresses() {

		$modelAddressTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_ADDRESS );

		return $this->hasMany( Address::className(), [ 'id' => 'modelId' ] )
			->viaTable( $modelAddressTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$modelAddressTable ) {

					$query->onCondition( [ "$modelAddressTable.parentType" => $this->modelType, "$modelAddressTable.active" => true ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getAddressesByType( $type, $active = true ) {

		$modelAddressTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_ADDRESS );

		return $this->hasMany( Address::className(), [ 'id' => 'modelId' ] )
			->viaTable( $modelAddressTable, [ 'parentId' => 'id' ],
				function( $query ) use( &$type, &$active, &$modelAddressTable ) {

					$query->onCondition( [ "$modelAddressTable.parentType" => $this->modelType, "$modelAddressTable.type" => $type, "$modelAddressTable.active" => $active ] );
				}
			)->all();
	}

	// Some useful methods in case model allows only one address for specific address type.

	/**
	 * @inheritdoc
	 */
	public function getDefaultAddress() {

		$modelAddressTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_ADDRESS );

		return $this->hasOne( Address::className(), [ 'id' => 'modelId' ] )
			->viaTable( $modelAddressTable, [ 'parentId' => 'id' ],
				function( $query, $type = Address::TYPE_DEFAULT ) use( &$modelAddressTable ) {

					$query->onCondition( "$modelAddressTable.parentType=:ptype AND $modelAddressTable.type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getPrimaryAddress() {

		$modelAddressTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_ADDRESS );

		return $this->hasOne( Address::className(), [ 'id' => 'modelId' ] )
			->viaTable( $modelAddressTable, [ 'parentId' => 'id' ],
				function( $query, $type = Address::TYPE_PRIMARY ) use( &$modelAddressTable ) {

					$query->onCondition( "$modelAddressTable.parentType=:ptype AND $modelAddressTable.type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getResidentialAddress() {

		$modelAddressTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_ADDRESS );

		return $this->hasOne( Address::className(), [ 'id' => 'modelId' ] )
			->viaTable( $modelAddressTable, [ 'parentId' => 'id' ],
				function( $query, $type = Address::TYPE_RESIDENTIAL ) use( &$modelAddressTable ) {

					$query->onCondition( "$modelAddressTable.parentType=:ptype AND $modelAddressTable.type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getShippingAddress() {

		$modelAddressTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_ADDRESS );

		return $this->hasOne( Address::className(), [ 'id' => 'modelId' ] )
			->viaTable( $modelAddressTable, [ 'parentId' => 'id' ],
				function( $query, $type = Address::TYPE_SHIPPING ) use( &$modelAddressTable ) {

					$query->onCondition( "$modelAddressTable.parentType=:ptype AND $modelAddressTable.type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getBillingAddress() {

		$modelAddressTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_ADDRESS );

		return $this->hasOne( Address::className(), [ 'id' => 'modelId' ] )
			->viaTable( $modelAddressTable, [ 'parentId' => 'id' ],
				function( $query, $type = Address::TYPE_BILLING ) use( &$modelAddressTable ) {

					$query->onCondition( "$modelAddressTable.parentType=:ptype AND $modelAddressTable.type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getOfficeAddress() {

		$modelAddressTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_ADDRESS );

		return $this->hasOne( Address::className(), [ 'id' => 'modelId' ] )
			->viaTable( $modelAddressTable, [ 'parentId' => 'id' ],
				function( $query, $type = Address::TYPE_OFFICE ) use( &$modelAddressTable ) {

					$query->onCondition( "$modelAddressTable.parentType=:ptype AND $modelAddressTable.type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getMailingAddress() {

		$modelAddressTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_ADDRESS );

		return $this->hasOne( Address::className(), [ 'id' => 'modelId' ] )
			->viaTable( $modelAddressTable, [ 'parentId' => 'id' ],
				function( $query, $type = Address::TYPE_MAILING ) use( &$modelAddressTable ) {

					$query->onCondition( "$modelAddressTable.parentType=:ptype AND $modelAddressTable.type=:type", [ ':ptype' => $this->modelType, ':type' => $type ] );
				}
			);
	}

	/**
	 * @inheritdoc
	 */
	public function getBranchAddress() {

		$modelAddressTable = CoreTables::getTableName( CoreTables::TABLE_MODEL_ADDRESS );

		return $this->hasOne( Address::className(), [ 'id' => 'modelId' ] )
			->viaTable( $modelAddressTable, [ 'parentId' => 'id' ],
				function( $query, $type = Address::TYPE_BRANCH ) use( &$modelAddressTable ) {

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
