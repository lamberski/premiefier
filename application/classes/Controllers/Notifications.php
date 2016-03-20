<?php

namespace Premiefier\Controllers;

use Silex\Application;
use Premiefier\Models\API;
use Premiefier\Models\User;
use Premiefier\Models\Premiere;
use Premiefier\Models\Notification;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Notifications
{
    public function index(Application $application)
    {
        $email = $application['request']->get('email');

        if (!trim($email)) {
            throw new HttpException(400, 'Enter your email address first.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new HttpException(400, 'Please enter valid email address.');
        }

        $user = User::whereEmail($email)->first();

        $notifications = $user
        ? $user
            ->notifications()->with('premiere')->get()
            ->sortBy(function ($notification) {
                return $notification->premiere->released_at;
            })
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
            throw new HttpException(400, 'Provide movie ID from Rotten Tomatoes API.');
        }

        if (!trim($email)) {
            throw new HttpException(400, 'Enter your email address first.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new HttpException(400, 'Please enter valid email address.');
        }

        $movie = API::getMovieByID($application, $movieId);

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
            throw new HttpException(404, sprintf('You are already subscribed to %s!', $movie['title']));
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
            throw new HttpException(400, 'Provide notification ID.');
        }

        Notification::destroy($notificationId);

        return $application->json([
            'params' => $application['request']->request->all(),
        ]);
    }
}
