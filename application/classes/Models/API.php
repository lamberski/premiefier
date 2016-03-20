<?php

namespace Premiefier\Models;

class API
{
    public static function getMoviesByTitle($title)
    {
        $apiKey = $application['config']['API_KEY'];
        $query  = http_build_query(['apikey' => $apiKey, 'q' => $title, 'page_limit' => 10]);
        $url    = 'http://api.rottentomatoes.com/api/public/v1.0/movies.json?';
        $json   = file_get_contents($url . $query);
        $data   = json_decode($json, true);
        $movies = array_map('Premiefier\Models\API::examineReleaseDate', $data['movies']);

        return self::sortByReleaseDate($movies);
    }

    public static function getMovieByID($id)
    {
        $apiKey = $application['config']['API_KEY'];
        $query  = http_build_query(['apikey' => $apiKey]);
        $url    = 'http://api.rottentomatoes.com/api/public/v1.0/movies/' . $id . '.json?';
        $json   = file_get_contents($url . $query);
        $data   = json_decode($json, true);

        return $data;
    }

    protected static function examineReleaseDate($movie)
    {
        if (isset($movie['release_dates']['theater'])) {
            $dateTimestamp         = strtotime($movie['release_dates']['theater']);
            $movie['released']     = $dateTimestamp < time();
            $movie['subscribable'] = !$movie['released'];
        } else {
            $movie['released'] = $movie['subscribable'] = false;
        }

        return $movie;
    }

    protected static function sortByReleaseDate($movies)
    {
        usort($movies, function ($a, $b) {
            $current  = isset($a['release_dates']['theater']) ? $a['release_dates']['theater'] : 0;
            $previous = isset($b['release_dates']['theater']) ? $b['release_dates']['theater'] : 0;

            return $current < $previous ? 1 : -1;
        });

        return $movies;
    }
}
