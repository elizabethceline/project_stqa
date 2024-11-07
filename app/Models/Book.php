<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Book extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'books';

    protected $fillable = [
        'name',
        'desc',
        'author',
        'availability',
        'edition',
        'count',
    ];

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'reserves', 'book_id', 'customer_id');
    }
}
