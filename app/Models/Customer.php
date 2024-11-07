<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'customers';

    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'mob',
        'lock',
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'reserves', 'customer_id', 'book_id');
    }
}
