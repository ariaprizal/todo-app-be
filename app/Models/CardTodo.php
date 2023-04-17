<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardTodo extends Model
{
    use HasFactory;

    protected $primaryKey = 'card_todo_id';

    protected $guarded= ['card_todo_id'];
}
