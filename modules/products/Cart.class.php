<?php
class Cart {
	var $sess;
	
	function Cart($sess) {
		$this->setSess($sess);
	}
	
	function setSess($sess) {
		$this->sess = $sess;
		if (!isset($_SESSION[$this->sess])) $_SESSION[$this->sess] = '';
	}
	
	function getData() {
		$newCart = array();
		if (isset($_SESSION[$this->sess]) && !empty($_SESSION[$this->sess])) {
			$cartProductArr = explode('|', $_SESSION[$this->sess]);
			foreach ($cartProductArr as $product) {
				$product = explode(';', $product);
				$newCart[$product[0]]['count'] = intval($product[1]);
				$newCart[$product[0]]['price'] = floatval($product[2]);
				$newCart[$product[0]]['name'] = base64_decode($product[3]);
			}
		}
		return $newCart;
	}

	function update($cart) {
		$temp = '';
		foreach ($cart as $itemId => $itemDetail) {
			if (($itemDetail['count']) < 1) continue;
			$temp .= "$itemId;{$itemDetail['count']};{$itemDetail['price']};".base64_encode($itemDetail['name']).'|';
		}
		$temp = substr($temp, 0, strlen($temp) - 1);
		$_SESSION[$this->sess] = $temp;
	}
	
	function delete($cart) {
		$temp = '';
		foreach ($cart as $itemId => $itemDetail) {
			if (($itemDetail['count']) < 1) continue;
			$temp .= "$itemId;{$itemDetail['count']};{$itemDetail['price']};".base64_encode($itemDetail['name']).'|';
		}
		$temp = substr($temp, 0, strlen($temp) - 1);
		$_SESSION[$this->sess] = $temp;
	}
	
	function add($id, $title, $price) {
		if (!empty($_SESSION[$this->sess])) $_SESSION[$this->sess] .= '|';
		$_SESSION[$this->sess] .= "$id;1;$price;".base64_encode($title);
	}
	
	function reset() {
		$_SESSION[$this->sess] = '';
	}
}
?>