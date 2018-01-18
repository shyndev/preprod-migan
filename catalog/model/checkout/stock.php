<?php
class ModelCheckoutstock extends Model {
	public function getProducts($customer_id){
		$sql = "SELECT p.*, pa.text, pd.name
				FROM " . DB_PREFIX . "order o, " . DB_PREFIX . "order_product op, " . DB_PREFIX . "product p, " . DB_PREFIX . "product_attribute pa, " . DB_PREFIX . "product_description pd
				WHERE o.order_id = op.order_id
				AND  op.product_id = p.product_id
				AND p.product_id = pa.product_id
				AND p.product_id = pd.product_id
				AND o.customer_id = $customer_id
				AND p.status = 1
				GROUP BY p.product_id";
		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getStock($customer_id, $product_id){
		$sql = "SELECT COUNT(*) as nombre, stock_client, stock_reference FROM " . DB_PREFIX . "customer_stock WHERE customer_id = $customer_id AND product_id = $product_id";
		$query = $this->db->query($sql);

		$tab = array();

		foreach ($query->rows as $row) {

			if($row['nombre'] == 0){
				$stock_client = 1;
				$stock_reference = 1;
			} else {
				$stock_client = $row['stock_client'];
				$stock_reference = $row['stock_reference'];
			}

			$tab = array(
				'nombre' => intval($row['nombre']),
				'stock_client' => $stock_client,
				'stock_reference' => $stock_reference
			);
		}

		return $tab;
	}

	public function updateStock($customer_id, $product_id, $quantite){
		$stock = $this->getStock($customer_id, $product_id);
		
		$nombre = $stock['nombre'];
		$stock_client = $stock['stock_client'];
		$stock_reference = $stock['stock_reference'];
		
		if($nombre == 0){
			$sql = "INSERT INTO " . DB_PREFIX . "customer_stock SET customer_id = $customer_id, product_id = $product_id, stock_client = $quantite, stock_reference = $quantite";
			// var_dump($sql);
			$query = $this->db->query($sql);
		} else {
			$stock_client = $stock_client + $quantite;
			
			if($stock_client > $stock_reference){
				$stock_reference = $stock_client;
			}
		
			$sql = "UPDATE " . DB_PREFIX . "customer_stock SET stock_client = $stock_client, stock_reference = $stock_reference WHERE customer_id = $customer_id AND product_id = $product_id";
			// var_dump($sql);
			$query = $this->db->query($sql);
		}
	}

	public function updateReference($customer_id, $product_id, $stock_reference){
		$sql = "UPDATE " . DB_PREFIX . "customer_stock SET stock_reference = $stock_reference WHERE customer_id = $customer_id AND product_id = $product_id";
		// var_dump($sql);
		$query = $this->db->query($sql);
	}

	public function updateStockClient($customer_id, $product_id, $stock_client){	
		$sql = "UPDATE " . DB_PREFIX . "customer_stock SET stock_client = $stock_client WHERE customer_id = $customer_id AND product_id = $product_id";
		// var_dump($sql);
		$query = $this->db->query($sql);
	}

	public function migration(){
		$sql = "SELECT o.order_id, o.customer_id, p.product_id, MAX(p.quantity) as quantite
				FROM migan_order o, migan_order_product p
				WHERE o.order_id = p.order_id
				AND customer_id IN( SELECT customer_id
									FROM migan_customer )
				GROUP BY o.customer_id, p.product_id";
		$query = $this->db->query($sql);

		foreach ($query->rows as $line) {
			$stock = $this->getStock($line['customer_id'], $line['product_id']);
			$nb = $stock['nombre'];

			if($nb == 0){
				$sql = "INSERT INTO " . DB_PREFIX . "customer_stock SET customer_id = ".$line['customer_id'].", product_id = ".$line['product_id'].", stock_client = ".$line['quantite'].", stock_reference = ".$line['quantite'];
				// var_dump($sql);
				$requete = $this->db->query($sql);
			}
		}
	}
}