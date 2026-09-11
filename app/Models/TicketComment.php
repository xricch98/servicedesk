<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['ticket_id', 'user_id', 'body', 'is_internal'])]
class TicketComment extends Model
{
    protected function casts(): array
    {
        return ['is_internal' => 'boolean'];
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
