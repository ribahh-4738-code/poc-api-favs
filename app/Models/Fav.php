<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Fav extends Pivot
{
    protected $table = 'fav';

    public $incrementing = true;

    protected $fillable = [
        'client_id', 'product_id', 'review'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
