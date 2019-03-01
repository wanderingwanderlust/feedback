<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $table = 'feedback';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = ['critic_name', 'movie_name', 'helpful', 'unhelpful'];
}