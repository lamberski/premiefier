<?php

namespace Premiefier\Controllers;

use Silex\Application;
use Premiefier\Models\API;
use Premiefier\Models\User;
use Premiefier\Models\Premiere;
use Premiefier\Models\Notification;

class Notifications
{
    public function index(Application $application)
    {
        $email = $application['request']->get('email');

        if (!trim($email)) {
            throw new \Exception('Enter your email address first.', 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Please enter valid email address.', 400);
        }

        $user = User::whereEmail($email)->first();

        $notifications = $user
        ? $user
            ->notifications()->with('premiere')->get()
            ->sortBy(function ($notification) {return $notification->premiere->released_at;})
            ->toArray()
        : [];

        return $application->json([
            'params'        => $application['request']->query->all(),
            'user'          => $user,
            'notifications' => array_values($notifications),
        ]);
    }

    public function create(Application $application)
    {
        $movieId = $application['request']->get('movie_id');
        $email   = $application['request']->get('email');

        if (!trim($movieId)) {
            throw new \Exception('Provide movie ID from Rotten Tomatoes API.', 400);
        }

        if (!trim($email)) {
            throw new \Exception('Enter your email address first.', 400);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Please enter valid email address.', 400);
        }

        $movie = API::getMovieByID($movieId);

        $premiere = Premiere::firstOrCreate([
            'id'          => $movieId,
            'released_at' => $movie['release_dates']['theater'],
            'title'       => $movie['title'],
            'poster_url'  => $movie['posters']['thumbnail'],
            'details_url' => $movie['links']['alternate'],
        ]);

        $user = User::firstOrCreate(['email' => $email]);

        $notification = Notification::firstOrNew([
            'user_id'     => $user->id,
            'premiere_id' => $movieId,
        ]);

        if ($notification->id) {
            throw new \Exception(sprintf('You are already subscribed to %s!', $movie['title']), 404);
        } else {
            $notification->save();
        }

        return $application->json([
            'params' => $application['request']->request->all(),
            'user'   => $user,
            'movie'  => $movie,
        ]);
    }

    public function delete(Application $application)
    {
        $notificationId = $application['request']->get('notification_id');

        if (!trim($notificationId)) {
            throw new \Exception('Provide notification ID.', 400);
        }

        Notification::destroy($notificationId);

        return $application->json([
            'params' => $application['request']->request->all(),
        ]);
    }
}
