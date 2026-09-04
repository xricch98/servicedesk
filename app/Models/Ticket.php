<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ticket extends Model
{
    protected $fillable = [
        'reference',
        'subject',
        'description',
        'requester_id',
        'created_by_id',
        'assigned_to_id',
        'department_id',
        'category_id',
        'status',
        'priority',
        'location',
        'assigned_at',
        'first_response_at',
        'resolved_at',
        'closed_at',
        'reopen_count',
    ];

    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
            'first_response_at' => 'datetime',
            'resolved_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public static function generateReference(Department $department): string
{
    $number = DB::transaction(function () use ($department) {
        $sequence = DB::table('ticket_sequences')
            ->where('department_id', $department->id)
            ->lockForUpdate()
            ->first();

        if (! $sequence) {
            DB::table('ticket_sequences')->insert([
                'department_id' => $department->id,
                'last_number' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return 1;
        }

        $next = $sequence->last_number + 1;

        DB::table('ticket_sequences')
            ->where('department_id', $department->id)
            ->update(['last_number' => $next, 'updated_at' => now()]);

        return $next;
    });

    return $department->code . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
}
}
