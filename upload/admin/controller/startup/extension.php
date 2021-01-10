<?php
namespace Opencart\Application\Controller\Startup;
class Extension extends \Opencart\System\Engine\Controller {
	public function index() {
		// Add extension paths from the DB
		$this->load->model('setting/extension');

		$results = $this->model_setting_extension->getInstalls();

		foreach ($results as $result) {
			$extension = str_replace(['_', '/'], ['', '\\'], ucwords($result['code'], '_/'));

			// Register controllers, models and system extension folders
			$this->autoloader->register('Opencart\Application\Controller\Extension\\' . $extension, DIR_EXTENSION . $result['code'] . '/admin/controller/');
			$this->autoloader->register('Opencart\Application\Model\Extension\\' . $extension, DIR_EXTENSION . $result['code'] . '/admin/model/');
			$this->autoloader->register('Opencart\System\Extension\\' . $extension, DIR_EXTENSION . $result['code'] . '/system/');

			// Template directory
			$this->template->addPath('extension/' . $result['code'], DIR_EXTENSION . $result['code'] . '/admin/view/template/');

			// Language directory
			$this->language->addPath('extension/' . $result['code'], DIR_EXTENSION . $result['code'] . '/admin/language/');

			// Config directory
			$this->config->addPath('extension/' . $result['code'], DIR_EXTENSION . $result['code'] . '/system/config/');
		}
	}
}
