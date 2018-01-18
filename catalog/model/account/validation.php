<?php
class ModelAccountValidation extends Model {
	public function validationCustomer($customer_id, $customer_token) {
		$response = false;

		$query = $this->db->query("SELECT token, status FROM `" . DB_PREFIX . "customer` WHERE customer_id = '" . $customer_id . "'");
		$token = $query->row['token'];
		$status = $query->row['status'];

		if($status == 0){
			if($customer_token == $token){
				$query = $this->db->query("UPDATE `" . DB_PREFIX . "customer` SET status = 1 WHERE customer_id = '" . $customer_id . "' ");
				if($query){
					$response = true;
				}
			}
		}

		return $response;
	}
}