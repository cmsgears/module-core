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

    	<?= $form->field( $model, 'email' ) ?>
    	<?= $form->field( $model, 'username' ) ?>
    	<h4>User Avatar</h4>
		<div id="file-avatar" class="file-container" legend="User Avatar" selector="avatar" utype="image" btn-class="btn file-input-wrap" btn-text="Choose Avatar">
			<div class="file-fields">
				<input type="hidden" name="File[id]" value="<?php if( isset( $avatar ) ) echo $avatar->id; ?>" />
				<input type="hidden" name="File[name]" class="file-name" value="<?php if( isset( $avatar ) ) echo $avatar->name; ?>" />
				<input type="hidden" name="File[extension]" class="file-extension" value="<?php if( isset( $avatar ) ) echo $avatar->extension; ?>" />
				<input type="hidden" name="File[directory]" value="avatar" value="<?php if( isset( $avatar ) ) echo $avatar->directory; ?>" />
				<input type="hidden" name="File[changed]" class="file-change" value="<?php if( isset( $avatar ) ) echo $avatar->changed; ?>" />
			</div>
		</div>
		<?= $form->field( $model, 'firstName' ) ?>
		<?= $form->field( $model, 'lastName' ) ?>
		<?= $form->field( $model, 'gender' )->dropDownList( $genders )  ?>
		<?= $form->field( $model, 'phone' ) ?>
		<?= $form->field( $model, 'roleId' )->dropDownList( $roles )  ?>
		<?= $form->field( $model, 'status' )->dropDownList( $status ) ?>
		<?= $form->field( $model, 'newsletter' )->checkbox() ?>

		<?=Html::a( "Back", [ '/cmgcore/user/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Update" />

		<?php ActiveForm::end(); ?>
	</div>	
</section>
<script type="text/javascript">
	initSidebar( "sidebar-identity", 3 );
	initFileUploader();

	<?php if( isset( $avatar ) ) { ?>
		jQuery("#file-avatar .file-image").html( "<img src='<?php echo Yii::$app->fileManager->uploadUrl . $avatar->getDisplayUrl(); ?>' />'" );
	<?php } ?>
</script>