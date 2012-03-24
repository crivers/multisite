<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

class RouteHelper {
	
	/*
		Takes a page object and figures out what URL should be used to
		access that page.
	*/
	public static function getUrl($path) {
		$hostname = $this->getHost();
		$pattern = '/(\/sites\/'.$hostname.')(.*)/';
		$match = preg_match($pattern, $path, $matches);
		
		if ($hostname != MAIN_URL && $match) {
			$url = '';
			foreach ($matches as $m) {
				if (!preg_match($pattern, $m)) {
					$url .= $m;
				}
			}
		}
		else {
			$url = $path;
		}
		return $url;
	}
	
	public static function getHost() {
		return self::sanitizeUrl($_SERVER['HTTP_HOST']);
	}
	
	public static function sanitizeUrl($url) {
		return strtolower(str_replace('www.', '', $url));
	}
	
}