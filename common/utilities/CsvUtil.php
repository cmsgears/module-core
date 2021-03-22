<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\utilities;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\config\CoreProperties;

/**
 * The class CsvUtil import CSV data to DB table and export table data to csv.
 */
class CsvUtil {

	public static function importFile( $fileName, $serviceName, $config = [] ) {

		$service	= null;
		$modelClass	= null;

		if( isset( $serviceName ) ) {

			$service	= Yii::$app->factory->get( $serviceName );
			$modelClass	= $service->getModelClass();
		}
		else {

			$modelClass	= isset( $config[ 'modelClass' ] ) ? $config[ 'modelClass' ] : null;
		}

		if( !isset( $modelClass ) ) {

			return false;
		}

		$coreProperties = CoreProperties::getInstance();

		$sourceDir	= isset( $config[ 'sourceDir' ] ) ? $config[ 'sourceDir' ] : $coreProperties->getUploadsDir();
		$attributes	= $config[ 'attributes' ];
		$rowsToSkip	= isset( $config[ 'rowsToSkip' ] ) ? $config[ 'rowsToSkip' ] : 0;
		$rowsLimit	= isset( $config[ 'rowsLimit' ] ) ? $config[ 'rowsLimit' ] : 0;

		$length		= isset( $config[ 'length' ] ) ? $config[ 'length' ] : 0;
		$delimiter	= isset( $config[ 'delimiter' ] ) ? $config[ 'delimiter' ] : ",";
		$enclosure	= isset( $config[ 'enclosure' ] ) ? $config[ 'enclosure' ] : '"';
		$escape		= isset( $config[ 'escape' ] ) ? $config[ 'escape' ] : "\\";

		$file = $sourceDir . $fileName;

		// open the csv file
		$fp = fopen( $file, "r" );

		$counter = 1;

		if( $rowsLimit <= 0 ) {

			while( ( $row = fgetcsv( $fp, $length, $delimiter, $enclosure, $escape ) ) ) {

				if( $counter > $rowsToSkip ) {

					self::insertRow( $service, $modelClass, $attributes, $row );
				}

				$counter++;
			}
		}
		else {

			while( ( $row = fgetcsv( $fp, $length, $delimiter, $enclosure, $escape ) ) ) {

				if( $counter > $rowsToSkip && $counter <= $rowsLimit ) {

					self::insertRow( $service, $modelClass, $attributes, $row );
				}

				$counter++;
			}
		}

		fclose( $fp );
	}

	protected static function insertRow( $service, $modelClass, $attributes, $row ) {

		$model = new $modelClass;

		foreach ( $attributes as $key => $value ) {

			if( !empty( $value ) ) {

				$model->$value = $row[ $key ];
			}
		}

		if( isset( $service ) ) {

			$service->create( $model );
		}
		else {

			$model->save();
		}
	}

}
