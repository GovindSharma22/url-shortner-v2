<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortUrl;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;


class ShortUrlController extends Controller
{
    public function index() {
        $user = auth()->user();
        $cacheKey = $user->role === 'super_admin' ? 'shorturls_all' : 'shorturls_user_' . $user->id;

        $urls = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($user) {
        return $user->role === 'super_admin'
        ? ShortUrl::paginate(10) // Super admin sees all URLs
        : ShortUrl::where('user_id', $user->id)->paginate(10); // Others see only their URLs
        });

        return view('shorturls.index', compact('urls'));
    }

    public function create() {

        if (auth()->user()->role === 'super_admin') {
            abort(403, 'Unauthorized access');
        }
        return view('shorturls.create');
    }

    public function store(Request $request) {
        $request->validate(['long_url' => 'required|url']);

        $userId = auth()->id();
        $longUrl = $request->long_url;

        $existing = ShortUrl::where(['user_id' => $userId, 'long_url' => $longUrl])->first();
        if ($existing) {
        return redirect()->route('shorturls.index')->with('message', 'URL already shortened.');
        }

        do {
        $shortCode = Str::random(6);
        } while (ShortUrl::where('short_code', $shortCode)->exists());

        ShortUrl::create([
        'user_id' => $userId,
        'long_url' => $longUrl,
        'short_code' => $shortCode
        ]);

        // Clear caches related to short URLs
        Cache::forget('superadmin_shorturls');
        Cache::forget('client_shorturl_stats');

        return redirect()->route('shorturls.index')->with('message', 'URL shortened successfully!');
    }

    public function redirect($code) {
        // $url = ShortUrl::where('short_code', $code)->firstOrFail();
        // $url->increment('hit_count');
        // return redirect($url->long_url);

        $cacheKey = "short_url_{$code}";
        $url = Cache::remember($cacheKey, now()->addMinutes(60), function () use ($code) {
        return ShortUrl::where('short_code', $code)->firstOrFail();
        });

        $url->increment('hit_count'); // Still updates the DB
        return redirect($url->long_url);
    }
}
