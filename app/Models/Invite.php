<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

// app/Models/Invite.php
class Invite extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'email', 'token', 'role', 'accepted', 'expires_at'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}

