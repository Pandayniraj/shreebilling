<?php

namespace App\Models;

use App\Traits\BaseModelTrait;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use BaseModelTrait;

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'category', 'message', 'data', 'data_parser', 'replay_route'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public static function log($user_id, $category, $message, array $attributes = null, $data_parser = null, $replay_route = null)
    {
        $audit_enabled = \Config::get('settings.enable_audit');
        $audit = false;
        $attJson = null;

        if ($audit_enabled) {
            // Remove from array attributes that we do not want to save.
            unset($attributes['_method']);
            unset($attributes['_token']);
            unset($attributes['password']);
            unset($attributes['password_confirmation']);

            if ($attributes) {
                $attJson = json_encode($attributes);
            }

            $audit = self::create([
                'user_id' => $user_id,
                'category' => $category,
                'message' => $message,
                'data' => $attJson,
                'data_parser' => $data_parser,
                'replay_route' => $replay_route,
            ]);
        }

        return $audit;
    }
}
