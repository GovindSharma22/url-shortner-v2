<?php

 class ShortUrlController extends Controller {
    public function index() {
        $urls = ShortUrl::where('user_id', auth()->id())->paginate(10);
        return view('shorturls.index', compact('urls'));
    }

    public function create() {
        return view('shorturls.create');
    }

    public function store(Request $request) {
        $request->validate(['long_url' => 'required|url']);
        
        $existing = ShortUrl::where('user_id', auth()->id())
                            ->where('long_url', $request->long_url)
                            ->first();
        if ($existing) return response()->json($existing);

        do {
            $shortCode = Str::random(6);
        } while (ShortUrl::where('short_code', $shortCode)->exists());
        
        $shortUrl = ShortUrl::create([
            'user_id' => auth()->id(),
            'long_url' => $request->long_url,
            'short_code' => $shortCode
        ]);
        
        return redirect()->route('shorturls.index');
    }