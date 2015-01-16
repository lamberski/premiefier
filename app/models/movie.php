<?php

namespace Premiefier;

class Movie {
  public $title, $plot, $genre, $poster, $releasedAt;

  public function __construct($title) {
    $this->fetch($title);
  }

  protected function fetch($title) {
    if (!$title) {
      throw new \Exception('Enter movie title.');
    }

    // Get movie data from IMDB API
    $url = 'http://www.omdbapi.com/?'.http_build_query(['t' => $title]);
    $json = file_get_contents($url);
    $data = json_decode($json);
    $data = $this->parseOMDBData($data);

    // Assign movie info to the object
    $this->title = $data->Title;
    $this->plot = $data->Plot;
    $this->genre = $data->Genre;
    $this->poster = $data->Poster;
    $this->releasedAt = $data->Released;

    // Detect any errors
    if (!$this->title) {
      throw new \Exception('Movie was not found.');
    } elseif (!$movie->releasedAt) {
      throw new \Exception('Release date of this movie is not available.');
    } elseif ($this->releasedAt && $this->releasedAt < time()) {
      throw new \Exception('This movie was already released.');
    }
  }

  protected function parseOMDBData($data) {
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
