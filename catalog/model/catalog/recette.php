<?php
class ModelCatalogRecette extends Model {
	public function updateViewed($recette_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "recette SET viewed = (viewed + 1) WHERE recette_id = '" . (int)$recette_id . "'");
	}

	public function getRecette($recette_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "recette r WHERE status = 1 AND r.recette_id = ".(int)$recette_id;
		$query = $this->db->query($sql);

		if ($query->num_rows) {
			return array(
				'recette_id'       => $query->row['recette_id'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				'meta_title'       => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'image'            => $query->row['image'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified']
			);
		} else {
			return false;
		}
	}

	public function getRecettes() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recette WHERE status = 1");

		$tab = false;

		foreach ($query->rows as $resultat) {
			$tab[] = array(
				'recette_id'       => $resultat['recette_id'],
				'name'             => $resultat['name'],
				'description'      => $resultat['description'],
				'meta_title'       => $resultat['meta_title'],
				'meta_description' => $resultat['meta_description'],
				'meta_keyword'     => $resultat['meta_keyword'],
				'image'            => $resultat['image'],
				'sort_order'       => $resultat['sort_order'],
				'status'           => $resultat['status'],
				'date_added'       => $resultat['date_added'],
				'date_modified'    => $resultat['date_modified']
			);
		}

		return $tab;
	}

	public function getRecetteImages($recette_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recette r, " . DB_PREFIX . "recette_image ri WHERE r.recette_id = ri.recette_id AND r.recette_id = ".(int)$recette_id);
		return $query->rows;
	}

	public function getTotalRecettes() {
		$query = $this->db->query("SELECT COUNT(*) as total_recette FROM " . DB_PREFIX . "recette WHERE status = 1");
		if ($query->num_rows) {
			return  $query->row['total_recette'];
		} else {
			return false;
		}
	}

	public function getProducts($recette_id){
		$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p, " . DB_PREFIX . "recette_product rp, " . DB_PREFIX . "recette r WHERE r.recette_id = $recette_id AND r.recette_id = rp.recette_id AND rp.product_id = p.product_id");
		return $query->rows;
	}
}