<?php 

namespace App\Model;
  
use Illuminate\Database\Eloquent\Model;
  
class City extends Model
{
     /**
	  * table name
	  */
     protected $table = 'tbl_city';
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
     protected $fillable = ['city', 'province',
     						'created'];
	 /**
	  * not mass assignable property
	  */
     protected $guarded = ['updated'];
	 
	 protected $hidden = ['created','updated'];
}
?>

