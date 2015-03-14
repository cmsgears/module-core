<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use yii\db\ActiveRecord;

class UserAddress extends ActiveRecord {

	const TYPE_NATIVE 	= 0; 
	const TYPE_MAILING 	= 1;
	const TYPE_BILLING 	= 2;

	// Instance methods --------------------------------------------------

	// db columns

	public function getUserId() {

		return $this->user_id;
	}

	public function getUser() {

		return $this->hasOne( User::className(), [ 'user_id' => 'user_id' ] );
	}

	public function setUserId( $id ) {

		$this->user_id = $id;
	}

	public function getAddressId() {

		return $this->address_id;
	}

	public function getAddress() {

		return $this->hasOne( Address::className(), [ 'address_id' => 'address_id' ] );
	}

	public function setAddressId( $id ) {

		$this->address_id = $id;
	}

	public function getAddressType() {

		return $this->address_type;
	}

	public function setAddressType( $type ) {

		$this->address_type = $type;
	}

	// Static methods --------------------------------------------------

	public static function tableName() {

		return CoreTables::TABLE_USER_ADDRESS;
	}

	// Address

	public static function findAllByUser( $user ) {

		return UserAddress::find()->where( [ 'user_id' => $user->getId() ] )->all();
	}

	public static function findNativeByUser( $user ) {

		$userAddress 	= UserAddress::find()->where( [ 'user_id' => $user->getId(), 'user_address_type' => self::TYPE_NATIVE ] )->one();
		$address 		= null;

		if( isset( $userAddress ) ) {

			$address	= $userAddress->address;
		}

		return $address;
	}

	public static function findMailingByUser( $user ) {

		$userAddress 	= UserAddress::find()->where( [ 'user_id' => $user->getId(), 'user_address_type' => self::TYPE_MAILING ] )->one();
		$address 		= null;

		if( isset( $userAddress ) ) {

			$address	= $userAddress->address;
		}

		return $address;
	}

	public static function findBillingByUser( $user ) {

		$userAddress 	= UserAddress::find()->where( [ 'user_id' => $user->getId(), 'user_address_type' => self::TYPE_BILLING ] )->one();
		$address 		= null;

		if( isset( $userAddress ) ) {

			$address	= $userAddress->address;
		}

		return $address;
	}
}

?>