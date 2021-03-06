<?php

namespace App\Http\Controllers;

use App\Concert;
use Illuminate\Http\Request;

class ConcertsController extends Controller
{
    /**
     * Show.
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $concert = Concert::whereNotNull('published_at')->findOrFail($id);

        return view('concerts.show', ['concert' => $concert]);
    }
}
