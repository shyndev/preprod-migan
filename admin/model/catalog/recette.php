<?php
class ModelCatalogRecette extends Model {
	public function addRecette($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "recette SET name='".$data['name']."', difficulte='".$data['difficulte']."', preparation='".$data['preparation']."', cuisson='".$data['cuisson']."', nb_personne='".$data['nb_personne']."', enavant='".$data['enavant']."', description='".$data['description']."', meta_description='".$data['meta_description']."', meta_title='".$data['meta_title']."', meta_keyword='".$data['meta_keyword']."', status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "'");

		$recette_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "recette SET image = '" . $this->db->escape($data['image']) . "' WHERE recette_id = '" . (int)$recette_id . "'");
		}


		if (isset($data['recette_image'])) {
			foreach ($data['recette_image'] as $recette_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "recette_image SET recette_id = '" . (int)$recette_id . "', image = '" . $this->db->escape($recette_image['image']) . "', sort_order = '" . (int)$recette_image['sort_order'] . "'");
			}
		}

		
		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "recette_product WHERE recette_id = '" . (int)$recette_id . "' AND product_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "recette_product SET recette_id = '" . (int)$recette_id . "', product_id = '" . (int)$related_id . "'");
			}
		}

		$this->cache->delete('recette');

		return $recette_id;
	}

	public function editRecette($recette_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "recette SET name='".$data['name']."', difficulte='".$data['difficulte']."', preparation='".$data['preparation']."', cuisson='".$data['cuisson']."', nb_personne='".$data['nb_personne']."', enavant='".$data['enavant']."', description='".$data['description']."', meta_description='".$data['meta_description']."', meta_title='".$data['meta_title']."', meta_keyword='".$data['meta_keyword']."', status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "' WHERE recette_id = '" . (int)$recette_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "recette SET image = '" . $this->db->escape($data['image']) . "' WHERE recette_id = '" . (int)$recette_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "recette_image WHERE recette_id = '" . (int)$recette_id . "'");

		if (isset($data['recette_image'])) {
			foreach ($data['recette_image'] as $recette_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "recette_image SET recette_id = '" . (int)$recette_id . "', image = '" . $this->db->escape($recette_image['image']) . "', sort_order = '" . (int)$recette_image['sort_order'] . "'");
			}
		}
		
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "recette_product WHERE recette_id = '" . (int)$recette_id . "'");

		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "recette_product WHERE recette_id = '" . (int)$recette_id . "' AND product_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "recette_product SET recette_id = '" . (int)$recette_id . "', product_id = '" . (int)$related_id . "'");
			}
		}

		$this->cache->delete('recette');
	}

	public function copyRecette($recette_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "recette p WHERE p.recette_id = '" . (int)$recette_id . "'");

		if ($query->num_rows) {
			$data = $query->row;

			$data['recette_image'] = $this->getRecetteImages($recette_id);

			$this->addRecette($data);
		}
	}

	public function deleteRecette($recette_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "recette WHERE recette_id = '" . (int)$recette_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "recette_image WHERE recette_id = '" . (int)$recette_id . "'");

		$this->cache->delete('recette');
	}

	public function getRecette($recette_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "recette p WHERE p.recette_id = '" . (int)$recette_id . "' ");

		return $query->row;
	}

	public function getRecettes($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "recette p WHERE 1=1 ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND p.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$sql .= " GROUP BY p.recette_id";

		$sort_data = array(
			'p.name',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY p.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getRecettesByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recette p LEFT JOIN " . DB_PREFIX . "recette_description pd ON (p.recette_id = pd.recette_id) LEFT JOIN " . DB_PREFIX . "recette_to_category p2c ON (p.recette_id = p2c.recette_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "' ORDER BY pd.name ASC");

		return $query->rows;
	}

	public function getRecetteDescriptions($recette_id) {
		$recette_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recette_description WHERE recette_id = '" . (int)$recette_id . "'");

		foreach ($query->rows as $result) {
			$recette_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'tag'              => $result['tag']
			);
		}

		return $recette_description_data;
	}

	public function getRecetteCategories($recette_id) {
		$recette_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recette_to_category WHERE recette_id = '" . (int)$recette_id . "'");

		foreach ($query->rows as $result) {
			$recette_category_data[] = $result['category_id'];
		}

		return $recette_category_data;
	}

	public function getRecetteFilters($recette_id) {
		$recette_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recette_filter WHERE recette_id = '" . (int)$recette_id . "'");

		foreach ($query->rows as $result) {
			$recette_filter_data[] = $result['filter_id'];
		}

		return $recette_filter_data;
	}

	public function getRecetteAttributes($recette_id) {
		$recette_attribute_data = array();

		$recette_attribute_query = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "recette_attribute WHERE recette_id = '" . (int)$recette_id . "' GROUP BY attribute_id");

		foreach ($recette_attribute_query->rows as $recette_attribute) {
			$recette_attribute_description_data = array();

			$recette_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recette_attribute WHERE recette_id = '" . (int)$recette_id . "' AND attribute_id = '" . (int)$recette_attribute['attribute_id'] . "'");

			foreach ($recette_attribute_description_query->rows as $recette_attribute_description) {
				$recette_attribute_description_data[$recette_attribute_description['language_id']] = array('text' => $recette_attribute_description['text']);
			}

			$recette_attribute_data[] = array(
				'attribute_id'                  => $recette_attribute['attribute_id'],
				'recette_attribute_description' => $recette_attribute_description_data
			);
		}

		return $recette_attribute_data;
	}

	public function getRecetteOptions($recette_id) {
		$recette_option_data = array();

		$recette_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "recette_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.recette_id = '" . (int)$recette_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		foreach ($recette_option_query->rows as $recette_option) {
			$recette_option_value_data = array();

			$recette_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recette_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON(pov.option_value_id = ov.option_value_id) WHERE pov.recette_option_id = '" . (int)$recette_option['recette_option_id'] . "' ORDER BY ov.sort_order ASC");

			foreach ($recette_option_value_query->rows as $recette_option_value) {
				$recette_option_value_data[] = array(
					'recette_option_value_id' => $recette_option_value['recette_option_value_id'],
					'option_value_id'         => $recette_option_value['option_value_id'],
					'quantity'                => $recette_option_value['quantity'],
					'subtract'                => $recette_option_value['subtract'],
					'price'                   => $recette_option_value['price'],
					'price_prefix'            => $recette_option_value['price_prefix'],
					'points'                  => $recette_option_value['points'],
					'points_prefix'           => $recette_option_value['points_prefix'],
					'weight'                  => $recette_option_value['weight'],
					'weight_prefix'           => $recette_option_value['weight_prefix']
				);
			}

			$recette_option_data[] = array(
				'recette_option_id'    => $recette_option['recette_option_id'],
				'recette_option_value' => $recette_option_value_data,
				'option_id'            => $recette_option['option_id'],
				'name'                 => $recette_option['name'],
				'type'                 => $recette_option['type'],
				'value'                => $recette_option['value'],
				'required'             => $recette_option['required']
			);
		}

		return $recette_option_data;
	}

	public function getRecetteOptionValue($recette_id, $recette_option_value_id) {
		$query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "recette_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.recette_id = '" . (int)$recette_id . "' AND pov.recette_option_value_id = '" . (int)$recette_option_value_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getRecetteImages($recette_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recette_image WHERE recette_id = '" . (int)$recette_id . "' ORDER BY sort_order ASC");

		return $query->rows;
	}

	public function getRecetteDiscounts($recette_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recette_discount WHERE recette_id = '" . (int)$recette_id . "' ORDER BY quantity, priority, price");

		return $query->rows;
	}

	public function getRecetteSpecials($recette_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recette_special WHERE recette_id = '" . (int)$recette_id . "' ORDER BY priority, price");

		return $query->rows;
	}

	public function getRecetteRewards($recette_id) {
		$recette_reward_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recette_reward WHERE recette_id = '" . (int)$recette_id . "'");

		foreach ($query->rows as $result) {
			$recette_reward_data[$result['customer_group_id']] = array('points' => $result['points']);
		}

		return $recette_reward_data;
	}

	public function getRecetteDownloads($recette_id) {
		$recette_download_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recette_to_download WHERE recette_id = '" . (int)$recette_id . "'");

		foreach ($query->rows as $result) {
			$recette_download_data[] = $result['download_id'];
		}

		return $recette_download_data;
	}

	public function getRecetteStores($recette_id) {
		$recette_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recette_to_store WHERE recette_id = '" . (int)$recette_id . "'");

		foreach ($query->rows as $result) {
			$recette_store_data[] = $result['store_id'];
		}

		return $recette_store_data;
	}
	
	public function getRecetteSeoUrls($recette_id) {
		$recette_seo_url_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'recette_id=" . (int)$recette_id . "'");

		foreach ($query->rows as $result) {
			$recette_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $recette_seo_url_data;
	}
	
	public function getRecetteLayouts($recette_id) {
		$recette_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recette_to_layout WHERE recette_id = '" . (int)$recette_id . "'");

		foreach ($query->rows as $result) {
			$recette_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $recette_layout_data;
	}

	public function getProductRelated($recette_id) {
		$recette_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recette_product WHERE recette_id = '" . (int)$recette_id . "'");
		
		foreach ($query->rows as $result) {
			$recette_related_data[] = $result['product_id'];
		}

		return $recette_related_data;
	}

	public function getRecurrings($recette_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "recette_recurring` WHERE recette_id = '" . (int)$recette_id . "'");

		return $query->rows;
	}

	public function getTotalRecettes($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.recette_id) AS total FROM " . DB_PREFIX . "recette p ";


		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " WHERE p.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalRecettesByTaxClassId($tax_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "recette WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalRecettesByStockStatusId($stock_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "recette WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		return $query->row['total'];
	}

	public function getTotalRecettesByWeightClassId($weight_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "recette WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalRecettesByLengthClassId($length_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "recette WHERE length_class_id = '" . (int)$length_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalRecettesByDownloadId($download_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "recette_to_download WHERE download_id = '" . (int)$download_id . "'");

		return $query->row['total'];
	}

	public function getTotalRecettesByManufacturerId($manufacturer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "recette WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row['total'];
	}

	public function getTotalRecettesByAttributeId($attribute_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "recette_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");

		return $query->row['total'];
	}

	public function getTotalRecettesByOptionId($option_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "recette_option WHERE option_id = '" . (int)$option_id . "'");

		return $query->row['total'];
	}

	public function getTotalRecettesByProfileId($recurring_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "recette_recurring WHERE recurring_id = '" . (int)$recurring_id . "'");

		return $query->row['total'];
	}

	public function getTotalRecettesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "recette_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
}
