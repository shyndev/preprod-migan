<?php
class ControllerCheckoutGoodies extends Controller {
	public function index(&$data) {
		$this->load->language('checkout/goodies');
		$this->load->model('checkout/goodies');


		$products = $this->cart->getProducts();
		$montant = 0;

		foreach ($products as $product) {
			$total = $product['total'];
			$montant = $montant + $total;
		}

		$goodies = $this->model_checkout_goodies->getGoodies($montant);
		
		if(!empty($goodies)){
			$data = array();
			$data['goodies'] = $goodies;
			$data['image_url'] = HTTP_SERVER.'image/';
			return $this->load->view('checkout/goodies', $data);
		} else {
			return false;
		}
	}

	public function goodie(){

		if(!isset($this->request->post['id_type'])){
			$this->session->data['goodies'] = null;
		} else if($this->request->post['id_type'] == 2){
			$this->session->data['goodies']['type'] = $this->request->post['id_type'];
			$this->session->data['goodies']['sexe'] = $this->request->post['id_sexe'];
			$this->session->data['goodies']['taille'] = "1";
			$this->session->data['goodies']['couleur'] = "1";
		} else if($this->request->post['id_type'] == 3){
			$this->session->data['goodies']['type'] = $this->request->post['id_type'];
			$this->session->data['goodies']['sexe'] = $this->request->post['id_sexe'];
			$this->session->data['goodies']['taille'] = "1";
			$this->session->data['goodies']['couleur'] = "3";
		} else {
			$this->session->data['goodies']['type'] = $this->request->post['id_type'];
			$this->session->data['goodies']['sexe'] = $this->request->post['id_sexe'];
			$this->session->data['goodies']['taille'] = $this->request->post['radio-taille'];
			$this->session->data['goodies']['couleur'] = $this->request->post['radio-couleur'];
		}

		echo json_encode(true);
	}

	public function record($data){
		$this->load->model('checkout/goodies');
		$response = $this->model_checkout_goodies->distributeGoodie($data['type'], $data['sexe'], $data['taille'], $data['couleur'], $id_order);
		return $response;
	}

	public function taille(){
		$this->load->model('checkout/goodies');
		$id_type = $this->request->get['id_type'];
		$id_sexe = $this->request->get['id_sexe'];

		$data = $this->model_checkout_goodies->getTaille($id_type, $id_sexe);
		echo json_encode($data);
	}

	public function couleur(){
		$this->load->model('checkout/goodies');
		$id_type = $this->request->get['id_type'];
		$id_sexe = $this->request->get['id_sexe'];
		$id_taille = $this->request->get['id_taille'];

		$data = $this->model_checkout_goodies->getCouleur($id_type, $id_sexe, $id_taille);
		echo json_encode($data);
	}
}