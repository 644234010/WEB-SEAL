<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'type' => 'integer',
        'address' => 'array',
        'address1' => 'array',
        'address2' => 'array',
        'address3' => 'array',
    ];

    const ADMIN_TYPE = 1;
    const DEFAULT_TYPE = 0;
    const FINANCE_TYPE = 2; // ฝ่ายการเงิน
    const SHIPPING_TYPE = 3; // ฝ่ายการจัดส่ง
    public const INVENTORY_TYPE = 4;//คลัง


    protected $fillable = [
        'name', 'email', 'password', 'type','address','address1','address2','address3','phone_number'
    ];
    
    public function isAdmin()
    {
        return $this->type == self::ADMIN_TYPE; 
    }
    public function isFinance()
    {
        return $this->type == self::FINANCE_TYPE;
    }

    public function isShipping()
    {
        return $this->type == self::SHIPPING_TYPE;
    }
    public function isInventoryStaff()
    {
        return $this->type == self::INVENTORY_TYPE;
    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->address = $this->address ?? [];
        $this->address1 = $this->address1 ?? [];
        $this->address2 = $this->address2 ?? [];
        $this->address3 = $this->address3 ?? [];
    }
    

}
