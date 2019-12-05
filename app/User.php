<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\CustomClasses\Token;
use App\Category;
use Illuminate\Support\Facades\App;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password'];

    public function create(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();

        return $user;
    }
    public static function by_field($key, $value)
    {
        $users = self::where($key, $value)->get();
        foreach ($users as $key => $user) {
            return $user;
        }
    }
    
    public function is_authorized(Request $request)
    {
        $token = new Token();
        $header = $request->header("Authorization");    

        if (!isset($header)) {
            return false;
        }
        $data = $token->decode($header);
        return !empty(self::by_field('email', $data->email));
    }

    public function categories()
    {
        return $this->hasMany('App\Category');
    }

    public function passwords()
    {
        return $this->hasManyThrough(Password::class, Category::class, 'id_user', 'id_category', 'id', 'id');
    }
}
