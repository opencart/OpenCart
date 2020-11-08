<?php
namespace Opencart\Application\Controller\Extension\Opencart\Report;
class CustomerOrder extends \Opencart\System\Engine\Controller {
	public function index() {
		$this->load->language('extension/opencart/report/customer_order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('report_customer_order', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report'));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/opencart/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/opencart/report/customer_order', 'user_token=' . $this->session->data['user_token'])
		];

		$data['action'] = $this->url->link('extension/opencart/report/customer_order', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report');

		if (isset($this->request->post['report_customer_order_status'])) {
			$data['report_customer_order_status'] = $this->request->post['report_customer_order_status'];
		} else {
			$data['report_customer_order_status'] = $this->config->get('report_customer_order_status');
		}

		if (isset($this->request->post['report_customer_order_sort_order'])) {
			$data['report_customer_order_sort_order'] = $this->request->post['report_customer_order_sort_order'];
		} else {
			$data['report_customer_order_sort_order'] = $this->config->get('report_customer_order_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/report/customer_order_form', $data));
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/opencart/report/customer_order')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
			
	public function report() {
		$this->load->language('extension/opencart/report/customer_order');

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = '';
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = (int)$this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = 0;
		}
		
		if (isset($this->request->get['filter_affiliate'])) {
			$filter_affiliate = $this->request->get['filter_affiliate'];
		} else {
			$filter_affiliate = '';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$this->load->model('extension/opencart/report/customer');

		$data['customers'] = [];

		$filter_data = [
			'filter_date_start'			=> $filter_date_start,
			'filter_date_end'			=> $filter_date_end,
			'filter_customer'			=> $filter_customer,
			'filter_order_status_id'	=> $filter_order_status_id,
			'filter_affiliate'			=> $filter_affiliate,
			'start'						=> ($page - 1) * $this->config->get('config_pagination'),
			'limit'						=> $this->config->get('config_pagination')
		];

		$customer_total = $this->model_extension_opencart_report_customer->getTotalOrders($filter_data);

		$results = $this->model_extension_opencart_report_customer->getOrders($filter_data);

		foreach ($results as $result) {
			$data['customers'][] = [
				'customer'       => $result['customer'],
				'email'          => $result['email'],
				'customer_group' => $result['customer_group'],
				'status'         => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'orders'         => $result['orders'],
				'products'       => $result['products'],
				'commission'	 => $this->currency->format($result['commission'], $this->config->get('config_currency')),
				'total'          => $this->currency->format($result['total'], $this->config->get('config_currency')),
				'edit'           => $this->url->link('customer/customer|edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id'])
			];
		}

		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode($this->request->get['filter_customer']);
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		if (isset($this->request->get['filter_affiliate'])) {
			$url .= '&filter_affiliate=' . $this->request->get['filter_affiliate'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $customer_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination'),
			'url'   => $this->url->link('extension/opencart/report/customer_order|report', 'user_token=' . $this->session->data['user_token'] . '&code=customer_order' . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_pagination')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination')) > ($customer_total - $this->config->get('config_pagination'))) ? $customer_total : ((($page - 1) * $this->config->get('config_pagination')) + $this->config->get('config_pagination')), $customer_total, ceil($customer_total / $this->config->get('config_pagination')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_customer'] = $filter_customer;
		$data['filter_order_status_id'] = $filter_order_status_id;
		$data['filter_affiliate'] = $filter_affiliate;

		$this->response->setOutput($this->load->view('extension/opencart/report/customer_order', $data));
	}
	
	public function autocomplete() {
		$json = [];

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_affiliate'])) {
			$filter_affiliate = $this->request->get['filter_affiliate'];
		} else {
			$filter_affiliate = '';
		}
		
		$this->load->model('extension/report/customer');

		$filter_data = [
			'filter_name'      => $filter_name,
			'filter_affiliate' => $filter_affiliate,
			'start'            => 0,
			'limit'            => 5
		];

		$results = $this->model_extension_report_customer->getOrders($filter_data);

		foreach ($results as $result) {
			$json[] = ['affiliate_id'			=> $result['affiliate_id'],
					   'customer'				=> $result['customer'],					  				   
					  ];
		}	

		$sort_order = [];

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['customer'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
