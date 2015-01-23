<?php

namespace Premiefier\Models;

class Movie {
  public $title, $plot, $genre, $poster, $releasedAt;

  public static function findOrFail($title) {
    // Get movie data from IMDB API
    $url = 'http://www.omdbapi.com/?'.http_build_query(['t' => $title]);
    $json = file_get_contents($url);
    $data = json_decode($json);
    $data = self::parseOMDbData($data);

    // Detect any errors
    if (!empty($data->Error)) {
      throw new \Exception($data->Error);
    } elseif (!$data->Released) {
      throw new \Exception('Release date of this movie is not available.');
    } elseif ($data->Released && $data->Released < time()) {
      throw new \Exception('This movie was already released.');
    }

    // Assign movie info to the object
    $movie = new Movie();
    $movie->title = $data->Title;
    $movie->plot = $data->Plot;
    $movie->genre = $data->Genre;
    $movie->poster = $data->Poster;
    $movie->releasedAt = $data->Released;

    return $movie;
  }

  protected static function parseOMDbData($data) {
    // Set value as empty if 'N/A'
    foreach($data as &$value) {
      if ($value == 'N/A') $value = '';
    }

    // Set date from text
    if (!empty($data->Released)) {
      $data->Released = strtotime($data->Released);
    }

    return $data;
  }
}
