<?php

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License 3
 */

?>

<?php if (in_array(\osWFrame\Core\Settings::getAction(), ['about'])): ?>

	<?php include \osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'tpl'.DIRECTORY_SEPARATOR.'about.tpl.php'; ?>

<?php elseif (in_array(\osWFrame\Core\Settings::getAction(), ['changelog'])): ?>

	<?php include \osWFrame\Core\Settings::getStringVar('settings_abspath').'resources'.DIRECTORY_SEPARATOR.'tpl'.DIRECTORY_SEPARATOR.'changelog.tpl.php'; ?>

<?php else: ?>

	<div class="row">
		<div class="col">
			<?php if (count($Tool->getLogDirs())>0): ?>
				<div class="btn-group w-100">
					<button type="button" class="btn btn-primary dropdown-flex-right dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
						<?php if ($curdir!=''): ?><?php echo \osWFrame\Core\HTML::outputString(substr($curdir, 0, -1)) ?><?php else: ?>Select Class<?php endif ?>
					</button>
					<ul class="dropdown-menu">
						<?php foreach ($Tool->getLogDirs() as $_dir): ?>
							<a class="dropdown-item<?php if ($_dir==$curdir): ?> active<?php endif ?>" href="<?php echo $this->buildhrefLink('current', 'dir='.$_dir) ?>"><?php echo \osWFrame\Core\HTML::outputString(substr($_dir, 0, -1)) ?></a>
						<?php endforeach ?>
					</ul>
				</div>
			<?php else: ?>
				<button class="btn btn-primary dropdown-flex-right dropdown-toggle w-100" type="button" id="dropdownDir" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">---</button>
			<?php endif ?>
		</div>
		<div class="col">
			<?php if (count($Tool->getLogFiles())>0): ?>
				<div class="btn-group w-100">
					<button type="button" class="btn btn-primary dropdown-flex-right dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
						<?php if ($curfile!=''): ?><?php echo \osWFrame\Core\HTML::outputString($curfile) ?><?php else: ?>Select Logfile<?php endif ?>
					</button>
					<ul class="dropdown-menu">
						<?php foreach ($Tool->getLogFiles() as $date=>$files): ?>

							<?php if (strlen($date)==8): ?>
								<h6 class="dropdown-header bg-secondary text-light"><?php echo substr($date, 0, 4) ?>.<?php echo substr($date, 4, 2) ?>.<?php echo substr($date, 6, 2) ?></h6>
							<?php endif ?>

							<?php foreach ($files as $_file): ?>
								<a class="dropdown-item<?php if ($_file==$curfile): ?> active<?php endif ?>" href="<?php echo $this->buildhrefLink('current', 'dir='.$curdir.'&file='.$_file.'&display='.$curdisplay) ?>"><?php echo \osWFrame\Core\HTML::outputString(str_replace($date.'_', '', $_file)) ?></a>
							<?php endforeach ?>

						<?php endforeach ?>
					</ul>
				</div>
			<?php else: ?>
				<button class="btn btn-primary dropdown-flex-right dropdown-toggle w-100" type="button" id="dropdownFile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">---</button>
			<?php endif ?>
		</div>
		<div class="col">
			<div class="btn-group w-100">
				<button type="button" class="btn btn-primary dropdown-flex-right dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
					<?php echo \osWFrame\Core\HTML::outputString(\osWFrame\Tools\Tool\LogBrowser::getCurrentDisplayOption($Tool->getFileDetailType(), $curdisplay)) ?>
				</button>
				<ul class="dropdown-menu">
					<?php foreach ($Tool->getDisplayOptions() as $_display=>$display): ?>
						<a class="dropdown-item<?php if ($_display==$curdisplay): ?> active<?php endif ?>" href="<?php echo $this->buildhrefLink('current', 'dir='.$curdir.'&file='.$curfile.'&display='.$_display) ?>"><?php echo \osWFrame\Core\HTML::outputString($display) ?></a>
					<?php endforeach ?>
				</ul>
			</div>
		</div>
	</div>
	<div class="row mt-3">
		<div class="col">
			<?php if (count($Tool->getLogDirs())==0): ?>
				<div class="alert alert-info" role="alert">
					No logfiles available.
				</div>

			<?php endif ?>

			<?php if ($Tool->getFileDetailType()=='csv'): ?>
				<div class="table-responsive">
					<table id="oswtools_logbrowser" class="table table-striped table-bordered">
						<thead>
						<tr>
							<?php foreach ($Tool->getFileDetailHead() as $head): ?>
								<th><?php echo \osWFrame\Core\HTML::outputString($head) ?></th>
							<?php endforeach ?>
						</tr>
						</thead>
						<tbody>
						<?php foreach ($Tool->getFileDetailLines() as $lines): ?>
							<tr>
								<?php foreach ($lines as $line): ?>
									<td><?php echo \osWFrame\Core\HTML::outputString($line) ?></td>
								<?php endforeach ?>
							</tr>
						<?php endforeach ?>
						</tbody>
					</table>
				</div>
			<?php else: ?>

				<?php echo $Tool->getFileDetailContent(); ?>

			<?php endif ?>

		</div>
	</div>

<?php endif ?>