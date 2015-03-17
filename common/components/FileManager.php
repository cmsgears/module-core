<?php
namespace cmsgears\core\common\components;

use \Yii;
use yii\base\Component;

// CMG Imports
use cmsgears\core\common\models\entities\CmgFile;
use cmsgears\core\common\utilities\ImageResize;

/**
 * The file manager accepts single file at a time uploaded by either xhr or file data using ajax post.
 */
class FileManager extends Component {

	public $allowedExtensions 	= [ 'png', 'jpg', 'jpeg', 'gif', 'zip' , 'pdf' ];
	public $generateName		= true;
	public $prettyNames			= true;
	public $maxSize				= 5; // In MB
	public $generateImageThumb	= true;
	public $thumbWidth			= 120;
	public $thumbHeight			= 120;
	public $uploadDirectory		= null;
	public $uploadUrl			= null;

	// File Uploads -----------------------------------------------------------------

	public function handleFileUpload( $selector ) {
		
		return $this->processFileUpload( $selector );
	}

	private function processFileUpload( $selector ) {

		// Get the filename submitted by user
		$filename = ( isset( $_SERVER['HTTP_X_FILENAME'] ) ? $_SERVER['HTTP_X_FILENAME'] : false );

		// Modern Style using Xhr -----
		if( $filename ) {

			$extension 	= pathinfo( $filename, PATHINFO_EXTENSION );

			if( in_array( strtolower( $extension ), $this->allowedExtensions ) ) {

				return $this->saveTempFile( file_get_contents( 'php://input' ), $selector, $filename, $extension );
			}
			else {

				echo "Error: extension not allowed.";
			}
		}
		// Legacy System using file data----
		else {

			if( isset( $_FILES['file'] ) ) {

			    if( $_FILES['file']['error'] > 0 ) {

			        echo 'Error: ' . $_FILES['file']['error'];
			    } 
			    else {

			        $filename = $_FILES['file']['name'];
					
					if( $filename ) {

						$extension 	= pathinfo( $filename, PATHINFO_EXTENSION );
			
						if( in_array( strtolower($extension), $this->allowedExtensions ) ) {
							
							return $this->saveTempFile( file_get_contents( $_FILES['file']['tmp_name'] ), $selector, $filename, $extension );
						}
						else {

							echo "Error: extension not allowed.";
						}
					}
			    }
			}
		}

		return false;
	}

	private function saveTempFile( $file_contents, $selector, $filename, $extension ) {
		
		// Check allowed file size
		$sizeInMb = number_format( $file_contents / 1048576, 2 );
		
		if( $sizeInMb > $this->maxSize ) {
			
			echo "Error: Max size reached.";
			
			return false;			
		}

		// Create Directory if not exist
		$tempUrl		 = "temp/$selector/";
		$uploadDirectory = $this->uploadDirectory . $tempUrl;

		if( !file_exists( $uploadDirectory ) ) {

			mkdir( $uploadDirectory , 0777, true );
		}

		// Generate File Name
		$name		= $filename;

		if( $this->generateName ) {

			$name	= md5( date( 'Y-m-d H:i:s:u' ) );
		}
		else if( $this->prettyNames ) {

			$name	= str_replace( " ", "-", $filename );
		}

		$filename	= $name . "." . $extension;

		// Save File
		if( file_put_contents( $uploadDirectory . $filename, $file_contents ) ) {

			$result	= array();

			$result['name'] 		= $name;
			$result['extension'] 	= $extension;
			$result['tempUrl'] 		= $this->uploadUrl . $tempUrl . $filename;

			return $result;
		}
		else {

			echo "Error: File save failed or file with same name alrady exist.";	
		}

		return false;
	}

	// File Processing ------------------------------------------------------------------

	public function processFile( $date, $user, $file ) {

		$dateDir	= date( 'Y-m-d' );
		$fileName	= $file->name;
		$fileExt	= $file->extension;
		$fileDir	= $file->directory;

		$uploadUrl	= $this->uploadUrl;

		$sourceFile		= $fileDir . '/' . $fileName . '.' . $fileExt;
		$fileDir		= $dateDir . '/' . $fileDir . '/';
		$fileUrl		= $fileDir . $fileName . '.' . $fileExt;

		$this->saveFile( $sourceFile, $fileDir, $fileUrl );

		// Save Image File
		$file->setDirectory( $fileDir );
		$file->setCreatedOn( $date );
		$file->setAuthorId( $user->id );
		$file->setType( CmgFile::TYPE_PUBLIC );
		$file->setUrl( $fileUrl );
	}

