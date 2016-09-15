<?php
namespace cmsgears\core\common\models\traits\mappers;

// Yii Import
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Address;
use cmsgears\core\common\models\mappers\ModelAddress;

/**
 * AddressTrait can be used to add address feature to relevant models. The model must define the member variable $addressType which is unique for the model.
 */
trait AddressTrait {

    /**
     * @return array - ModelAddress associated with parent
     */
    public function getModelAddresses() {

        return $this->hasMany( ModelAddress::className(), [ 'parentId' => 'id' ] )
                    ->where( "parentType='$this->mParentType'" );
    }

    /**
     * @return ModelAddress associated with parent
     */
    public function getModelAddressByType( $type ) {

        return $this->hasOne( ModelAddress::className(), [ 'parentId' => 'id' ] )
                    ->where( "parentType=:ptype AND type=:type", [ ':ptype' => $this->mParentType, ':type' => $type ] )->one();
    }

    /**
     * @return Address - associated with parent having type set to primary
     */
    public function getPrimaryAddress() {

        return $this->hasOne( Address::className(), [ 'id' => 'modelId' ] )
                    ->viaTable( CoreTables::TABLE_MODEL_ADDRESS, [ 'parentId' => 'id' ], function( $query, $type = Address::TYPE_PRIMARY ) {

                        $modelAddress	= CoreTables::TABLE_MODEL_ADDRESS;

                        $query->onCondition( "$modelAddress.parentType=:ptype AND $modelAddress.type=:type", [ ':ptype' => $this->mParentType, ':type' => $type ] );
                    });
    }

    /**
     * @return Address - associated with parent having type set to residential
     */
    public function getResidentialAddress() {

        return $this->hasOne( Address::className(), [ 'id' => 'modelId' ] )
                    ->viaTable( CoreTables::TABLE_MODEL_ADDRESS, [ 'parentId' => 'id' ], function( $query, $type = Address::TYPE_RESIDENTIAL ) {

                        $modelAddress	= CoreTables::TABLE_MODEL_ADDRESS;

                        $query->onCondition( "$modelAddress.parentType=:ptype AND $modelAddress.type=:type", [ ':ptype' => $this->mParentType, ':type' => $type ] );
                    });
    }

    /**
     * @return Address - associated with parent having type set to shipping
     */
    public function getShippingAddress() {

        return $this->hasOne( Address::className(), [ 'id' => 'modelId' ] )
                    ->viaTable( CoreTables::TABLE_MODEL_ADDRESS, [ 'parentId' => 'id' ], function( $query, $type = Address::TYPE_SHIPPING ) {

                        $modelAddress	= CoreTables::TABLE_MODEL_ADDRESS;

                        $query->onCondition( "$modelAddress.parentType=:ptype AND $modelAddress.type=:type", [ ':ptype' => $this->mParentType, ':type' => $type ] );
                    });
    }

    /**
     * @return Address - associated with parent having type set to billing
     */
    public function getBillingAddress() {

        return $this->hasOne( Address::className(), [ 'id' => 'modelId' ] )
                    ->viaTable( CoreTables::TABLE_MODEL_ADDRESS, [ 'parentId' => 'id' ], function( $query, $type = Address::TYPE_BILLING ) {

                        $modelAddress	= CoreTables::TABLE_MODEL_ADDRESS;

                        $query->onCondition( "$modelAddress.parentType=:ptype AND $modelAddress.type=:type", [ ':ptype' => $this->mParentType, ':type' => $type ] );
                    });
    }

    /**
     * @return Address - associated with parent having type set to office
     */
    public function getOfficeAddress() {

        return $this->hasOne( Address::className(), [ 'id' => 'modelId' ] )
                    ->viaTable( CoreTables::TABLE_MODEL_ADDRESS, [ 'parentId' => 'id' ], function( $query, $type = Address::TYPE_OFFICE ) {

                        $modelAddress	= CoreTables::TABLE_MODEL_ADDRESS;

                        $query->onCondition( "$modelAddress.parentType=:ptype AND $modelAddress.type=:type", [ ':ptype' => $this->mParentType, ':type' => $type ] );
                    });
    }

    /**
     * @return Address - associated with parent having type set to mailing
     */
    public function getMailingAddress() {

        return $this->hasOne( Address::className(), [ 'id' => 'modelId' ] )
                    ->viaTable( CoreTables::TABLE_MODEL_ADDRESS, [ 'parentId' => 'id' ], function( $query, $type = Address::TYPE_MAILING ) {

                        $modelAddress	= CoreTables::TABLE_MODEL_ADDRESS;

                        $query->onCondition( "$modelAddress.parentType=:ptype AND $modelAddress.type=:type", [ ':ptype' => $this->mParentType, ':type' => $type ] );
                    });
    }

    /**
     * @return Address - associated with parent having type set to mailing
     */
    public function getBranchAddress() {

        return $this->hasMany( Address::className(), [ 'id' => 'modelId' ] )
                    ->viaTable( CoreTables::TABLE_MODEL_ADDRESS, [ 'parentId' => 'id' ], function( $query, $type = Address::TYPE_BRANCH ) {

                        $modelAddress	= CoreTables::TABLE_MODEL_ADDRESS;

                        $query->onCondition( "$modelAddress.parentType=:ptype AND $modelAddress.type=:type", [ ':ptype' => $this->mParentType, ':type' => $type ] );
                    });
    }
}
