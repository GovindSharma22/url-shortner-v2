URL Shortener 

Major Step to Create new project 

composer create-project laravel/laravel url-shortener

# Step 1: Install Dependencies
composer require laravel/ui laravel/sanctum laravel/cashier
php artisan ui bootstrap --auth
npm install && npm run dev
php artisan migrate

# Step 2: Configure Environment (.env)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=url_shortener
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=admin@example.com
MAIL_FROM_NAME="URL Shortener"

# Step 3: Create Models & Migrations
php artisan make:model User -m
php artisan make:model Invite -m
php artisan make:model ShortUrl -m
php artisan make:model UrlHit -m
php artisan make:model Client -m

# Step 4: Define Migrations
Schema::create('clients', function (Blueprint $table) {
	$table->id();
	$table->string('name');
	$table->timestamps();
});

Schema::create('users', function (Blueprint $table) {
	$table->id();
	$table->string('name');
	$table->string('email')->unique();
	$table->string('password');
	$table->enum('role', ['super_admin', 'admin', 'member']);
	$table->foreignId('client_id')->nullable()->constrained();
	$table->timestamps();
});

Schema::create('invites', function (Blueprint $table) {
	$table->id();
	$table->string('email')->unique();
	$table->string('token');
	$table->timestamp('expires_at');
	$table->foreignId('client_id')->constrained();
	$table->foreignId('invited_by')->constrained('users');
	$table->timestamps();
});

Schema::create('short_urls', function (Blueprint $table) {
	$table->id();
	$table->foreignId('user_id')->constrained();
	$table->string('long_url');
	$table->string('short_code')->unique();
	$table->integer('hit_count')->default(0);
	$table->timestamps();
});

# Step 5: Implement Laravel Sanctum Authentication
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate

# Add Sanctum Middleware in Kernel.php
protected $middlewareGroups = [
	'api' => [
    	\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    	'throttle:api',
    	\Illuminate\Routing\Middleware\SubstituteBindings::class,
	],
];

# Update API Authentication Guard in config/auth.php
'guards' => [
	'api' => [
    	'driver' => 'sanctum',
    	'provider' => 'users',
	],
],

# Step 6: Implement URL Shortening Logic
class ShortUrlController extends Controller {
	public function index() {
    	$urls = Cache::remember('user_urls_' . auth()->id(), 600, function () {
        	return ShortUrl::where('user_id', auth()->id())->paginate(10);
    	});
    	return view('shorturls.index', compact('urls'));
	}

	public function create() {
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

    	$shortCode = Str::random(6);
    	while (ShortUrl::where('short_code', $shortCode)->exists()) {
        	$shortCode = Str::random(6);
    	}

    	ShortUrl::create([
        	'user_id' => $userId,
        	'long_url' => $longUrl,
        	'short_code' => $shortCode
    	]);

    	Cache::forget('user_urls_' . $userId);

    	return redirect()->route('shorturls.index')->with('message', 'URL shortened successfully!');
	}

	public function redirect($code) {
    	$url = ShortUrl::where('short_code', $code)->firstOrFail();
    	$url->increment('hit_count');
    	return redirect($url->long_url);
	}
}

# Step 7: Implement Role-based Access
Gate::define('manage-invites', function ($user) {
	return $user->role === 'super_admin' || $user->role === 'admin';
});

# Step 8: Implement Invitation System
class InviteController extends Controller {
	public function index() {
    	$invites = Invite::paginate(10);
    	return view('invites.index', compact('invites'));
	}

	public function sendInvite(Request $request) {
    	$request->validate(['email' => 'required|email']);
    	$token = Str::random(32);
    	Invite::create([
        	'email' => $request->email,
        	'token' => $token,
        	'expires_at' => now()->addMinutes(30),
        	'client_id' => auth()->user()->client_id,
        	'invited_by' => auth()->id()
    	]);
    	Mail::to($request->email)->send(new InviteMail($token));
    	return redirect()->route('invites.index');
	}
}

# Step 9: Implement CSV Export
class ExportController extends Controller {
	public function exportCsv() {
    	return response()->streamDownload(function() {
        	$file = fopen('php://output', 'w');
        	fputcsv($file, ['Short URL', 'Long URL', 'Hit Count']);
        	ShortUrl::all()->each(fn($url) => fputcsv($file, [$url->short_code, $url->long_url, $url->hit_count]));
        	fclose($file);
    	}, 'short_urls.csv');
	}
}

# Step 10: Implement Super Admin Short URL Viewing
class SuperAdminController extends Controller {
	public function viewShortUrls() {
    	$urls = Cache::remember('all_short_urls', 600, function () {
        	return ShortUrl::paginate(10);
    	});
    	return view('superadmin.shorturls', compact('urls'));
	}
}


#Step 11: Run Migrations & Seed the Database
php artisan migrate --seed

php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload





# Step 12: Unit Test Cases
php artisan make:test ShortUrlTest
php artisan make:test InviteTest
php artisan make:test ExportTest



Process Flow:  https://docs.google.com/document/d/1e_RVkaiyKPGyKH3woAz8G2yYMurtuWW2pUjia8i14Zo/edit?tab=t.0 

Login Page




SuperAdmin Login Details:   superadmin@example.com
Password: password


Step:2 Client Wise URL Detail





Step:3  Click on view All short URL





Step:4  Invite Client and list client details





Step: 5  After client invite user will register do login 





Step 6: view short URL and can create new URL






Step: 7  Create URL 


