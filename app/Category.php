<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'id_user'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function passwords()
    {
        return $this->hasMany('App\Password');
    }

    public function create(Request $request, $user)
    {
        $category = new Category();

        $category->name = $request->name;
        $category->id_user = $user->id;

        $category->save();

        return $category;
    }
    
}
