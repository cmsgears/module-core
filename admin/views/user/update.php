<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . " | Update User";
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Update User</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-user-update', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'user_email' ) ?>
    	<?= $form->field( $model, 'user_username' ) ?>
    	<h4>User Avatar</h4>
		<div id="file-avatar" class="file-container" legend="User Avatar" selector="avatar" utype="image" btn-class="btn file-input-wrap" btn-text="Choose Avatar">
			<div class="file-fields">
				<input type="hidden" name="File[file_id]" value="<?php if( isset( $avatar ) ) echo $avatar->getId(); ?>" />
				<input type="hidden" name="File[file_name]" class="file-name" value="<?php if( isset( $avatar ) ) echo $avatar->getName(); ?>" />
				<input type="hidden" name="File[file_extension]" class="file-extension" value="<?php if( isset( $avatar ) ) echo $avatar->getExtension(); ?>" />
				<input type="hidden" name="File[file_directory]" value="avatar" value="<?php if( isset( $avatar ) ) echo $avatar->getDirectory(); ?>" />
				<input type="hidden" name="File[changed]" class="file-change" value="<?php if( isset( $avatar ) ) echo $avatar->changed; ?>" />
			</div>
		</div>
		<?= $form->field( $model, 'user_firstname' ) ?>
		<?= $form->field( $model, 'user_lastname' ) ?>
		<?= $form->field( $model, 'user_gender' )->dropDownList( $genders )  ?>
		<?= $form->field( $model, 'user_mobile' ) ?>
		<?= $form->field( $model, 'user_role' )->dropDownList( $roles )  ?>
		<?= $form->field( $model, 'user_status' )->dropDownList( $status ) ?>
		<?= $form->field( $model, 'user_newsletter' )->checkbox() ?>

		<?=Html::a( "Back", [ '/cmgcore/user/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Update" />

		<?php ActiveForm::end(); ?>
	</div>	
</section>
<script type="text/javascript">
	initSidebar( "sidebar-identity", -1 );
	initFileUploader();

	<?php if( isset( $avatar ) ) { ?>
		jQuery("#file-avatar .file-image").html( "<img src='<?php echo Yii::$app->fileManager->uploadUrl . $avatar->getDisplayUrl(); ?>' />'" );
	<?php } ?>
</script>