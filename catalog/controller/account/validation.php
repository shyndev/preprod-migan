<?php
class ControllerAccountValidation extends Controller {
	public function index() {
		$this->load->language('account/validation');
		$this->load->model('account/validation');
		
		$customer_id = $this->request->get['customer'];
		$token = $this->request->get['token'];
		$data['mail'] = '';
		$data['response'] = $this->model_account_validation->validationCustomer($customer_id, $token);

		$this->response->redirect($this->url->link('account/success', $data));
	}
}