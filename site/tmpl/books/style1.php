<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Employee
 * @author     Vaibhav Shinde <vaibhav87shinde@gmail.com>
 * @copyright  2024 Vaibhav Shinde
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Layout\LayoutHelper;
use \Joomla\CMS\Session\Session;
use \Joomla\CMS\User\UserFactoryInterface;

HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

$user       = Factory::getApplication()->getIdentity();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_employee') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'employeeform.xml');
$canEdit    = $user->authorise('core.edit', 'com_employee') && file_exists(JPATH_COMPONENT .  DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'employeeform.xml');
$canCheckin = $user->authorise('core.manage', 'com_employee');
$canChange  = $user->authorise('core.edit.state', 'com_employee');
$canDelete  = $user->authorise('core.delete', 'com_employee');

// Import CSS
$wa = $this->document->getWebAssetManager();
$wa->useStyle('com_employee.list');
?>

<h1>Style: 1</h1>
<div class="container text-center">
<div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
			<?php foreach ($this->items as $i => $item) : ?>
				<?php $canEdit = $user->authorise('core.edit', 'com_employee'); ?>
				<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_employee')): ?>
				<?php $canEdit = Factory::getApplication()->getIdentity()->id == $item->created_by; ?>
				<?php endif; ?>

				<div class="col align-items-center b-2">
						<?php $canCheckin = Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_employee.' . $item->id) || $item->checked_out == Factory::getApplication()->getIdentity()->id; ?>
						
							<h3 class="fs-2 text-body-emphasis"><?php echo $this->escape($item->booktitle); ?></h3>
							<?php echo $item->bookauthor; ?>
							<p><?php echo $item->bookpublisher; ?></p>
						
					
					<a href="<?php echo Route::_('index.php?option=com_employee&task=employee.view&id=' . $item->id, false, 2); ?>" class="btn btn-primary" type="button">Read More</a>

				</div>
			<?php endforeach; ?>
			
	</div>
	</div>
	<?php if ($canCreate) : ?>
		<a href="<?php echo Route::_('index.php?option=com_employee&task=employeeform.edit&id=0', false, 0); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo Text::_('COM_EMPLOYEE_ADD_ITEM'); ?></a>
	<?php endif; ?>