<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pay extends Model
{
    protected $table = 'pays';
    protected $fillable = [
        'parent_id',
        'payment_id',
        'school_id',
        'staff_id',
        'paid_at',
    ];

    public function parent()
    {
        return $this->belongsTo(\App\Models\ParentModel::class, 'parent_id');
    }
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