	public function saveFile( $sourceFile, $targetDir, $filePath ) {

		$sourceFile	= $this->uploadDirectory . "temp/" . $sourceFile;
		$targetDir	= $this->uploadDirectory . $targetDir;
		$filePath	= $this->uploadDirectory . $filePath;

		// move file from temp to final destination
		if( !file_exists( $targetDir ) ) {

			mkdir( $targetDir , 0777, true );
		}

		rename( $sourceFile, $filePath );
	}	

	// Image Processing -----------------------------------------------------------------
	
	public function processImage( $date, $user, $file, $width = null, $height = null ) {

		$dateDir	= date( 'Y-m-d' );
		$imageName	= $file->name;
		$imageExt	= $file->extension;
		$imageDir	= $file->directory;

		$uploadUrl	= $this->uploadUrl;

		$sourceFile		= $imageDir . '/' . $imageName . '.' . $imageExt;
		$targetDir		= $dateDir . '/' . $imageDir . '/';
		$imageUrl		= $targetDir . $imageName . '.' . $imageExt;
		$imageThumbUrl	= $targetDir . $imageName . '-thumb.' . $imageExt;

		$this->saveImageAndThumb( $sourceFile, $targetDir, $imageUrl, $imageThumbUrl, $width, $height );

		// Save Image File
		$file->createdOn	= $date;
		$file->authorId		= $user->id;
		$file->type			= CmgFile::TYPE_PUBLIC;
		$file->url			= $imageUrl;

		if( $this->generateImageThumb ) {

			$file->thumb	= $imageThumbUrl;
		}
	}

	public function saveImageAndThumb( $sourceFile, $targetDir, $filePath, $thumbPath, $width = null, $height = null ) {

		$sourceFile	= $this->uploadDirectory . "temp/" . $sourceFile;
		$targetDir	= $this->uploadDirectory . $targetDir;
		$filePath	= $this->uploadDirectory . $filePath;
		$thumbPath	= $this->uploadDirectory . $thumbPath;

		// move file from temp to final destination
		if( !file_exists( $targetDir ) ) {

			mkdir( $targetDir , 0777, true );
		}

		rename( $sourceFile, $filePath );

		// Resize Image
		if( isset($width) && isset($height) && intval($width) > 0 && intval($height) > 0 ) {

			$resizeObj = new ImageResize( $filePath );
			$resizeObj -> resizeImage( $width, $height, 'exact' );
			$resizeObj -> saveImage( $filePath, 100 );
		}

		// Save Thumb
		if( $this->generateImageThumb && isset( $thumbPath ) ) {

			$resizeObj = new ImageResize( $filePath );
			$resizeObj -> resizeImage( $this->thumbWidth, $this->thumbHeight, 'crop' );
			$resizeObj -> saveImage( $thumbPath, 100 );
		}
	}

	/*
	public function save_qr_code( $url ) {

		$date				= date('Y-m-d');
		$design_directory 	= $this->uploadDirectory . "temp/" . $date . "/";

		if( !file_exists( $design_directory ) ) {

			mkdir( $design_directory , 0777, true );
		}

		$name		= md5( date('Y-m-d H:i:s:u') );
		$filename	= $name . ".png";
		$filepath 	= $design_directory . $name . ".png";
		
		QRcode::png( $url,  $filepath, "L", 8, 4);

		return EmblmCommerce::$temp_images_dir . $date . "/" . $filename;;
	}
	*/

	private function convertTo300DPI( $jpgPath ) {

       	$filename 	= $jpgPath;
        $input 		= imagecreatefromjpeg( $filename );

        $width		= imagesx( $input );
        $height		= imagesy( $input );

        $output 	= imagecreatetruecolor( $width, $height );
        $white 		= imagecolorallocate( $output,  255, 255, 255 );

        imagefilledrectangle( $output, 0, 0, $width, $height, $white );
        imagecopy( $output, $input, 0, 0, 0, 0, $width, $height );

        ob_start();

        imagejpeg( $output );

        $contents =  ob_get_contents();
		
        //Converting Image DPI to 300DPI                
        $contents = substr_replace( $contents, pack( "cnn", 1, 300, 300 ), 13, 5 );
        ob_end_clean();

		file_put_contents( $filename, $contents );
	}
}

?>