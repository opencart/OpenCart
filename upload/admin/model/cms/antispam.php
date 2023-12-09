<?php
namespace Opencart\Admin\Model\Cms;
/**
 * Class Country
 *
 * @package Opencart\Admin\Model\Cms
 */
class Antispam extends \Opencart\System\Engine\Model {
	/**
	 * addAntispam
	 *
	 * @param array $data
	 *
	 * @return int
	 */
	public function addAntispam(array $data = []): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "antispam` SET `keyword` = '" . $this->db->escape((string)$data['keyword']) . "'");

		return $this->db->getLastId();
	}

	/**
	 * editAntispam
	 *
	 * @param array $data
	 *
	 * @return int
	 */
	public function editAntispam(int $antispam_id, array $data = []): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "antispam` SET `keyword` = '" . $this->db->escape((string)$data['keyword']) . "' WHERE `antispam_id` = '" . (int)$antispam_id . "'");
	}

	/**
	 * deleteAntispam
	 *
	 * @param int $antispam_id
	 *
	 * @return void
	 */
	public function deleteAntispam(int $antispam_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "antispam` WHERE `antispam_id` = '" . (int)$antispam_id . "'");
	}

	/**
	 * getAntispam
	 *
	 * @param int $antispam_id
	 *
	 * @return array
	 */
	public function getAntispam(int $antispam_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "antispam` WHERE `antispam_id` = '" . (int)$antispam_id . "'");

		return $query->row;
	}

	/**
	 * getAntispams
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function getAntispams(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "antispam`";

		$implode = [];

		if (!empty($data['filter_keyword'])) {
			$implode[] = "LCASE(`keyword`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_keyword'])) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = ['keyword'];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `keyword`";
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

	/**
	 * getTotalAntispams
	 *
	 * @param array $data
	 *
	 * @return int
	 */
	public function getTotalAntispams(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "antispam`";

		$implode = [];

		if (!empty($data['filter_keyword'])) {
			$implode[] = "LCASE(`keyword`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_keyword'])) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}
}
