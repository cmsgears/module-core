<?php
// Yii Import
use \Yii;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

// CMG Import
use cmsgears\files\widgets\AvatarUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Profile";

$this->params['sidebar-parent'] = 'sidebar-profile';
$this->params['sidebar-child'] 	= 'profile';
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<div class="clear"></div>
    	<h4>My Profile</h4>

		<div>
			<?=AvatarUploader::widget([
				'options' => [ 'id' => 'avatar-user', 'class' => 'file-uploader' ], 
				'model' => $model->avatar, 
				'postaction' => true, 'cmtcontroller' => 'default',
				'postactionurl' => Url::toRoute( [ 'apix/user/avatar'], true ),
				'btnChooserIcon' => 'icon-action icon-action-edit',
				'postviewIcon' => 'icon-sidebar icon-user'
			]);?> 
		</div>

    	<div class="clear row right">
    		<a class="right icon-action icon-action-edit btn-edit btn-edit-profile"></a>
    	</div>

    	<div class="frm-split frm-view-profile">
		 	<div>
	    		<label> Email </label>
	    		<label><?= $model->email ?></label>
			</div>
		 	<div>
	    		<label> Username </label>
	    		<label> <?= $model->username ?></label>
			</div>
			<div>
	    		<label> Firstname </label>
	    		<label> <?= $model->firstName ?></label>
			</div>
			<div>
	    		<label> Lastname </label>
	    		<label> <?= $model->lastName ?></label>
			</div>
			<div>
	    		<label> Gender </label>
	    		<label> <?= $model->getGenderStr() ?></label>
			</div>
			<div>
	    		<label> Phone </label>
	    		<label> <?= $model->phone ?></label>
		 	</div>
		 	<div>
	    		<label> Newsletter </label>
	    		<label> <?= $model->getNewsletterStr() ?> </label>
		 	</div>
    	</div>

    	<div class="frm-edit hidden">
			<?php $form = ActiveForm::begin( ['id' => 'frm-user-profile', 'options' => ['class' => 'frm-split' ] ] );?>

			<?php if( !Yii::$app->cmgCore->isEmailChangeAllowed() ) { ?>

				<?= $form->field( $model, 'email' )->textInput( [ 'readonly' => true ] ) ?>

			<?php } else { ?>

				<?= $form->field( $model, 'email' ) ?>

			<?php } ?>

			<?php if( !Yii::$app->cmgCore->isUsernameChangeAllowed() ) { ?>

				<?= $form->field( $model, 'username' )->textInput( [ 'readonly' => true ] ) ?>

			<?php } else { ?>

				<?= $form->field( $model, 'username' ) ?>

			<?php } ?>

			<?= $form->field( $model, 'firstName' ) ?>
			<?= $form->field( $model, 'lastName' ) ?>
			<?= $form->field( $model, 'genderId' )->dropDownList( $genders )  ?>
			<?= $form->field( $model, 'phone' ) ?>
			<?= $form->field( $model, 'newsletter' )->checkbox() ?>
	
			<div class="clear"></div>
			<div class="align-middle"><input type="submit" class="btn-fancy medium" value="Update" /></div>
	
			<?php ActiveForm::end(); ?>
		</div>
	</div>	
</section>