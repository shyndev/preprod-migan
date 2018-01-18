<?php
class ModelCheckoutGoodies extends Model {
	public function getGoodies($total) {
		$total = (int)$total;
		// migan :::::: " . DB_PREFIX . "
		$query = $this->db->query("	SELECT goodie_id, nom_lot, stock, image, nom_couleur, nom_taille, nom_sexe, id_type, id_sexe
									FROM " . DB_PREFIX . "goodie g, " . DB_PREFIX . "goodie_couleur gc, " . DB_PREFIX . "goodie_taille gt, " . DB_PREFIX . "goodie_sexe gs
									WHERE CONVERT(points, SIGNED INTEGER) < $total
									AND g.id_couleur = gc.idcouleur
									AND g.id_taille = gt.idtaille
									AND g.id_sexe = gs.idsexe
									GROUP BY id_sexe, id_type
								");
		return $query->rows;
	}

	public function getTaille($id_type, $id_sexe){

		$sql = "SELECT gt.idtaille, UPPER(nom_taille) as nom_taille FROM " . DB_PREFIX . "goodie g, " . DB_PREFIX . "goodie_taille gt
				WHERE g.id_type = $id_type
				AND g.id_sexe = $id_sexe
				AND g.id_taille = gt.idtaille
				GROUP BY g.id_taille";

		$query = $this->db->query($sql);

		$response = array();
		foreach($query->rows as $row) {
			array_push($response, $row);
		}

		return $response;
	}

	public function getCouleur($id_type, $id_sexe, $id_taille){

		$sql = "SELECT gc.idcouleur, nom_couleur FROM " . DB_PREFIX . "goodie g, " . DB_PREFIX . "goodie_couleur gc
				WHERE g.id_type = $id_type
				AND g.id_sexe = $id_sexe
				AND g.id_taille = $id_taille
				AND g.id_couleur = gc.idcouleur";
				
		$query = $this->db->query($sql);

		$response = array();
		foreach($query->rows as $row) {
			array_push($response, $row);
		}

		return $response;
	}

	public function distributeGoodie($id_type, $id_sexe, $id_taille, $id_couleur, $id_order){
		$sql = "SELECT goodie_id FROM " . DB_PREFIX . "goodie WHERE id_type = $id_type AND id_sexe = $id_sexe AND id_taille = $id_taille AND id_couleur = $id_couleur";
		$query = $this->db->query($sql);
		$goodie_id = $query->rows[0]['goodie_id'];

		$sql = "INSERT INTO migan_order_goodie SET goodie_id = $goodie_id, order_id = $id_order";
		$query = $this->db->query($sql);

		return $query;
	}

	public function getGoodieOrder($order_id){
		$sql = "SELECT g.goodie_id, nom_lot AS nom, UPPER(nom_taille) AS taille FROM " . DB_PREFIX . "order_goodie og, " . DB_PREFIX . "goodie g, " . DB_PREFIX . "goodie_taille gt WHERE order_id = $order_id AND og.goodie_id = g.goodie_id AND g.id_taille = gt.idtaille";
		$query = $this->db->query($sql);

		return $query->row;
	}
}