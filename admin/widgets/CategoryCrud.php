<?php
namespace cmsgears\core\admin\widgets;

// Yii Imports
use \Yii;
use yii\web\View;
use yii\base\Widget;
use yii\base\InvalidConfigException;

class CategoryCrud extends Widget {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

    public $type;

	// Constructor and Initialisation ------------------------------

	// yii\base\Object

    public function init() {

        parent::init();
    }

	// Instance Methods --------------------------------------------

	// yii\base\Widget

    public function run() {

		$this->registerScripts();

		return $this->renderCrud();
    }

    public function renderCrud() {
		
		$createUrl	= Yii::$app->urlManager->createAbsoluteUrl( '/apix/cmgcore/category/create' );
		$csrfToken	= Yii::$app->request->csrfToken;
		$type		= $this->type;

		$html	=  "<!--Create Category -->
					<div class='category-popup-wrapper add-cat-popup'>
						<div class='popup-main'>
							<span class='close-icon'> </span>
							<h1>Add Category</h1>
							<form class='frm-ajax' id='frm-apix-cat-create' action='$createUrl' method='post'>
								<ul>
									<li class='clearfix'>
										<label>Category Name</label>
										<input type='text' name='Category[category_name]'>
										<span class='form-error' formError='category_name'></span>
									</li>
									<li class='clearfix'>
										<label>Description</label>
										<textarea name='Category[category_desc]'> </textarea>
										<span class='form-error' formError='category_desc'></span>
									</li>
									<li>
										<input type='hidden' name='_csrf' value='$csrfToken' />
										<input type='hidden' name='Category[category_type]' value='$type' />
										<input type='submit' class='btn' value='Add Category'>
									</li>
								</ul>
								<div class='spinner'></div>
								<div class='frm-message'></div>
							</form>
						</div>
					</div>
					
					<!--- Update Category -->
					<div class='category-popup-wrapper update-cat-popup'>
						<div class='popup-main'>
							<span class='close-icon'> </span>
							<h1>Update Category</h1>
							<form class='frm-ajax' id='frm-apix-cat-update' group='0' key='25' action='#' method='post'>
								<ul>
									<li class='clearfix'>
										<label>Category Name</label>
										<input class='cat-name' type='text' name='Category[category_name]'>
										<span class='form-error' formError='category_name'></span>
									</li>
									<li class='clearfix'>
										<label>Description</label>
										<textarea class='cat-desc' name='Category[category_desc]'> </textarea>
										<span class='form-error' formError='category_desc'></span>
									</li>
									<li>
										<input type='hidden' name='_csrf' value='$csrfToken' />
										<input type='hidden' name='Category[category_type]' value='$type' />
										<input type='submit' class='btn' value='Save'>
									</li>
								</ul>
								<div class='spinner'></div>
								<div class='frm-message'></div>
							</form>
						</div>
					</div>
					
					<!-- Delete Category -->
					<div class='category-popup-wrapper delete-cat-popup'>
						<div class='popup-main'>
							<span class='close-icon'> </span>
							<h1>Delete Category</h1>
							<form class='frm-ajax' id='frm-apix-cat-delete' group='0' key='30' action='#' method='post'>
								<ul>
									<li class='clearfix'>
										<label>Category Name</label>
										<input class='cat-name' disabled='true' type='text' name='Category[category_name]'>
										<span class='form-error' formError='category_name'></span>
									</li>
									<li class='clearfix'>
										<label>Description</label>
										<textarea class='cat-desc'  disabled='true' name='Category[category_desc]'> </textarea>
										<span class='form-error' formError='category_desc'></span>
									</li>
									<li>
										<input type='hidden' name='_csrf' value='$csrfToken' />
										<input type='hidden' name='Category[category_type]' value='$type' />
										<input type='submit' class='btn' value='Delete Category'>
									</li>
								</ul>
								<div class='spinner'></div>
								<div class='frm-message'></div>
							</form>
						</div>
					</div>";
		
		return $html;
    }
    
	public function registerScripts() {
			
		$updateUrl	= Yii::$app->urlManager->createAbsoluteUrl('/cmgcore/apix/category/update?id=');
		$deleteUrl	= Yii::$app->urlManager->createAbsoluteUrl('/cmgcore/apix/category/delete?id=');

		$categoryJs		= "	// Create Category 
							jQuery('.category-add-btn').click( function() {
						
								jQuery( 'body' ).addClass( 'overflow-hidden' );
								jQuery( '.add-cat-popup' ).fadeIn( 'slow' );
							});
						
							jQuery( '.popup-main span.close-icon' ).click( function() {
						
								jQuery( '.add-cat-popup' ).fadeOut( 'slow' );
							});
						
							//Update Category
							jQuery('.category-update-btn').click( function() {
						
								jQuery( 'body' ).addClass( 'overflow-hidden' );
								
								var parentclass 	= jQuery(this).parents().eq(1).attr('class');
								
								var splitCatData 	= parentclass.split('-');
								var id				= splitCatData[1];
								
								var catName 		= jQuery(this).parents().eq(1).children('.cat-name').text();
								var catDesc 		= jQuery(this).parents().eq(1).children('.cat-desc').text();
								
								var formAction		= '$updateUrl' + id;
								
								//Set Category Update form
								jQuery('.update-cat-popup form').attr('action',formAction );
								jQuery('.update-cat-popup form input.cat-name ').val( catName );
								jQuery('.update-cat-popup form textarea.cat-desc ').val( catDesc );
								
								jQuery('.update-cat-popup').fadeIn('slow');
							});
							
							jQuery('.popup-main span.close-icon').click( function() {
								
								jQuery('.update-cat-popup').fadeOut('slow');
							});
							
							//Delete Category 
							jQuery('.category-delete-btn').click( function() {
								
								jQuery('body').addClass('overflow-hidden');
								
								var parentclass 	= jQuery(this).parents().eq(1).attr('class');
								
								var splitCatData 	= parentclass.split('-');
								var id				= splitCatData[1];
								
								var catName 		= jQuery(this).parents().eq(1).children('.cat-name').text();
								var catDesc 		= jQuery(this).parents().eq(1).children('.cat-desc').text();
								
								var formAction		= '$deleteUrl' + id ;

								//Set Category Update form
								jQuery('.delete-cat-popup form').attr('action',formAction );
								jQuery('.delete-cat-popup form input.cat-name ').val( catName );
								jQuery('.delete-cat-popup form textarea.cat-desc ').val( catDesc );
								
								jQuery('.delete-cat-popup').fadeIn('slow');
							});
							
							jQuery('.popup-main span.close-icon').click( function() {
								
								jQuery('.delete-cat-popup').fadeOut('slow');
							});";

		$this->getView()->registerJs( $categoryJs, View::POS_READY );
	}
}

?>