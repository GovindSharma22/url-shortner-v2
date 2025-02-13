<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortUrl;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class SuperAdminController extends Controller
{
    public function viewShortUrls()
    {
        

        $urls = Cache::remember('superadmin_shorturls', now()->addMinutes(10), function () {
        return ShortUrl::paginate(10);
    });

    return view('superadmin.shorturls', compact('urls'));

    }

    public function exportCsv()
    {
        return response()->streamDownload(function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['User', 'Short URL', 'Long URL', 'Hit Count']);
            
            ShortUrl::with('user')->get()->each(function($url) use ($file) {
                fputcsv($file, [$url->user->name, $url->short_code, $url->long_url, $url->hit_count]);
            });

            fclose($file);
        }, 'short_urls.csv');
    }

    public function clientStats()
   {

        if (auth()->user()->role !== 'super_admin') {
        abort(403, 'Unauthorized access');
        }

        $cacheKey = 'client_stats';

        $clients = Cache::remember($cacheKey, now()->addMinutes(10), function () {
        return DB::table('clients')
        ->leftJoin('users', 'clients.id', '=', 'users.client_id')
        ->leftJoin('short_urls', 'users.id', '=', 'short_urls.user_id')
        ->selectRaw('clients.name, COUNT(short_urls.id) as total_urls, SUM(short_urls.hit_count) as total_hits')
        ->groupBy('clients.id', 'clients.name')
        ->paginate(10);
        });

        return view('superadmin.client_stats', compact('clients'));
   }

}
