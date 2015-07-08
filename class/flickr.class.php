<?php
class FlickrPhotoSearch {
	private $_apikey;

	public function __construct($api_key = null) {
		$this->_apikey = $api_key;
	}

	private function _getContent($endPoint) {
		//curl library
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endPoint);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
		$getinfo = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        //check if the return is valid
		if ($getinfo == 200) {
			return $output;
		} else {
			return null;
		}
	}

	public function searchPhotos($query = null, $page = 1, $per_page = 5, $format = 'php_serial') {
		//params
		$params = array(
			'method' 	=> 'flickr.photos.search',
			'api_key' 	=> $this->_apikey,
			'text' 		=> $query,
			'page' 		=> $page,
			'per_page' 	=> $per_page,
			'format' 	=> $format
		);
		$result = $this->_getContent('https://api.flickr.com/services/rest/?'.http_build_query($params));

		//unserialize if format is php_serial
		if ($format == 'php_serial') {
			$result = unserialize($result);
		}

		return $result;
	}
}