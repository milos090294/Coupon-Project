<?php

namespace App\Models;

use App\Events\CouponCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'coupon_id';

    protected $fillable = ['type_id', 'subtype_id', 'email'];


    protected $dispatchesEvents = array(

        'created' => CouponCreated::class


    );
}
