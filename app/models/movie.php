<?php

namespace Premiefier\Models;

class Movie {
  public $title, $plot, $genre, $poster, $releasedAt;

  public static function findOrFail($title) {
    if (!$title) {
      throw new \Exception('Enter movie title.');
    }

    // Get movie data from IMDB API
    $url = 'http://www.omdbapi.com/?'.http_build_query(['t' => $title]);
    $json = file_get_contents($url);
    $data = json_decode($json);
    $data = self::parseOMDBData($data);

    if (!empty($data->Error)) {
      throw new \Exception($data->Error);
    }

    // Assign movie info to the object
    $movie = new Movie();
    $movie->title = $data->Title;
    $movie->plot = $data->Plot;
    $movie->genre = $data->Genre;
    $movie->poster = $data->Poster;
    $movie->releasedAt = $data->Released;

    // Detect any premiere-related errors
    if (!$movie->releasedAt) {
      throw new \Exception('Release date of this movie is not available.');
    } elseif ($movie->releasedAt && $movie->releasedAt < time()) {
      throw new \Exception('This movie was already released.');
    }

    return $movie;
  }

  protected static function parseOMDBData($data) {
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
