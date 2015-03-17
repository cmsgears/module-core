<?php
namespace cmsgears\core\common\models\entities;

class UserAddress extends CmgEntity {

	const TYPE_NATIVE 	= 0; 
	const TYPE_MAILING 	= 1;
	const TYPE_BILLING 	= 2;

	// Instance methods --------------------------------------------------

	public function getUser() {

		return $this->hasOne( User::className(), [ 'id' => 'userId' ] );
	}

	public function getAddress() {

		return $this->hasOne( Address::className(), [ 'id' => 'addressId' ] );
	}

	// Static methods --------------------------------------------------

	public static function tableName() {

		return CoreTables::TABLE_USER_ADDRESS;
	}

	// Address

	public static function findAllByUser( $user ) {

		return UserAddress::find()->where( [ 'userId' => $user->getId() ] )->all();
	}

	public static function findNativeByUser( $user ) {

		$userAddress 	= UserAddress::find()->where( [ 'userId' => $user->getId(), 'type' => self::TYPE_NATIVE ] )->one();
		$address 		= null;

		if( isset( $userAddress ) ) {

			$address	= $userAddress->address;
		}

		return $address;
	}

	public static function findMailingByUser( $user ) {

		$userAddress 	= UserAddress::find()->where( [ 'userId' => $user->getId(), 'type' => self::TYPE_MAILING ] )->one();
		$address 		= null;

		if( isset( $userAddress ) ) {

			$address	= $userAddress->address;
		}

		return $address;
	}

	public static function findBillingByUser( $user ) {

		$userAddress 	= UserAddress::find()->where( [ 'userId' => $user->getId(), 'type' => self::TYPE_BILLING ] )->one();
		$address 		= null;

		if( isset( $userAddress ) ) {

			$address	= $userAddress->address;
		}

		return $address;
	}
}

?>