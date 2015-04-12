<?php
namespace cmsgears\core\common\models\entities;

/**
 * UserAddress Entity - It can be used in several scenarios where we need different type of addresss associated with the User.
 *
 * @property integer $userId
 * @property integer $addressId
 * @property integer $type
 */
class UserAddress extends CmgEntity {

	/**
	 * Standard Address types
	 */
	const TYPE_NATIVE 	=  0;
	const TYPE_MAILING 	= 10;
	const TYPE_BILLING 	= 20;

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

		return self::find()->where( 'userId=:id', [ ':id' => $user->id ] )->all();
	}

	public static function findNativeByUser( $user ) {

		$userAddress	= self::find()->where( [ 'userId=:id', 'type=:type' ] )
							->addParams( [ ':id' => $user->id, ':type' => self::TYPE_NATIVE ] )
							->one();

		$address 		= null;

		if( isset( $userAddress ) ) {

			$address	= $userAddress->address;
		}

		return $address;
	}

	public static function findMailingByUser( $user ) {

		$userAddress	= self::find()->where( [ 'userId=:id', 'type=:type' ] )
							->addParams( [ ':id' => $user->id, ':type' => self::TYPE_MAILING ] )
							->one();

		$address 		= null;

		if( isset( $userAddress ) ) {

			$address	= $userAddress->address;
		}

		return $address;
	}

	public static function findBillingByUser( $user ) {

		$userAddress	= self::find()->where( [ 'userId=:id', 'type=:type' ] )
							->addParams( [ ':id' => $user->id, ':type' => self::TYPE_BILLING ] )
							->one();

		$address 		= null;

		if( isset( $userAddress ) ) {

			$address	= $userAddress->address;
		}

		return $address;
	}
}

?>