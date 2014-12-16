<?php

namespace Premiefier;

class Movie {
    public $title, $plot, $genre, $poster, $releasedAt, $wasFound;

    public function __construct($title) {
        $this->fetch($title);
    }

    public function isAlreadyReleased() {
        return $this->releasedAt < time();
    }

    protected function fetch($title) {

        // Get movie data from IMDB API
        $url = 'http://www.omdbapi.com/?'.http_build_query(['t' => $title]);
        $json = file_get_contents($url);
        $movie = json_decode($json);

        if (!empty($movie->Error)) {
            $this->wasFound = false;
            return;
        }

        // Save movie info in the object
        $this->title = $movie->Title;
        $this->plot = $movie->Plot;
        $this->genre = $movie->Genre;
        $this->poster = $movie->Poster;
        $this->releasedAt = strtotime($movie->Released);
        $this->wasFound = true;
    }
}
