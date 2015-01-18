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

    $omdb = new Movie();

    // Assign movie info to the object
    $omdb->title = $data->Title;
    $omdb->plot = $data->Plot;
    $omdb->genre = $data->Genre;
    $omdb->poster = $data->Poster;
    $omdb->releasedAt = $data->Released;

    // Detect any errors
    if (!$omdb->title) {
      throw new \Exception('Movie was not found.');
    } elseif (!$omdb->releasedAt) {
      throw new \Exception('Release date of this movie is not available.');
    } elseif ($omdb->releasedAt && $omdb->releasedAt < time()) {
      throw new \Exception('This movie was already released.');
    }

    return $omdb;
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
