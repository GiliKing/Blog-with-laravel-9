<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostMeta extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post_metas';

    protected $fillable = [
        'post_id',
        'meta_description',
        'meta_keywords',
        'meta_rorbots'
    ];
}
