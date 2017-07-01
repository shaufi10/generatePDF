<?php 

namespace App\Model;
  
use Illuminate\Database\Eloquent\Model;
  
class Message extends Model
{
     /**
	  * table name
	  */
     protected $table = 'messages';
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
     protected $fillable = ['job_application_id', 'sender', 'message', 'n_status',
     						'created'];
	 /**
	  * not mass assignable property
	  */
     protected $guarded = ['updated'];
	 
	 protected $hidden = ['updated'];
}
?>

