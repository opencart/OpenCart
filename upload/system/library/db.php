<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* DB
*/
namespace Opencart\System\Library;
class DB {
	private $adaptor;
	private $cache;

	/**
	 * Constructor
	 *
	 * @param	string	$adaptor
	 * @param	string	$hostname
	 * @param	string	$username
     * @param	string	$password
	 * @param	string	$database
	 * @param	int		$port
	 *
 	*/
	public function __construct($adaptor, $hostname, $username, $password, $database, $port = '') {
		$class = 'Opencart\System\Library\DB\\' . $adaptor;

		if (class_exists($class)) {
			$this->adaptor = new $class($hostname, $username, $password, $database, $port);
		} else {
			throw new \Exception('Error: Could not load database adaptor ' . $adaptor . '!');
		}
	}

	/**
     * Query
     *
     * @param	string	$sql
	 * 
	 * @return	array
     */
	public function query($sql) {
		return $this->adaptor->query($sql);
	}

	/**
     * Escape
     *
     * @param	string	$value
	 * 
	 * @return	string
     */
	public function escape($value) {
		return $this->adaptor->escape($value);
	}

	/**
     * Count Affected
	 *
	 * Gets the total number of affected rows from the last query
	 *
	 * @return	int	returns the total number of affected rows.
     */
	public function countAffected() {
		return $this->adaptor->countAffected();
	}

	/**
     * Get Last ID
	 *
	 * Get the last ID gets the primary key that was returned after creating a row in a table.
	 *
	 * @return	int returns last ID
     */
	public function getLastId() {
		return $this->adaptor->getLastId();
	}
	
	/**
     * Is Connected
	 *
	 * Checks if a DB connection is active.
	 *
	 * @return	bool
     */	
	public function isConnected() {
		return $this->adaptor->isConnected();
	}

	/**
	 * Set Cache
	 *
	 * Injected Cache Engine Adapter to enable SQL Query cache
	 *
	 * @param \Opencart\System\Library\Cache $cache
	 */
	public function setCacheEngine(\Opencart\System\Library\Cache $cache){
		$this->cache = $cache;
	}

	/**
	 * QueryCached - should be used for SQL SELECT queries which may be cached
	 *
	 * @param string $cacheKey
	 * @param string $sql
	 * @param string $expireSeconds Expire time in seconds or default
	 *
	 * @return    object
	 */
	public function queryCached($cacheKey, $sql, $expireSeconds = NULL) {
		if (!$this->cache){
			return $this->adaptor->query($sql);
		}
		if (($cached = $this->cache->get($cacheKey))) {
			return (object)array('rows' => $cached);
		}
		$results = $this->adaptor->query($sql);
		$this->cache->set($cacheKey, $results->rows, $expireSeconds);

		return $results;
	}
}