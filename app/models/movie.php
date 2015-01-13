<?php

namespace Premiefier;

class Movie {
  public $title, $plot, $genre, $poster, $releasedAt;
  public $wasFound, $wasAlreadyReleased;

  public function __construct($title) {
    $this->fetch($title);
  }

  protected function fetch($title) {
    // Get movie data from IMDB API
    $url = 'http://www.omdbapi.com/?'.http_build_query(['t' => $title]);
    $json = file_get_contents($url);
    $data = json_decode($json);

    $data = $this->parseOMDBData($data);

    // Save movie info in the object
    $this->title = $data->Title;
    $this->plot = $data->Plot;
    $this->genre = $data->Genre;
    $this->poster = $data->Poster;
    $this->releasedAt = $data->Released;

    // Save flags values
    $this->wasFound = $this->title != '';
    $this->wasAlreadyReleased = $this->releasedAt && $this->releasedAt < time();
  }

  protected function parseOMDBData($data) {
    // Set value as empty if 'N/A'
    foreach($data as &$value) {
      if ($value == 'N/A') $value = '';
    }

    // Set date from text
    if ($data->Released) {
      $data->Released = strtotime($data->Released);
    }

    return $data;
  }
}
