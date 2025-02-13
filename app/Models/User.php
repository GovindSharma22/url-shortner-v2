<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// app/Models/User.php
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'client_id'];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // Create a new client entry when a user is created
            $client = Client::create([
                'name' => $user->name, // Adjust based on client structure
            ]);

            // Assign client_id to the user before saving
            $user->client_id = $client->id;
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}

