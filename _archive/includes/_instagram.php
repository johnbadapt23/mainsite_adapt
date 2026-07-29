<?php

// instagram feed
class Instagram {
    public static $result;
    public static $results;
    public static $display_size = 'thumbnail'; // low_resolution, thumbnail, standard_resolution
    public static $access_token = '';
    public static $count = 4;
    public static $tag = '';
    public static $filter = '';

    public static function fetch($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    function __construct($token=null,$count=null,$size=null,$tag=false,$filter=false){
        if(!empty($token)){
            self::$access_token = $token;
        }
        if(!empty($count)){
            self::$count = $count;
        }
        if(!empty($size)){
            self::$display_size = $size;
        }
        if(!empty($tag)){
            self::$tag = $tag;
        }
        if(!empty($filter)){
            self::$filter = $filter;
        }

        if(!empty($filter)){
            self::$result = self::fetch("https://filters.rocks/embed/" . self::$filter . ".json");

            self::$result = json_decode(self::$result);
            self::$results = self::$result->feed->approved_images;
        } else {
            if (self::$tag) {
                self::$result = self::fetch("https://api.instagram.com/v1/tags/" . self::$tag . "/media/recent?count=" . self::$count . "&access_token=" . self::$access_token);
            } else {
    	        self::$result = self::fetch("https://api.instagram.com/v1/users/self/media/recent?count=" . self::$count . "&access_token=" . self::$access_token);
            }

            self::$result = json_decode(self::$result);
            self::$results = self::$result->data;
        }

    }
}

?>
