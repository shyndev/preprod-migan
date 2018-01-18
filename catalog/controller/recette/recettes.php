<?php
class ControllerRecetteRecettes extends Controller {
	public function index() {
		$this->load->language('recette/recettes');
		$this->load->model('catalog/recette');
		$this->load->model('tool/image');

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
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

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_recette_limit');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->get['path'])) {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$recettes_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}

				$recettes_info = $this->model_catalog_recettes->getRecettes($path_id);

				if ($recettes_info) {
					$data['breadcrumbs'][] = array(
						'text' => $recettes_info['name'],
						'href' => $this->url->link('recette/recettes', 'path=' . $path . $url)
					);
				}
			}
		} else {
			$recettes_id = 0;
			$path = '';
		}

		$recettes_info = $this->model_catalog_recette->getRecettes();

		if ($recettes_info) {
			foreach ($recettes_info as $recettes) {
				$title = 'Recettes';
				$description = 'Nos suggestions de recettes';

				$this->document->setTitle($title);
				$this->document->setDescription($description);
				$this->document->setKeywords($title);

				$data['heading_title'] = $title;

				$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));

				// Set the last recettes breadcrumb
				$data['breadcrumbs'][] = array(
					'text' => $title,
					'href' => $this->url->link('recette/recettes', 'path=' . $path)
				);

				if ($recettes['image']) {
					$data['thumb'] = $this->model_tool_image->resize($recettes['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				} else {
					$data['thumb'] = '';
				}

				$data['description'] = html_entity_decode($description, ENT_QUOTES, 'UTF-8');
				$data['compare'] = $this->url->link('product/compare');

				$url = '';

				if (isset($this->request->get['filter'])) {
					$url .= '&filter=' . $this->request->get['filter'];
				}

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}

				$data['recettes'] = array();

				$filter_data = array(
					'filter_recettes_id' => $recettes_id,
					'filter_filter'      => $filter,
					'sort'               => $sort,
					'order'              => $order,
					'start'              => ($page - 1) * $limit,
					'limit'              => $limit
				);


				$recette_total = $this->model_catalog_recette->getTotalRecettes($filter_data);

				$results = $this->model_catalog_recette->getRecettes($filter_data);

				foreach ($results as $result) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
					}

					if ($this->config->get('config_review_status')) {
						$rating = (int)$result['rating'] = 0;
					} else {
						$rating = false;
					}

					$data['recettes'][] = array(
						'recette_id'  => $result['recette_id'],
						'thumb'       => $image,
						'name'        => $result['name'],
						'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
						'rating'      => $result['rating'],
						'href'        => $this->url->link('recette/recette', 'path=' . $path . '&recette_id=' . $result['recette_id'] . $url)
					);
				}

				$url = '';

				if (isset($this->request->get['filter'])) {
					$url .= '&filter=' . $this->request->get['filter'];
				}

				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}

				$data['sorts'] = array();

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_default'),
					'value' => 'p.sort_order-ASC',
					'href'  => $this->url->link('recette/recettes', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
				);

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_name_asc'),
					'value' => 'pd.name-ASC',
					'href'  => $this->url->link('recette/recettes', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
				);

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_name_desc'),
					'value' => 'pd.name-DESC',
					'href'  => $this->url->link('recette/recettes', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
				);

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_price_asc'),
					'value' => 'p.price-ASC',
					'href'  => $this->url->link('recette/recettes', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
				);

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_price_desc'),
					'value' => 'p.price-DESC',
					'href'  => $this->url->link('recette/recettes', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
				);

				if ($this->config->get('config_review_status')) {
					$data['sorts'][] = array(
						'text'  => $this->language->get('text_rating_desc'),
						'value' => 'rating-DESC',
						'href'  => $this->url->link('recette/recettes', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url)
					);

					$data['sorts'][] = array(
						'text'  => $this->language->get('text_rating_asc'),
						'value' => 'rating-ASC',
						'href'  => $this->url->link('recette/recettes', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url)
					);
				}

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_model_asc'),
					'value' => 'p.model-ASC',
					'href'  => $this->url->link('recette/recettes', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url)
				);

				$data['sorts'][] = array(
					'text'  => $this->language->get('text_model_desc'),
					'value' => 'p.model-DESC',
					'href'  => $this->url->link('recette/recettes', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url)
				);

				$url = '';

				if (isset($this->request->get['filter'])) {
					$url .= '&filter=' . $this->request->get['filter'];
				}

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				$data['limits'] = array();

				$limits = array_unique(array($this->config->get('theme_' . $this->config->get('config_theme') . '_recette_limit'), 25, 50, 75, 100));

				sort($limits);

				foreach($limits as $value) {
					$data['limits'][] = array(
						'text'  => $value,
						'value' => $value,
						'href'  => $this->url->link('recette/recettes', 'path=' . $this->request->get['path'] . $url . '&limit=' . $value)
					);
				}

				$url = '';

				if (isset($this->request->get['filter'])) {
					$url .= '&filter=' . $this->request->get['filter'];
				}

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}

				$pagination = new Pagination();
				$pagination->total = $recette_total;
				$pagination->page = $page;
				$pagination->limit = $limit;
				$pagination->url = $this->url->link('recette/recettes', 'path=' . $this->request->get['path'] . $url . '&page={page}');

				$data['pagination'] = $pagination->render();

				$data['results'] = sprintf($this->language->get('text_pagination'), ($recette_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($recette_total - $limit)) ? $recette_total : ((($page - 1) * $limit) + $limit), $recette_total, ceil($recette_total / 1));

				// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
				if ($page == 1) {
				    $this->document->addLink($this->url->link('recette/recettes', 'path=' . $recettes['recette_id']), 'canonical');
				} else {
					$this->document->addLink($this->url->link('recette/recettes', 'path=' . $recettes['recette_id'] . '&page='. $page), 'canonical');
				}
				
				if ($page > 1) {
				    $this->document->addLink($this->url->link('recette/recettes', 'path=' . $recettes['recette_id'] . (($page - 2) ? '&page='. ($page - 1) : '')), 'prev');
				}

				if ($limit && ceil($recette_total / $limit) > $page) {
				    $this->document->addLink($this->url->link('recette/recettes', 'path=' . $recettes['recette_id'] . '&page='. ($page + 1)), 'next');
				}

				$data['sort'] = $sort;
				$data['order'] = $order;
				$data['limit'] = $limit;
				$data['continue'] = $this->url->link('common/home');
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['content_top'] = $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');

				// var_dump($data);
				$this->response->setOutput($this->load->view('recette/recettes', $data));
			}
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
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

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('recette/recettes', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}
}
