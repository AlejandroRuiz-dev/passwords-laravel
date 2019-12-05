<?php

namespace App;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Password extends Model
{


    protected $table = 'passwords';
    protected $fillable = ['title', 'password', 'id_category'];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function create(Request $request, $category)
    {
        $password = new Password();

        $password->title = $request->title;
        $password->password = $request->password;
        $password->id_category = $category;

        $password->save();

        return $password;
    }
}
