<?php
class ControllerCatalogGoodie extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/goodie');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/goodie');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/goodie');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/goodie');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_goodie->addGoodie($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/goodie', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/goodie');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/goodie');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_goodie->editGoodie($this->request->get['goodie_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/goodie', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/goodie');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/goodie');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $goodie_id) {
				$this->model_catalog_goodie->deleteGoodie($goodie_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/goodie', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	public function copy() {
		$this->load->language('catalog/goodie');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/goodie');

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $goodie_id) {
				$this->model_catalog_goodie->copyGoodie($goodie_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/goodie', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_sexe'])) {
			$filter_sexe = $this->request->get['filter_sexe'];
		} else {
			$filter_sexe = '';
		}
		
		if (isset($this->request->get['filter_taille'])) {
			$filter_taille = $this->request->get['filter_taille'];
		} else {
			$filter_taille = '';
		}
		
		if (isset($this->request->get['filter_couleur'])) {
			$filter_couleur = $this->request->get['filter_couleur'];
		} else {
			$filter_couleur = '';
		}
		
		if (isset($this->request->get['filter_type'])) {
			$filter_type = $this->request->get['filter_type'];
		} else {
			$filter_type = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_taille'])) {
			$url .= '&filter_taille=' . $this->request->get['filter_taille'];
		}

		if (isset($this->request->get['filter_sexe'])) {
			$url .= '&filter_sexe=' . $this->request->get['filter_sexe'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . $this->request->get['filter_type'];
		}
		if (isset($this->request->get['filter_couleur'])) {
			$url .= '&filter_type=' . $this->request->get['filter_couleur'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/goodie', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('catalog/goodie/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['copy'] = $this->url->link('catalog/goodie/copy', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('catalog/goodie/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['goodies'] = array();

		$filter_data = array(
			'filter_name'	  => $filter_name,
			'filter_type' => $filter_type,
			'filter_sexe' => $filter_sexe,
			'filter_taille' => $filter_taille,
			'filter_couleur' => $filter_couleur,
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$this->load->model('tool/image');

		$goodie_total = $this->model_catalog_goodie->getTotalGoodies($filter_data);

		$results = $this->model_catalog_goodie->getGoodies($filter_data);

		$data['tailles']=$this->model_catalog_goodie->getGoodieTailles();
		$data['sexes']=$this->model_catalog_goodie->getGoodieSexes();
		$data['couleurs']=$this->model_catalog_goodie->getGoodieCouleurs();
		$data['types']=$this->model_catalog_goodie->getGoodieTypes();
		
		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}

			$special = false;

			$goodie_specials = array() ;

			$data['goodies'][] = array(
				'goodie_id' => $result['goodie_id'],
				'image'      => $image,
				'name'       => $result['nom_lot'],
				'reference'  => $result['ref_lot'],
				'taille'	=> strtoupper($this->model_catalog_goodie->getGoodieTaille($result['goodie_id'])),
				'sexe'		=> $this->model_catalog_goodie->getGoodieSexe($result['goodie_id']),
				'couleur'	=> $this->model_catalog_goodie->getGoodieCouleur($result['goodie_id']),
				'type'		=> $this->model_catalog_goodie->getGoodieType($result['goodie_id']),
				'status'     => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'edit'       => $this->url->link('catalog/goodie/edit', 'user_token=' . $this->session->data['user_token'] . '&goodie_id=' . $result['goodie_id'] . $url, true)
			);
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_taille'])) {
			$url .= '&filter_taille=' . $this->request->get['filter_taille'];
		}
		if (isset($this->request->get['filter_sexe'])) {
			$url .= '&filter_sexe=' . $this->request->get['filter_sexe'];
		}
		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . $this->request->get['filter_type'];
		}if (isset($this->request->get['filter_couleur'])) {
			$url .= '&filter_couleur=' . $this->request->get['filter_couleur'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('catalog/goodie', 'user_token=' . $this->session->data['user_token'] . '&sort=p.name' . $url, true);
		$data['sort_status'] = $this->url->link('catalog/goodie', 'user_token=' . $this->session->data['user_token'] . '&sort=p.status' . $url, true);
		$data['sort_order'] = $this->url->link('catalog/goodie', 'user_token=' . $this->session->data['user_token'] . '&sort=p.sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		if (isset($this->request->get['filter_taille'])) {
			$url .= '&filter_taille=' . $this->request->get['filter_taille'];
		}
		if (isset($this->request->get['filter_sexe'])) {
			$url .= '&filter_sexe=' . $this->request->get['filter_sexe'];
		}
		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . $this->request->get['filter_type'];
		}if (isset($this->request->get['filter_couleur'])) {
			$url .= '&filter_couleur=' . $this->request->get['filter_couleur'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $goodie_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/goodie', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($goodie_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($goodie_total - $this->config->get('config_limit_admin'))) ? $goodie_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $goodie_total, ceil($goodie_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_status'] = $filter_status;
		$data['filter_type'] = $filter_type;
		$data['filter_sexe'] = $filter_sexe;
		$data['filter_taille'] = $filter_taille;
		$data['filter_couleur'] = $filter_couleur;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/goodie_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['goodie_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = array();
		}

		if (isset($this->error['model'])) {
			$data['error_model'] = $this->error['model'];
		} else {
			$data['error_model'] = '';
		}

		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/goodie', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['goodie_id'])) {
			$data['action'] = $this->url->link('catalog/goodie/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('catalog/goodie/edit', 'user_token=' . $this->session->data['user_token'] . '&goodie_id=' . $this->request->get['goodie_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('catalog/goodie', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['goodie_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$goodie_info = $this->model_catalog_goodie->getGoodie($this->request->get['goodie_id']);
		}


		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['goodie_description'])) {
			$data['goodie_description'] = $this->request->post['goodie_description'];
		} else {
			$data['goodie_description'] = array();
		}

		$this->load->model('setting/store');

		$data['stores'] = array();
		
		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		);
		
		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			);
		}

		$this->load->model('catalog/recurring');

		$data['recurrings'] = $this->model_catalog_recurring->getRecurrings();

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();


		$this->load->model('localisation/stock_status');
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($goodie_info)) {
			$data['status'] = $goodie_info['status'];
		} else {
			$data['status'] = true;
		}

		if (!empty($goodie_info)) {
			
			$this->load->model('localisation/length_class');
		}
		
		$this->load->model('catalog/product');
		
		
		if(!empty($goodie_info))
			$data['goodie'] = $goodie_info;
		else
			$data['goodie'] = array();

		$data['tailles']=$this->model_catalog_goodie->getGoodieTailles();
		$data['sexes']=$this->model_catalog_goodie->getGoodieSexes();
		$data['couleurs']=$this->model_catalog_goodie->getGoodieCouleurs();
		$data['types']=$this->model_catalog_goodie->getGoodieTypes();
		
		
		// Image
		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($goodie_info)) {
			$data['image'] = $goodie_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($goodie_info) && is_file(DIR_IMAGE . $goodie_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($goodie_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/goodie_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/goodie')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

			if ((utf8_strlen($this->request->post['nom_lot']) < 1) || (utf8_strlen($this->request->post['nom_lot']) > 255)) {
				$this->error['name'] = $this->language->get('error_name');
			}

		

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/goodie')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'catalog/goodie')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_model'])) {
			$this->load->model('catalog/goodie');
			$this->load->model('catalog/option');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_model'])) {
				$filter_model = $this->request->get['filter_model'];
			} else {
				$filter_model = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_model' => $filter_model,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_catalog_goodie->getGoodies($filter_data);

			foreach ($results as $result) {
				$option_data = array();

				$goodie_options = $this->model_catalog_goodie->getGoodieOptions($result['goodie_id']);

				foreach ($goodie_options as $goodie_option) {
					$option_info = $this->model_catalog_option->getOption($goodie_option['option_id']);

					if ($option_info) {
						$goodie_option_value_data = array();

						foreach ($goodie_option['goodie_option_value'] as $goodie_option_value) {
							$option_value_info = $this->model_catalog_option->getOptionValue($goodie_option_value['option_value_id']);

							if ($option_value_info) {
								$goodie_option_value_data[] = array(
									'goodie_option_value_id' => $goodie_option_value['goodie_option_value_id'],
									'option_value_id'         => $goodie_option_value['option_value_id'],
									'name'                    => $option_value_info['name'],
									'price'                   => (float)$goodie_option_value['price'] ? $this->currency->format($goodie_option_value['price'], $this->config->get('config_currency')) : false,
									'price_prefix'            => $goodie_option_value['price_prefix']
								);
							}
						}

						$option_data[] = array(
							'goodie_option_id'    => $goodie_option['goodie_option_id'],
							'goodie_option_value' => $goodie_option_value_data,
							'option_id'            => $goodie_option['option_id'],
							'name'                 => $option_info['name'],
							'type'                 => $option_info['type'],
							'value'                => $goodie_option['value'],
							'required'             => $goodie_option['required']
						);
					}
				}

				$json[] = array(
					'goodie_id' => $result['goodie_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'model'      => $result['model'],
					'option'     => $option_data,
					'price'      => $result['price']
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
