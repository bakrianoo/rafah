<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialProvider extends Model
{
    use HasFactory;

    protected $table = 'social_providers';
    protected $primaryKey = 'social_provider_id';
    protected $fillable = ['social_provider_name','social_provider_uuid',
                            'social_provider_user_id','social_provider_avatar'];
    protected $hidden = ['created_at','updated_at'];
}
