<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['client_name', 'amount', 'status', 'invoice_date', 'file_path'];

}
