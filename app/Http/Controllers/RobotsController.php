<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function index(): Response
    {
        $lines = [
            'User-agent: *',
        ];

        if (! config('app.indexing')) {
            $lines[] = 'Disallow: /';
        } else {
            $lines[] = 'Disallow:';
            $lines[] = '';
            $lines[] = 'Sitemap: '.url('/sitemap.xml');
        }

        $lines[] = '';

        return response(implode("\n", $lines), 200)->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
