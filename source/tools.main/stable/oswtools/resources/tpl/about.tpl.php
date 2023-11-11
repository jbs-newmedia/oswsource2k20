<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license MIT License
 *
 * @var osWFrame\Tools\Tool $Tool
 * @var osWFrame\Core\Template $this
 */

?>

	<h4 class="mb-3">About</h4>

	<div class="row">
		<div class="col-md-3 col-lg-2"><strong>Tool:</strong></div>
		<div class="col-md-3 col-lg-4"><?php if ($Tool->getStringValue('name') === ''): ?>-<?php else: ?><?php echo $Tool->getStringValue('name') ?><?php endif ?></div>
		<div class="col-md-3 col-lg-2"><strong>Author:</strong></div>
		<div class="col-md-3 col-lg-4"><?php if ($Tool->getStringValue('author') === ''): ?>-<?php else: ?><?php echo $Tool->getStringValue('author') ?><?php endif ?></div>
	</div>

	<div class="row">
		<div class="col-md-3 col-lg-2"><strong>Version:</strong></div>
		<div class="col-md-3 col-lg-4"><?php if ($Tool->getStringValue('version') === ''): ?>-<?php else: ?><?php echo $Tool->getStringValue('version') ?><?php endif ?></div>
		<div class="col-md-3 col-lg-2"><strong>Copyright:</strong></div>
		<div class="col-md-3 col-lg-4"><?php if ($Tool->getStringValue('copyright') === ''): ?>-<?php else: ?><?php echo $Tool->getStringValue('copyright') ?><?php endif ?></div>
	</div>

	<div class="row">
		<div class="col-md-3 col-lg-2"><strong>Release:</strong></div>
		<div class="col-md-3 col-lg-4"><?php if ($Tool->getStringValue('release') === ''): ?>-<?php else: ?><?php echo $Tool->getStringValue('release') ?><?php endif ?></div>
		<div class="col-md-3 col-lg-2"><strong>Link:</strong></div>
		<div class="col-md-3 col-lg-4"><?php if ($Tool->getStringValue('link') === ''): ?>-<?php else: ?>
				<a href="<?php echo $Tool->getStringValue('link') ?>" target="_blank"><?php echo $Tool->getStringValue('link') ?></a><?php endif ?>
		</div>
	</div>

<?php if (\osWFrame\Tools\Helper::checkVersion($Tool->getStringValue('version'), $Tool->getStringValue('version_update'))): ?>
	<div class="row mt-3">
		<div class="col-12">
			Version <?php echo $Tool->getStringValue('version_update') ?> available,
			<a href="<?php echo $this->buildhrefLink('current', 'action=update') ?>"><strong>click here to update</strong></a>
		</div>
	</div>
<?php endif ?>

	<hr/>

	<h4 class="mb-3">License</h4>

	<div class="card card-license">
		<div class="card-body"><?php echo \osWFrame\Tools\Helper::getLicense($Tool->getStringValue('license')) ?></div>
	</div>

	<hr/>

	<h4 class="">Used software</h4>

<?php foreach ($Tool->getUsedSoftware() as $software): ?>
	<div class="row mt-3">
		<div class="col-md-3 col-lg-2">
			<a target="_blank" href="<?php echo $software['url'] ?>"><?php echo $software['name'] ?></a></div>
		<div class="col-md-9 col-lg-10"><?php echo $software['description'] ?></div>
	</div>
<?php endforeach ?>
