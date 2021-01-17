<?php
namespace Opencart\Application\Controller\Common;
class Footer extends \Opencart\System\Engine\Controller {
	public function index() {
		$this->load->language('common/footer');
		
		$data['text_project'] = $this->language->get('text_project');
		$data['text_documentation'] = $this->language->get('text_documentation');
		$data['text_support'] = $this->language->get('text_support');
		$data['text_copyright'] = $this->language->get('text_copyright');

		return $this->load->view('common/footer', $data);
	}
}
