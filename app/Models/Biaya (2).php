<?php

namespace App\Models;

use App\Traits\HasFormatRupiah;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biaya extends Model
{
    use HasFactory;
    use HasFormatRupiah;
    protected $guarded = [];
    protected $append = ['nama_biaya_full'];

    /**
     * Interect with the user's first name
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute 
     */
    protected function namaBiayaFull(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->nama . ' - ' . $this->formatRupiah('jumlah'),
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($biaya) {
            $biaya->user_id = auth()->user()->id;
        }); 

        static::updating(function ($biaya) {
            $biaya->user_id = auth()->user()->id;
        }); 
    }
}
