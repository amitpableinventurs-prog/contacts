<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactGalleryImage extends Model
{
    protected $table = 'contact_gallery_images';

    protected $fillable = [
        'team_id',
        'contact_id',
        'user_id',
        'image_path',
        'image_name',
        'size_bytes',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
}
