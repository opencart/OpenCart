<?php
namespace Opencart\Install\Controller\Install;
use Melbahja\Seo\MetaTags;

/**
 * Class Step1
 *
 * @package Opencart\Install\Controller\Install
 */
class Step1 extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('install/step_1');

		$metadata = new MetaTags();
		$metadata->title($this->language->get('heading_title'));
		$this->document->setSeo($metadata);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_step_1'] = $this->language->get('text_step_1');
		$data['text_terms'] = $this->language->get('text_terms');

		$data['button_continue'] = $this->language->get('button_continue');

		$data['continue'] = $this->url->link('install/step_2', 'language=' . $this->config->get('language_code'));

		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['language'] = $this->load->controller('common/language');

		$this->response->setOutput($this->load->view('install/step_1', $data));
	}
}
