<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 //eloquent is laravel's db layer eli tkhali est3mel data w op li ysirou alihom sehlin
class Todo extends Model
{
    /** @use HasFactory<\Database\Factories\TodoFactory> */
    use HasFactory;

    /**
     *
     * @var array<int,string>
     */
    protected $fillable = [ //ken var li lena ynjmou yetsajlou fel db and no others
        'user_id',
        'title',
        'description',
        'completed',
    ];
    /**
     *
     * @var array<string, string>
     */
    protected $casts = [
        'completed'=> 'boolean',
    ];

    public function user()
    {
        return $this-> belongsTo(User::class); //un todo appartient aa un seul utilisateur 
    }
}
