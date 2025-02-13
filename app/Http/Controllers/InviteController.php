<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invite;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\InviteMail;

class InviteController extends Controller
{
    public function index()
    {
        $invites = Invite::paginate(10);
        return view('superadmin.invite', compact('invites'));
    }

    public function sendInvite(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'email' => 'required|email|unique:invites,email',
        ]);

        $client = Client::firstOrCreate(['name' => $request->client_name]);
        $token = Str::random(32);

        $invite = Invite::create([
            'email' => $request->email,
            'token' => $token,
            'expires_at' => now()->addMinutes(30),
            'client_id' => $client->id,
            'invited_by' => auth()->id()
        ]);

        Mail::to($request->email)->send(new InviteMail($invite));

        return redirect()->route('superadmin.invite')->with('success', 'Invitation Sent!');
    }
}
