<?php
namespace Opencart\Application\Model\Upgrade;
class Upgrade1005 extends \Opencart\System\Engine\Model {
	public function upgrade() {
		// customer
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer` CHANGE `token` `token` text NOT NULL");

		// custom_field
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "custom_field' AND COLUMN_NAME = 'validation'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "custom_field` ADD `validation` varchar(255) NOT NULL AFTER `value`");
		}

		// product
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "product` CHANGE `isbn` `isbn` VARCHAR(17) NOT NULL");

		// product
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product' AND COLUMN_NAME = 'viewed'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `viewed` int(5) NOT NULL AFTER `status`");
		}

		// product_description
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product_description' AND COLUMN_NAME = 'meta_title'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_description` ADD `meta_title` varchar(255) NOT NULL AFTER `description`");
		}

		// product_recurring
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product_recurring' AND COLUMN_NAME = 'recurring_id'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_recurring` ADD `recurring_id` int(11) NOT NULL AFTER `product_id`");
		}

		// product_recurring
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product_recurring' AND COLUMN_NAME = 'customer_group_id'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_recurring` ADD `customer_group_id` int(11) NOT NULL AFTER `recurring_id`");
		}

		// order_recurring_transaction
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_recurring_transaction' AND COLUMN_NAME = 'created'");

		if ($query->num_rows) {
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_recurring_transaction' AND COLUMN_NAME = 'date_added'");

			if ($query->num_rows) {
				$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring_transaction` SET `date_added` = `created` WHERE `date_added` IS NULL or `date_added` = ''");
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_recurring_transaction` DROP `created`");
			} else {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_recurring_transaction` CHANGE `created` `date_added` datetime NOT NULL AFTER `amount`");
			}
		}

		// order_recurring_transaction
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_recurring_transaction' AND COLUMN_NAME = 'reference'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_recurring_transaction` ADD `reference` varchar(255) NOT NULL AFTER `order_recurring_id`");
		}

		// order_recurring_transaction
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_recurring_transaction` CHANGE `type` `type` varchar(255) NOT NULL AFTER `reference`");

		// user
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "user' AND COLUMN_NAME = 'image'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "user` ADD `image` varchar(255) NOT NULL AFTER `email`");
		}

		// Set Product Meta Title default to product name if empty
		$this->db->query("UPDATE `" . DB_PREFIX . "product_description` SET `meta_title` = `name` WHERE meta_title = ''");
		$this->db->query("UPDATE `" . DB_PREFIX . "category_description` SET `meta_title` = `name` WHERE meta_title = ''");
		$this->db->query("UPDATE `" . DB_PREFIX . "information_description` SET `meta_title` = `title` WHERE meta_title = ''");

		// setting
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_complete_status'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = 'config_complete_status', `value` = '[\"5\"]', `code` = 'config', `serialized` = '1', `store_id` = '0'");
		}

		// setting
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_processing_status'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = 'config_processing_status', `value` = '[\"2\"]', `code` = 'config', `serialized` = '1', `store_id` = '0'");
		}

		// setting
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_fraud_status_id'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = 'config_fraud_status_id', `value` = '8', `code` = 'config', `serialized` = '0', `store_id` = '0'");
		}

		// setting
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_api_id'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = 'config_api_id', `value` = '1', `code` = 'config', `serialized` = '0', `store_id` = '0'");
		}

		// setting
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_product_description_length'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = 'config_product_description_length', `value` = '100', `code` = 'config', `serialized` = '0', `store_id` = '0'");
		}

		// setting
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_pagination'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = 'config_pagination', `value` = '20', `code` = 'config', `serialized` = '0', `store_id` = '0'");
		}

		// setting
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_encryption'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = 'config_encryption', `value` = '" . hash('sha512', mt_rand()) . "', `code` = 'config', `serialized` = '0', `store_id` = '0'");
		} elseif (strlen($query->row['value']) < 28) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_encryption'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = 'config_encryption', `value` = '" . hash('sha512', mt_rand()) . "', `code` = 'config', `serialized` = '0', `store_id` = '0'");
		}

		// force some settings to prevent errors
		$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = 'default' WHERE `key` = 'config_template'");
		$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '1' WHERE `key` = 'config_error_display'");
		$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '1' WHERE `key` = 'config_error_log'");
		$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '0' WHERE `key` = 'config_compression'");
	}
}
