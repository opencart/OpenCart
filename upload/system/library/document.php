<?php
/**
 * @package        OpenCart
 * @author        Daniel Kerr
 * @copyright    Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license        https://opensource.org/licenses/GPL-3.0
 * @link        https://www.opencart.com
 */

namespace Opencart\System\Library;
/**
 * Class Document
 */
class Document {
	/**
	 * @var string
	 */
	private string $seo = '';
	/**
	 * @var string
	 */
	private string $schema = '';
	/**
	 * @var array
	 */
	private array $links = [];
	/**
	 * @var array
	 */
	private array $styles = [];
	/**
	 * @var array
	 */
	private array $scripts = [];

	/**
	 *
	 *
	 * @param string $seo
	 */
	public function setSeo(string $seo): void {
		$this->seo = $seo;
	}

	/**
	 *
	 *
	 * @return    string
	 */
	public function getSeo(): string {
		return $this->seo;
	}

	/**
	 *
	 *
	 * @param string $schema
	 */
	public function setSchema(string $schema): void {
		$this->schema = $schema;
	}

	/**
	 *
	 *
	 * @return    string
	 */
	public function getSchema(): string {
		return $this->schema;
	}

	/**
	 *
	 *
	 * @param string $href
	 * @param string $rel
	 */
	public function addLink(string $href, string $rel): void {
		$this->links[$href] = [
			'href' => $href,
			'rel' => $rel
		];
	}

	/**
	 *
	 *
	 * @return    array
	 */
	public function getLinks(): array {
		return $this->links;
	}

	/**
	 *
	 *
	 * @param string $href
	 * @param string $rel
	 * @param string $media
	 */
	public function addStyle(string $href, $rel = 'stylesheet', $media = 'screen'): void {
		$this->styles[$href] = [
			'href' => $href,
			'rel' => $rel,
			'media' => $media
		];
	}

	/**
	 *
	 *
	 * @return    array
	 */
	public function getStyles(): array {
		return $this->styles;
	}

	/**
	 *
	 *
	 * @param string $href
	 * @param string $position
	 */
	public function addScript(string $href, $position = 'header'): void {
		$this->scripts[$position][$href] = ['href' => $href];
	}

	/**
	 *
	 *
	 * @param string $position
	 *
	 * @return    array
	 */
	public function getScripts($position = 'header'): array {
		if (isset($this->scripts[$position])) {
			return $this->scripts[$position];
		} else {
			return [];
		}
	}
}
