<?php 

namespace App\Model;
  
use Illuminate\Database\Eloquent\Model;
  
class Interest extends Model
{
     /**
	  * table name
	  */
     protected $table = 'tbl_interest';
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
     protected $fillable = ['interest',
     						'created'];
	 /**
	  * not mass assignable property
	  */
     protected $guarded = ['n_status', 'updated'];
	 
	 protected $hidden = ['created','n_status','updated'];
}
?>

