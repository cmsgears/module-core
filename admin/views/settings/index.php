<?php
$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Settings | ' . $coreProperties->getSiteTitle();

$settings		= Yii::$app->sidebar->getConfig();

$total	= count( $settings );
$first	= ceil( $total / 2 );
$last	= $total - $first;
$count	= 0;

$left	= '';
$right	= '';
?>

<?php
	foreach ( $settings as $setting ) {

		$settingTitle	= str_replace( "-", " ", $setting );

		ob_start();
?>
	<div class="box box-collapsible box-settings">
		<div class="box-header">
			<div class="box-header-title">
				<?= ucwords( $settingTitle ) ?>
			</div>
			<span class="box-btn-collapse cmti cmti-chevron-down"></span>
			<span id="settings-<?= $setting ?>" content="settings-<?= $setting ?>-content" cmt-app="site" cmt-controller="settings" cmt-action="getContent" action="settings/index?type=<?= $setting ?>">
				<span class="cmt-click collapse-trigger"></span>
			</span>
		</div>
		<div id="settings-<?= $setting ?>-content" class="box-content-wrap clearfix">
			<div class="box-content"></div>
		</div>
	</div>

<?php
		if( $count < $first ) {

			$left .= ob_get_contents();
		}
		else {

			$right .= ob_get_contents();
		}

		ob_get_clean();

		$count++;
	}
?>

<div class="row wrap-settings">
	<div class="col col12x6"><?= $left ?></div>
	<div class="col col12x6"><?= $right ?></div>
</div>

<!-- Templates -->
<?php include_once( dirname( __FILE__ ) . "/templates/update.php" ); ?>
