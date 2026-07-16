<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'team_id', 'group_id', 'owner_id',
        'name', 'email', 'phone', 'phone_digits', 'phone_country',
        'company', 'job_title', 'website', 'address', 'city',
        'photo', 'birthday', 'lifecycle_stage',
        'facebook', 'twitter', 'linkedin',
        'notes', 'admin_comment', 'custom_fields', 'last_contacted_at',
        'approval_status', 'approved_by', 'approved_at',
        // legacy columns kept for backward compat
        'number', 'dp_path', 'social_links', 'description_html',
        'rating', 'status', 'suspended_at', 'banned_at',
    ];

    protected $casts = [
        'social_links'     => 'array',
        'custom_fields'    => 'array',
        'rating'           => 'decimal:1',
        'birthday'         => 'date',
        'suspended_at'     => 'datetime',
        'banned_at'        => 'datetime',
        'last_contacted_at' => 'datetime',
        'approved_at'      => 'datetime',
    ];

    protected static function booted(): void
    {
        // Keep the indexed digits-only phone in sync for fast duplicate lookups.
        static::saving(function (Contact $contact) {
            $contact->phone_digits = $contact->phone
                ? (preg_replace('/\D/', '', $contact->phone) ?: null)
                : null;
        });
    }

    /** Normalize a phone string to its digits for matching. */
    public static function normalizePhone(?string $phone): ?string
    {
        $digits = preg_replace('/\D/', '', (string) $phone);

        return $digits === '' ? null : $digits;
    }

    public function isPending(): bool   { return $this->approval_status === 'pending'; }
    public function isApproved(): bool  { return $this->approval_status === 'approved'; }
    public function isRejected(): bool  { return $this->approval_status === 'rejected'; }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // -----------------------------------------------------------------
    // Relationships
    // -----------------------------------------------------------------

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function emails(): HasMany
    {
        return $this->hasMany(EmailMessage::class);
    }

    public function contactNotes(): HasMany
    {
        return $this->hasMany(ContactNote::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(ContactFile::class);
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(ContactGalleryImage::class);
    }

    public function editHistories(): HasMany
    {
        return $this->hasMany(ContactEditHistory::class)->latest()->limit(5);
    }

    public function editRequests(): HasMany
    {
        return $this->hasMany(ContactEditRequest::class);
    }

    // Legacy many-to-many via explicit pivot (kept for existing code that may use it)
    public function contactGroups(): BelongsToMany
    {
        return $this->belongsToMany(
            ContactGroup::class,
            'contact_contact_group',
            'contact_id',
            'contact_group_id'
        )->withTimestamps();
    }
}
