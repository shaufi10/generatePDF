<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TokenSeeker extends Model
{
    /**
     * table name
     */
    protected $table = 'token_seeker';
    /**
     * primary key column
     */
    //protected $id = 'id';
    /**
     * primary key is incrementing integer value
     */
    //$public $incrementing = false;
    /**
     * Do not let lumen automatically manage created_at and updated_at
     */
    public $timestamps = false;
    /**
     * mass assignable property
     */
    protected $fillable = ['job_seeker_id',
        'id','token_device'];
    /**
     * not mass assignable property
     */
    protected $guarded = ['id'];
}
?>

