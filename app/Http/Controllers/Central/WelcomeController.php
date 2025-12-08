<?php

declare(strict_types=1);

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    /**
     * Display the landing page.
     */
    public function index(): Response
    {
        return Inertia::render('Welcome', [
            'appName' => config('app.name'),
            'appUrl' => config('app.url'),
            'appDomain' => config('app.domain'),
        ]);
    }
}
