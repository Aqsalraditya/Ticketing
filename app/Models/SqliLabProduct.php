<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SqliLabProduct extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini.
     * * @var string
     */
    protected $table = 'sqli_lab_products';

    /**
     * Atribut yang boleh diisi secara massal (mass assignable).
     * Dikosongkan karena ini hanya untuk keperluan demo.
     * * @var array<int, string>
     */
    protected $guarded = [];
}