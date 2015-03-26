<?php

namespace Premiefier\Models;

class API {

  public static function getMoviesByTitle($title)
  {
    $query  = http_build_query(['apikey' => getenv('API_KEY'), 'q' => $title, 'page_limit' => 10]);
    $url    = 'http://api.rottentomatoes.com/api/public/v1.0/movies.json?';
    $json   = file_get_contents($url.$query);
    $data   = json_decode($json, true);
    $movies = array_map('Premiefier\Models\API::examineReleaseDate', $data['movies']);

    return self::sortByReleaseDate($movies);
  }

  public static function getMovieByID($id)
  {
    $query = http_build_query(['apikey' => getenv('API_KEY')]);
    $url   = 'http://api.rottentomatoes.com/api/public/v1.0/movies/'.$id.'.json?';
    $json  = file_get_contents($url.$query);
    $data  = json_decode($json, true);

    return $data;
  }

  protected static function examineReleaseDate($movie)
  {
    if (isset($movie['release_dates']['theater']))
    {
      $date_timestamp = strtotime($movie['release_dates']['theater']);
      $movie['already_released'] = $date_timestamp < time();
    }
    else
    {
      $movie['already_released'] = false;
    }

    return $movie;
  }

  protected static function sortByReleaseDate($movies)
  {
    usort($movies, function ($movie) {
      return ($movie['already_released'] || empty($movie['release_dates']['theater'])) ? -1 : 1;
    });

    return array_reverse($movies);
  }

}
