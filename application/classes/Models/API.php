<?php

namespace Premiefier\Models;

use Silex\Application;

class API
{
    public static function getMoviesByTitle(Application $application, $title)
    {
        $apiKey = $application['config.api_key'];
        $query  = http_build_query(['apikey' => $apiKey, 'q' => $title, 'page_limit' => 10]);
        $url    = 'http://api.rottentomatoes.com/api/public/v1.0/movies.json?';
        $json   = file_get_contents($url . $query);
        $data   = json_decode($json, true);
        $movies = array_map('Premiefier\Models\API::examineReleaseDate', $data['movies']);

        return self::sortByReleaseDate($movies);
    }

    public static function getMovieByID(Application $application, $movieId)
    {
        $apiKey = $application['config.api_key'];
        $query  = http_build_query(['apikey' => $apiKey]);
        $url    = 'http://api.rottentomatoes.com/api/public/v1.0/movies/' . $movieId . '.json?';
        $json   = file_get_contents($url . $query);
        $data   = json_decode($json, true);

        return $data;
    }

    protected static function examineReleaseDate($movie)
    {
        $movie['released'] = $movie['subscribable'] = false;

        if (isset($movie['release_dates']['theater'])) {
            $dateTimestamp         = strtotime($movie['release_dates']['theater']);
            $movie['released']     = $dateTimestamp < time();
            $movie['subscribable'] = !$movie['released'];
        }

        return $movie;
    }

    protected static function sortByReleaseDate($movies)
    {
        usort($movies, function ($one, $two) {
            $current  = isset($one['release_dates']['theater']) ? $one['release_dates']['theater'] : 0;
            $previous = isset($two['release_dates']['theater']) ? $two['release_dates']['theater'] : 0;

            return $current < $previous ? 1 : -1;
        });

        return $movies;
    }
}
