<?php
class ModelCatalogGoodie extends Model {
	public function addGoodie($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "goodie SET nom_lot='".$data['nom_lot']."', ref_lot='".$data['ref_lot']."', points='".$data['points']."', stock='".$data['stock']."', status='".$data['status']."', id_couleur='".$data['id_couleur']."', id_type='".$data['id_type']."', id_taille='".$data['id_taille']."', id_sexe='".$data['id_sexe']."' ");

		$goodie_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "goodie SET image = '" . $this->db->escape($data['image']) . "' WHERE goodie_id = '" . (int)$goodie_id . "'");
		}

		$this->cache->delete('goodie');

		return $goodie_id;
	}

	public function editGoodie($goodie_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "goodie SET  nom_lot='".$data['nom_lot']."', ref_lot='".$data['ref_lot']."', points='".$data['points']."', stock='".$data['stock']."', status='".$data['status']."', id_couleur='".$data['id_couleur']."', id_type='".$data['id_type']."', id_taille='".$data['id_taille']."', id_sexe='".$data['id_sexe']."' WHERE goodie_id = '" . (int)$goodie_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "goodie SET image = '" . $this->db->escape($data['image']) . "' WHERE goodie_id = '" . (int)$goodie_id . "'");
		}
		
		$this->cache->delete('goodie');
	}

	public function copyGoodie($goodie_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "goodie p WHERE p.goodie_id = '" . (int)$goodie_id . "'");

		if ($query->num_rows) {
			$data = $query->row;

			$this->addGoodie($data);
		}
	}

	public function deleteGoodie($goodie_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "goodie WHERE goodie_id = '" . (int)$goodie_id . "'");

		$this->cache->delete('goodie');
	}

	public function getGoodie($goodie_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "goodie p WHERE p.goodie_id = '" . (int)$goodie_id . "' ");

		return $query->row;
	}

	public function getGoodies($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "goodie p WHERE 1=1 ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND p.nom_lot LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}
		
		if (isset($data['filter_taille']) && $data['filter_taille'] !== '') {
			$sql .= " and p.id_taille = '" . (int)$data['filter_taille'] . "'";
		}
		if (isset($data['filter_sexe']) && $data['filter_sexe'] !== '') {
			$sql .= " and p.id_sexe = '" . (int)$data['filter_sexe'] . "'";
		}
		if (isset($data['filter_type']) && $data['filter_type'] !== '') {
			$sql .= " and p.id_type = '" . (int)$data['filter_type'] . "'";
		}
		if (isset($data['filter_couleur']) && $data['filter_couleur'] !== '') {
			$sql .= " and p.id_couleur = '" . (int)$data['filter_couleur'] . "'";
		}

		$sql .= " GROUP BY p.goodie_id";

		$sort_data = array(
			'p.nom_lot',
			'p.status'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY p.nom_lot";
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


	public function getTotalGoodies($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.goodie_id) AS total FROM " . DB_PREFIX . "goodie p WHERE 1=1 ";


		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " and p.status = '" . (int)$data['filter_status'] . "'";
		}
		
		if (isset($data['filter_taille']) && $data['filter_taille'] !== '') {
			$sql .= " and p.id_taille = '" . (int)$data['filter_taille'] . "'";
		}
		if (isset($data['filter_sexe']) && $data['filter_sexe'] !== '') {
			$sql .= " and p.id_sexe = '" . (int)$data['filter_sexe'] . "'";
		}
		if (isset($data['filter_type']) && $data['filter_type'] !== '') {
			$sql .= " and p.id_type = '" . (int)$data['filter_type'] . "'";
		}
		if (isset($data['filter_couleur']) && $data['filter_couleur'] !== '') {
			$sql .= " and p.id_couleur = '" . (int)$data['filter_couleur'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getGoodieTaille($goodie_id) {
		$query = $this->db->query("SELECT nom_taille FROM " . DB_PREFIX . "goodie_taille JOIN " . DB_PREFIX . "goodie ON " . DB_PREFIX . "goodie.id_taille=" . DB_PREFIX . "goodie_taille.idtaille WHERE goodie_id = '" . (int)$goodie_id . "'");

		return $query->row['nom_taille'];
	}
	
	public function getGoodieSexe($goodie_id) {
		$query = $this->db->query("SELECT nom_sexe FROM " . DB_PREFIX . "goodie_sexe JOIN " . DB_PREFIX . "goodie ON " . DB_PREFIX . "goodie.id_sexe=" . DB_PREFIX . "goodie_sexe.idsexe WHERE goodie_id = '" . (int)$goodie_id . "'");

		return $query->row['nom_sexe'];
	}
	public function getGoodieType($goodie_id) {
		$query = $this->db->query("SELECT nom_type FROM " . DB_PREFIX . "goodie_type JOIN " . DB_PREFIX . "goodie ON " . DB_PREFIX . "goodie.id_type=" . DB_PREFIX . "goodie_type.idtype_lot WHERE goodie_id = '" . (int)$goodie_id . "'");

		return $query->row['nom_type'];
	}
	public function getGoodieCouleur($goodie_id) {
		$query = $this->db->query("SELECT nom_couleur FROM " . DB_PREFIX . "goodie_couleur JOIN " . DB_PREFIX . "goodie ON " . DB_PREFIX . "goodie.id_couleur=" . DB_PREFIX . "goodie_couleur.idcouleur WHERE goodie_id = '" . (int)$goodie_id . "'");

		return $query->row['nom_couleur'];
	}
	
	public function getGoodieTailles() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "goodie_taille ");

		return $query->rows;
	}
	
	public function getGoodieSexes() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "goodie_sexe ");

		return $query->rows;
	}
	public function getGoodieTypes() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "goodie_type ");

		return $query->rows;
	}
	public function getGoodieCouleurs() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "goodie_couleur ");

		return $query->rows;
	}
	public function getTotalGoodiesByTaxClassId($tax_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "goodie WHERE tax_class_id = '" . (int)$tax_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalGoodiesByStockStatusId($stock_status_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "goodie WHERE stock_status_id = '" . (int)$stock_status_id . "'");

		return $query->row['total'];
	}

	public function getTotalGoodiesByWeightClassId($weight_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "goodie WHERE weight_class_id = '" . (int)$weight_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalGoodiesByLengthClassId($length_class_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "goodie WHERE length_class_id = '" . (int)$length_class_id . "'");

		return $query->row['total'];
	}

	public function getTotalGoodiesByDownloadId($download_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "goodie_to_download WHERE download_id = '" . (int)$download_id . "'");

		return $query->row['total'];
	}

	public function getTotalGoodiesByManufacturerId($manufacturer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "goodie WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row['total'];
	}

	public function getTotalGoodiesByAttributeId($attribute_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "goodie_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");

		return $query->row['total'];
	}

	public function getTotalGoodiesByOptionId($option_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "goodie_option WHERE option_id = '" . (int)$option_id . "'");

		return $query->row['total'];
	}

	public function getTotalGoodiesByProfileId($recurring_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "goodie_recurring WHERE recurring_id = '" . (int)$recurring_id . "'");

		return $query->row['total'];
	}

	public function getTotalGoodiesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "goodie_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
}
