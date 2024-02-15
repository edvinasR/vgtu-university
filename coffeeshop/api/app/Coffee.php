<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use QCod\ImageUp\HasImageUploads;

class Coffee extends Model
{
    use HasImageUploads;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'image', 'price', 'user_id'];
    /**
     * All the images fields for model
     *
     * @var array
    */
    protected static $imageFields = [
        'image' => [
            'placeholder' =>'/placeholder.jpg',
            'width' => 160,
            'height' => 160,
            'resize_image_quality' => 70,
            'crop' => true,
        ]
    ];

}
