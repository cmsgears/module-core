<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Import
use cmsgears\files\widgets\AvatarUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Profile";
$user			= Yii::$app->user->getIdentity();
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">My Profile</div>
	</div>
	<div class="box-wrap-content">

		<div class="box-content clearfix">
			<div class="header">User Avatar</div>
			<?= AvatarUploader::widget([
				'options' => [ 'id' => 'avatar-user', 'class' => 'file-uploader' ], 
				'model' => $user->avatar, 'cmtController' => 'user',
				'postAction' => true, 'postViewIcon' => 'cmti cmti-2x cmti-user'
			]); ?>
		</div>

		<div class="box-content clearfix">
			<div class="header">User Profile</div>
			<div class="box-form box-form-regular">
				<span class="cmti cmti-edit btn-edit text text-black"></span>
				<div class="wrap-info info">
					<table>
						<tr><td>Email</td><td><?= $model->email ?></td></tr>
						<tr><td>Username</td><td><?= $model->username ?></td></tr>
						<tr><td>Firstname</td><td><?= $model->firstName ?></td></tr>
						<tr><td>Lastname</td><td><?= $model->lastName ?></td></tr>
						<tr><td>Gender</td><td><?= $model->getGenderStr() ?></td></tr>
						<tr><td>Phone</td><td><?= $model->phone ?></td></tr>
						<tr><td>Newsletter</td><td><?= $model->getNewsletterStr() ?></td></tr>
					</table>
				</div>
				<div class="wrap-form">
					<?php $form = ActiveForm::begin( [ 'id' => 'frm-user-profile', 'options' => [ 'class' => 'frm-split-40-60' ] ] );?>
		
					<?php 
						if( !$coreProperties->isChangeEmail() ) {
		
							echo $form->field( $model, 'email' )->textInput( [ 'readonly' => true ] );
						}
						else {
		
							echo $form->field( $model, 'email' );
						}
					?>
		
					<?php 
						if( !$coreProperties->isChangeUsername() ) {
		
							echo $form->field( $model, 'username' )->textInput( [ 'readonly' => true ] );
						}
						else {
		
							echo $form->field( $model, 'username' );
						}
					?>
		
					<?= $form->field( $model, 'firstName' ) ?>
					<?= $form->field( $model, 'lastName' ) ?>
					<?= $form->field( $model, 'genderId' )->dropDownList( $genders )  ?>
					<?= $form->field( $model, 'phone' ) ?>
					<?= $form->field( $model, 'newsletter' )->checkbox() ?>
			
					<div class="clear"></div>

					<div class="align align-center">
						<input class="element-medium" type="submit" value="Update" />
					</div>

					<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div> 
	</div>
</div>