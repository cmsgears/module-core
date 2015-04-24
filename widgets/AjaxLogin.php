<?php
namespace cmsgears\core\widgets;

// Yii Imports
use \Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\web\View;
use yii\helpers\Html;

class AjaxLogin extends Widget {

	// Variables ---------------------------------------------------

	// Public Variables --------------------

	public $options;

	// Constructor and Initialisation ------------------------------

	// yii\base\Object

    public function init() {

        parent::init();
    }

	// Instance Methods --------------------------------------------

	// yii\base\Widget

    public function run() {

		$this->renderHtml();

		$this->registerJs();
    }
	
	private function renderHtml() {

		$attribs = [];
		
		if( isset( $this->options ) ) {
			
			foreach ( $this->options as $key => $value ) {
				
				$attribs[]	= $key . "='" . $value . "' ";
			}
		}
		
		$attribs	= implode( " ", $attribs );
?>
		<div <?=$attribs?>>
			<div id="box-login" class='popout-header'>
				<form class="frm-ajax" id="frm-login" group="0" key="5" action="<?php echo Yii::$app->urlManager->createAbsoluteUrl("apix/login"); ?>" method="post">
					<div class="max-area-cover frm-spinner"><div class="valign-center fa fa-3x fa-spinner fa-spin"></div></div>
		
					<div class="frm-icon-field">
						<span class="wrap-icon fa fa-at"></span><input  type="text" name="Login[email]" placeholder="Email *">
					</div>
					<span class="error" formError="email"></span>
		
					<div class="frm-icon-field">
						<span class="wrap-icon fa fa-lock"></span><input  type="password" name="Login[password]" placeholder="Password *">
					</div>
					<span class="error" formError="password"></span>
					
					<div class="row clearfix">
						<?= Html::a( "Forgot your Password ?", [ '/forgot-password' ] ) ?>
					</div>
		
					<div class="row clearfix">
						<input type="submit" name="submit" value="Login">
					</div>
		
					<div class="row clearfix">
						<div class="frm-message warning"></div>
					</div>
		
					<div class="row clearfix">
						<?= Html::a( "Sign Up", [ '#' ], [ 'id' => 'box-signup-show'] ) ?>
					</div>
				</form>
			</div>
			<div id="box-signup" class='popout-header'>
				<form class="frm-ajax" id="frm-register" action="<?php echo Yii::$app->urlManager->createAbsoluteUrl("apix/register"); ?>" method="post">
					<div class="max-area-cover frm-spinner"><div class="valign-center fa fa-3x fa-spinner fa-spin"></div></div>
		
					<div class="frm-icon-field">
						<span class="wrap-icon fa fa-at"></span><input type="text" name="Register[email]" placeholder="Email *">
					</div>
					<span class="error" formError="email"></span>
		
					<div class="frm-icon-field">
						<span class="wrap-icon fa fa-lock"></span><input type="password" name="Register[password]" placeholder="Password *">
					</div>
					<span class="error" formError="password"></span>
		
					<div class="frm-icon-field">
						<span class="wrap-icon fa fa-lock"></span><input type="password" name="Register[password_repeat]" placeholder="Repeat Password *">
					</div>
					<span class="error" formError="password_repeat"></span>
					
					<div class="frm-icon-field">
						<span class="wrap-icon fa fa-user"></span><input type="text" name="Register[username]" placeholder="Username *">
					</div>
					<span class="error" formError="username"></span>
		
					<div class="frm-icon-field">
						<span class="wrap-icon fa fa-user"></span><input type="text" name="Register[firstName]" placeholder="First Name">
					</div>
					<span class="error" formError="firstName"></span>
		
					<div class="frm-icon-field">
						<span class="wrap-icon fa fa-user"></span><input type="text" name="Register[lastName]" placeholder="Last Name">
					</div>
					<span class="error" formError="lastName"></span>
		
					<div class="row clearfix">
						<input type="checkbox" name="Register[newsletter]"> Sign Up for our newsletter.
					</div>
		
					<div class="row clearfix">
						<input type="submit" name="submit" value="Sign Up">
					</div>
		
					<div class="row clearfix">
						<div class="frm-message warning"></div>
					</div>
		
					<div class="row clearfix">
						<?= Html::a( "Login", [ '#' ], [ 'id' => 'box-login-show'] ) ?>
					</div>
				</form>
			</div>
		</div>
<?php
	}

	private function registerJs() {

		$js	= "jQuery( '#box-signup-show' ).click( function( e ) {

					e.preventDefault();
			
					jQuery( '#box-login' ).hide();

					jQuery( '#box-signup' ).show( 'fast' );
				});
	
				jQuery( '#box-login-show' ).click( function( e ) {
					
					e.preventDefault();
			
					jQuery( '#box-login' ).show( 'fast' );
			
					jQuery( '#box-signup' ).hide();
				});";

		$this->getView()->registerJs( $js, View::POS_READY );
	}
}

?>