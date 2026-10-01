<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class RatingController extends Controller
{
    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $result = [];

        User::query()
            ->with('rating')
            ->whereHas('roles', fn($query) => $query->where('role', 'worker'))
            ->each(function ($user, $index) use (&$result) {
                if ($user->rating->isNotEmpty()) {
                    $rCount = $user->rating->count();
                    $rSum = $user->rating->pluck('rating')->sum();
                    $result[$index] = $user->toArray();
                    $result[$index]['rating'] = (int) round($rSum / $rCount);
                }
            });

        return view('userRatings', ['users' => $result]);
    }
}
