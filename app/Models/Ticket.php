<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'assigned_to_user_id',
        'title',
        'description',
        'category',
        'attachment_path',
        'priority',
        'status',
        'sla_started_at',
        'sla_due_at',
        'sla_resolved_at',
        'sla_breached_at',
        'department_id',
        'source',
        'email_from',
        'email_from_name',
        'email_subject',
        'email_body',
        'email_message_id',
        'email_received_at',
    ];

    protected $casts = [
        'sla_started_at' => 'datetime',
        'sla_due_at' => 'datetime',
        'sla_resolved_at' => 'datetime',
        'sla_breached_at' => 'datetime',
        'email_received_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    public function assignedTechnician()
    {
        return $this->belongsTo(
            User::class,
            'assigned_to_user_id'
        );
    }

    public function activities()
    {
        return $this->hasMany(TicketActivity::class);
    }

    public function internalNotes()
    {
        return $this->hasMany(TicketInternalNote::class);
    }

    public function slaDueAtForPriority(string $priority, $start = null)
    {
        $startAt = $start ? $start->copy() : now();

        return match ($priority) {
            'high' => $startAt->addHours(8),
            'medium' => $startAt->addHours(24),
            default => $startAt->addHours(72),
        };
    }

    public function slaStatus(): string
    {
        if ($this->isSlaCompleted()) {
            return 'completed';
        }

        if ($this->isSlaOverdue()) {
            return 'overdue';
        }

        if ($this->isSlaDueSoon()) {
            return 'due_soon';
        }

        return 'on_track';
    }

    public function slaLabel(): string
    {
        return match ($this->slaStatus()) {
            'completed' => 'Completed',
            'overdue' => 'Overdue',
            'due_soon' => 'Due Soon',
            default => 'On Track',
        };
    }

    public function slaBadgeClasses(): string
    {
        return match ($this->slaStatus()) {
            'completed' => 'bg-slate-50 text-slate-600 ring-slate-200',
            'overdue' => 'bg-red-50 text-red-700 ring-red-200',
            'due_soon' => 'bg-amber-50 text-amber-700 ring-amber-200',
            default => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        };
    }

    public function isSlaOverdue(): bool
    {
        return ! $this->isSlaCompleted()
            && $this->sla_due_at
            && now()->greaterThan($this->sla_due_at);
    }

    public function isSlaDueSoon(): bool
    {
        return ! $this->isSlaCompleted()
            && $this->sla_due_at
            && now()->lessThanOrEqualTo($this->sla_due_at)
            && now()->diffInHours($this->sla_due_at, false) <= 4;
    }

    public function isSlaCompleted(): bool
    {
        return in_array($this->status, ['resolved', 'closed'], true);
    }
}
