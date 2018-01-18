<?php
class ControllerCheckoutStock extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));
		$this->load->model('checkout/stock');

		$stock = $this->model_checkout_stock->getProducts($this->session->data['customer_id']);
		// var_dump($stock);

		foreach ($stock as $product) {
			// Display prices
			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$unit_price = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));
				
				$price = $this->currency->format($unit_price, $this->session->data['currency']);
			} else {
				$price = false;
			}

			$this->load->model('tool/image');
			$this->load->model('tool/upload');

			if ($product['image']) {
				$image = $this->model_tool_image->resize($product['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_height'));
			} else {
				$image = '';
			}

			$stock_quantite = $this->model_checkout_stock->getStock($this->session->data['customer_id'], $product['product_id']);

			$data['products'][] = array(
				'product_id'   => $product['product_id'],
				'thumb'     => $image,
				'name'      => $product['name'],
				'stock_client'  => $stock_quantite['stock_client'],
				'stock_reference'  => $stock_quantite['stock_reference'],
				'stock'     => $product['quantity'],
				'price'     => $price,
				'href'      => $this->url->link('product/product', 'product_id=' . $product['product_id'])
			);

		}

		$data['action'] = $this->url->link('checkout/stock/update', '', true);
		$data['continue'] = $this->url->link('common/home');
		$data['cart'] = $this->url->link('checkout/cart', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['heading_title'] = $this->language->get('text_stock');

		$this->load->language('account/account');
		
		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home'),
			'text' => $this->language->get('text_home')
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('account/account'),
			'text' => $this->language->get('heading_title')
		);


		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('checkout/stock'),
			'text' => $this->language->get('text_stock')
		);

		
		// var_dump($data['action']);
		$this->response->setOutput($this->load->view('checkout/stock', $data));
	}

	public function migrate(){
		$this->load->model('checkout/stock');

		$this->model_checkout_stock->migration();

		$this->response->redirect($this->url->link('checkout/stock'));
		
	}

	public function update(){
		$this->load->model('checkout/stock');

		$json = array();

		// Update
		if (!empty($this->request->post['quantity'])) {
			foreach ($this->request->post['quantity'] as $product_id => $quantity) {
				$this->model_checkout_stock->updateStockClient($this->session->data['customer_id'], $product_id, $quantity);
			}
			$this->response->redirect($this->url->link('checkout/stock'));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function products(){
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$this->load->model('tool/image');

		if($this->request->get['type'] != 1){
			$limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
			$page = 1;
			$data = array(
				'filter_category_id' => $this->request->get['type'],
				'sort'               => 'p.sort_order',
				'order'              => 'ASC',
			);
		} else {
			$data = array(
				'liste_produit_categorie' => true,
				'order'                   => 'ASC',
			);
		}
		

		$products = $this->model_catalog_product->getProducts($data);
		$data = array();
		$i = 0;

		foreach ($products as $product) {
			$data[$i]['product_id'] = $product['product_id'];
			$data[$i]['name'] = $product['name'];
			
			$product_attributes = $this->model_catalog_product->getProductAttributes($product['product_id']);
			
			if(isset($product_attributes[0]['attribute'][0])){
				$data[$i]['conditionnement'] = $product_attributes[0]['attribute'][0]['text'];
			} else {
				$data[$i]['conditionnement'] = '';
			}

			$categorie = $this->model_catalog_product->getCategories($product['product_id']);
			$product_categorie = $this->model_catalog_category->getCategory($categorie[0]['category_id']);
			// var_dump($product_categorie);
			$lien_image = $product['image'];
			$image = $this->model_tool_image->resize($lien_image, $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_height'));

			$data[$i]['product_categorie'] = $product_categorie['name'];
			$data[$i]['image'] = HTTP_SERVER.'image/'.$lien_image;
			$data[$i]['image_resize'] = $image;
			$data[$i]['price'] = $product['price'];
			$data[$i]['href'] = $this->url->link('product/product', 'product_id=' . $product['product_id']);

			$i++;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}

	public function aide(){
		$url = HTTP_SERVER.'image/stock_aide/';

		$html = '<style>#doc_aide img{width:1050px; height:auto; display:block; margin: 5px auto 0px;border: 1px solid #dcdcdc;} .aide_legende {width:1000px;margin: 0px auto 20px;background-color: #dcdcdc;border: 1px solid #dcdcdc;padding: 10px 25px;font-size: 18px;font-family: Cambria, Calibri, Tahoma, sans-serif;text-align:justify;-moz-text-align-last: center; text-align-last: center;} .text-danger{color: red;}</style>';
		$html .= '<div id="doc_aide">';

		$html .= '<img src="'.$url.'info_etat_stock.png" alt="migan">';
		$html .= '<div class="aide_legende">Cette page présente votre stock. Depuis ce stock, vous pouvez choisir les produits que vous souhaitez commander. Des informations visuelles vous signalent lorsque votre stock est bientôt ou déjà épuisé.</div>';

		$html .= '<img src="'.$url.'miseajour_stock.png" alt="migan">';
		$html .= '<div class="aide_legende">Vous avez la possibilité de mettre à jour votre stock en appuyant sur le bouton présenté sur l\'image ci-dessus.</div>';

		$html .= '<img src="'.$url.'ajouter_produit.png" alt="migan">';
		$html .= '<div class="aide_legende">En appuyant sur le bouton "Ajouter des produits", une fenêtre s\'affichera et présentera une liste des produits disponible sur Migan. Un filtre est mis à disposition pour une meilleure vision des produits. Vous pourrez dès lors ajouter des produits à votre stock avant de les ajouter au panier. <b class="text-danger">Attention: Les produits ajoutés au stock ne sont pas définitevement enregistrés, vous devez les ajouter au panier avant de quitter la page de votre stock.</b></div>';

		$html .= '<img src="'.$url.'ajouter_panier.png" alt="migan">';
		$html .= '<div class="aide_legende">Une fois votre sélection faite, vous pouvez alors les ajouter au panier en appuyant sur le bouton "ajouter au panier".</div>';

		$html .= '<img src="'.$url.'panier.png" alt="migan">';
		$html .= '<div class="aide_legende">Vous serez redirigé vers votre panier avec vos produits ajoutés depuis votre stock. Vous pourrez modifier la quantité à commander depuis cette page avant de passer la commande.</div>';
	
		$html .= '<img src="'.$url.'stock_miseajour.png" alt="migan">';
		$html .= '<div class="aide_legende">Après chaque commande, les nouveaux produits sont automatiquement ajoutés au stock de façon définive et votre stock personnel est également mis à jour.</div>';
	
		$html .= '</div>';

		echo $html;
	}
}