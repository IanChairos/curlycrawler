<?php
namespace CurlyCrawler;
/**
 * UserFoundDO
 *
 * @author Langosh
 */
class UserFoundDO {
	
	private $id;
	private $badoo_id;
	private $image;
	private $name;
	private $url;
	private $date;

	public function __set($name, $value){
		// not
	}

	
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getBadoo_id() {
		return $this->badoo_id;
	}

	public function setBadoo_id($badoo_id) {
		$this->badoo_id = $badoo_id;
	}

	public function getImage() {
		return $this->image;
	}

	public function setImage($image) {
		$this->image = $image;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getUrl() {
		return $this->url;
	}

	public function setUrl($url) {
		$this->url = $url;
	}

	public function getDate() {
		return $this->date;
	}

	public function setDate($date) {
		$this->date = $date;
	}

}
