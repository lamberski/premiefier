<?php

namespace Premiefier\Models;

class API {
  public static function getMoviesByTitle($title) {

    // Get movie data from Rotten Tomatoes API
    $query = http_build_query(['apikey' => getenv('API_KEY'), 'q' => $title, 'page_limit' => 10]);
    $url   = 'http://api.rottentomatoes.com/api/public/v1.0/movies.json?';
    $json  = file_get_contents($url.$query);
    $data  = json_decode($json);

    return $data;
  }
}
