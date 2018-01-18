<?php
class ControllerAccountSuccess extends Controller {
	public function index() {
		$this->load->language('account/success');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();
	
		if(isset($this->request->get['mail'])){
			$response = $this->request->get['response'];

			if($response){
				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_success'),
					'href' => $this->url->link('account/success')
				);

				
				$data['text_message'] = "<p>Merci d'avoir pris le temps de valider votre adresse mail. <br> Vous pouvez dès maintenant vous connecter avec votre adresse mail et mot de passe.</<p>";
				$data['continue'] = $this->url->link('common/home');
			} else {
				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_failure'),
					'href' => $this->url->link('account/success')
				);

				$data['text_message'] = $this->language->get('text_disapproval');
				$data['continue'] = $this->url->link('common/home');
			}
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_success'),
				'href' => $this->url->link('account/success')
			);

			if ($this->customer->isLogged()) {
				$data['text_message'] = sprintf($this->language->get('text_message'), $this->url->link('information/contact'));
			} else {
				$data['text_message'] = sprintf($this->language->get('text_approval'), $this->config->get('config_name'), $this->url->link('information/contact'));
			}

			if ($this->cart->hasProducts()) {
				$data['continue'] = $this->url->link('checkout/cart');
			} else {
				$data['continue'] = $this->url->link('account/account', '', true);
			}
		}


		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		

		

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/success', $data));
	}
}