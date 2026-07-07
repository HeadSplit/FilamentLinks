<?php

namespace App\Http\Controllers;

use App\Models\Click;
use App\Models\Link;
use Illuminate\Http\Request;

class ClickController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $short_url)
    {
        $link = Link::where('short_url', $short_url)
            ->firstOrFail();

        $link->increment('clicks');

        Click::create([
            'link_id' => $link->id,
            'user_ip' => request()->ip(),
        ]);
        return redirect($link->original_url);

    }
}
