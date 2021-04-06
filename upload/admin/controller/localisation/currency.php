<?php
namespace Opencart\Admin\Controller\Localisation;
class Currency extends \Opencart\System\Engine\Controller {
	private array $error = [];
	
	public function index(): void {
		$this->load->language('localisation/currency');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/currency');

		$this->getList();
	}

	public function add(): void {
		$this->load->language('localisation/currency');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/currency');

		$this->model_localisation_currency->addCurrency($this->request->post);

		$this->getForm();
	}

	public function edit(): void {
		$this->load->language('localisation/currency');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/currency');

		$this->model_localisation_currency->editCurrency($this->request->get['currency_id'], $this->request->post);

		$this->getForm();
	}

	public function refresh(): void {
		$this->load->language('localisation/currency');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/currency');

		if ($this->validateRefresh()) {
			$this->load->controller('extension/currency/' . $this->config->get('config_currency_engine') . '|currency', $this->config->get('config_currency'));

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('localisation/currency', 'user_token=' . $this->session->data['user_token'] . $url));
		}

		$this->getList();
	}

	protected function getList(): void {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'title';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/currency', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('localisation/currency|add', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('localisation/currency|delete', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['refresh'] = $this->url->link('localisation/currency|refresh', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['currencies'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$currency_total = $this->model_localisation_currency->getTotalCurrencies();

		$results = $this->model_localisation_currency->getCurrencies($filter_data);

		foreach ($results as $result) {
			$data['currencies'][] = [
				'currency_id'   => $result['currency_id'],
				'title'         => $result['title'] . (($result['code'] == $this->config->get('config_currency')) ? $this->language->get('text_default') : ''),
				'code'          => $result['code'],
				'value'         => $result['value'],
				'status'        => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'edit'          => $this->url->link('localisation/currency|edit', 'user_token=' . $this->session->data['user_token'] . '&currency_id=' . $result['currency_id'] . $url)
			];
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = [];
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_title'] = $this->url->link('localisation/currency', 'user_token=' . $this->session->data['user_token'] . '&sort=title' . $url);
		$data['sort_code'] = $this->url->link('localisation/currency', 'user_token=' . $this->session->data['user_token'] . '&sort=code' . $url);
		$data['sort_value'] = $this->url->link('localisation/currency', 'user_token=' . $this->session->data['user_token'] . '&sort=value' . $url);
		$data['sort_status'] = $this->url->link('localisation/currency', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url);
		$data['sort_date_modified'] = $this->url->link('localisation/currency', 'user_token=' . $this->session->data['user_token'] . '&sort=date_modified' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $currency_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('localisation/currency', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($currency_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($currency_total - $this->config->get('config_pagination_admin'))) ? $currency_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $currency_total, ceil($currency_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/currency_list', $data));
	}

	public function form(): void {
		$data['text_form'] = !isset($this->request->get['currency_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/currency', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		if (!isset($this->request->get['currency_id'])) {
			$data['action'] = $this->url->link('localisation/currency|add', 'user_token=' . $this->session->data['user_token'] . $url);
		} else {
			$data['action'] = $this->url->link('localisation/currency|edit', 'user_token=' . $this->session->data['user_token'] . '&currency_id=' . $this->request->get['currency_id'] . $url);
		}

		$data['cancel'] = $this->url->link('localisation/currency', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['currency_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$currency_info = $this->model_localisation_currency->getCurrency($this->request->get['currency_id']);
		}

		if (isset($this->request->post['title'])) {
			$data['title'] = $this->request->post['title'];
		} elseif (!empty($currency_info)) {
			$data['title'] = $currency_info['title'];
		} else {
			$data['title'] = '';
		}

		if (isset($this->request->post['code'])) {
			$data['code'] = (string)$this->request->post['code'];
		} elseif (!empty($currency_info)) {
			$data['code'] = $currency_info['code'];
		} else {
			$data['code'] = '';
		}

		if (isset($this->request->post['symbol_left'])) {
			$data['symbol_left'] = $this->request->post['symbol_left'];
		} elseif (!empty($currency_info)) {
			$data['symbol_left'] = $currency_info['symbol_left'];
		} else {
			$data['symbol_left'] = '';
		}

		if (isset($this->request->post['symbol_right'])) {
			$data['symbol_right'] = $this->request->post['symbol_right'];
		} elseif (!empty($currency_info)) {
			$data['symbol_right'] = $currency_info['symbol_right'];
		} else {
			$data['symbol_right'] = '';
		}

		if (isset($this->request->post['decimal_place'])) {
			$data['decimal_place'] = $this->request->post['decimal_place'];
		} elseif (!empty($currency_info)) {
			$data['decimal_place'] = $currency_info['decimal_place'];
		} else {
			$data['decimal_place'] = '';
		}

		if (isset($this->request->post['value'])) {
			$data['value'] = $this->request->post['value'];
		} elseif (!empty($currency_info)) {
			$data['value'] = $currency_info['value'];
		} else {
			$data['value'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = (int)$this->request->post['status'];
		} elseif (!empty($currency_info)) {
			$data['status'] = $currency_info['status'];
		} else {
			$data['status'] = 1;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/currency_form', $data));
	}

	public function save(): void {
		$this->load->language('localisation/currency');
		
		$json = [];
		
		if (!$this->user->hasPermission('modify', 'localisation/currency')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['title']) < 3) || (utf8_strlen($this->request->post['title']) > 32)) {
			$json['error']['title'] = $this->language->get('error_title');
		}

		if (utf8_strlen($this->request->post['code']) != 3) {
			$json['error']['code'] = $this->language->get('error_code');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('localisation/currency');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'localisation/currency')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/store');
		
		$this->load->model('sale/order');

		foreach ($selected as $currency_id) {
			$currency_info = $this->model_localisation_currency->getCurrency($currency_id);

			if ($currency_info) {
				if ($this->config->get('config_currency') == $currency_info['code']) {
					$json['error'] = $this->language->get('error_default');
				}

				$store_total = $this->model_setting_store->getTotalStoresByCurrency($currency_info['code']);

				if ($store_total) {
					$json['error'] = sprintf($this->language->get('error_store'), $store_total);
				}
			}

			$order_total = $this->model_sale_order->getTotalOrdersByCurrencyId($currency_id);

			if ($order_total) {
				$json['error'] = sprintf($this->language->get('error_order'), $order_total);
			}
		}

		if (!$json) {
			$this->load->model('localisation/currency');

			foreach ($selected as $currency_id) {
				$this->model_localisation_currency->deleteCurrency($currency_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validateRefresh(): bool {
		if (!$this->user->hasPermission('modify', 'localisation/currency')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
