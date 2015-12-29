<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\ObjectData;

/**
 * The class ObjectDataService is base class to perform database activities for ObjectData Entity.
 */
class ObjectDataService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	/**
	 * @param integer $id
	 * @return ObjectData
	 */
	public static function findById( $id ) {

		return ObjectData::findById( $id );
	}

	/**
	 * @param string $name
	 * @param string $type
	 * @return ObjectData
	 */
	public static function findByNameType( $name, $type ) {

		return ObjectData::findByNameType( $name, $type );
	}

	/**
	 * @param string $slug
	 * @param string $type
	 * @return ObjectData
	 */
	public static function findBySlugType( $slug, $type ) {

		return ObjectData::findBySlugType( $slug, $type );
	}

	/**
	 * @param array $config
	 * @return array - an array having id as key and name as value.
	 */
	public static function getIdNameMap( $config = [] ) {

		return self::findMap( 'id', 'name', CoreTables::TABLE_OBJECT_DATA, $config );
	}

	/**
	 * @param string $type
	 * @return array - an array having id as key and name as value.
	 */
	public static function getIdNameMapByType( $type ) {

		return self::getIdNameMap( [ 'conditions' => [ 'type' => $type ] ] );
	}

	/**
	 * @return array - of all object data ids
	 */
	public static function getIdList( $config = [] ) {

		return self::findList( 'id', CoreTables::TABLE_OBJECT_DATA, $config );
	}

	/**
	 * @return array - of all object data ids
	 */
	public static function getIdListByType( $type ) {

		return self::getIdList( [ 'conditions' => [ 'type' => $type ] ] );
	}

	/**
	 * @param array $config
	 * @return array - An array of associative array of object data id and name.
	 */
	public static function getIdNameList( $config = [] ) {

		return self::findIdNameList( 'id', 'name', CoreTables::TABLE_OBJECT_DATA, $config );
	}

	/**
	 * @param string $id
	 * @return array - An array of associative array of object data id and name.
	 */
	public static function getIdNameListByType( $type ) {

		return self::getIdNameList( [ 'conditions' => [ 'type' => $type ] ] );
	}

	// Data Provider ----

	/**
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

		return self::getDataProvider( new ObjectData(), $config );
	}

	// Create -----------

	public static function create( $object, $data = null ) {

		if( isset( $object->templateId ) && $object->templateId <= 0 ) {

			unset( $object->templateId );
		}

		if( isset( $data ) ) {

			$object->generateJsonFromObject( $data );
		}

		$object->save();

		return $object;
	}

	// Update -----------

	public static function update( $object, $data = null ) {

		if( isset( $object->templateId ) && $object->templateId <= 0 ) {

			unset( $object->templateId );
		}

		$objectToUpdate	= self::findById( $object->id );

		$objectToUpdate->copyForUpdateFrom( $object, [ 'templateId', 'name', 'description', 'type', 'active', 'htmlOptions', 'data' ] );

		if( isset( $data ) ) {

			$objectToUpdate->generateJsonFromObject( $data );
		}

		$objectToUpdate->update();

		return $objectToUpdate;
	}

	// Delete -----------

	public static function delete( $object ) {

		$existingObject	= self::findById( $object->id );

		// Delete Object
		$existingObject->delete();

		return true;
	}
}

?>