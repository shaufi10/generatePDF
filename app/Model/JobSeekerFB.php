<?php 

namespace App\Model;
  
use Illuminate\Database\Eloquent\Model;
  
class JobSeekerFB extends Model
{
     /**
	  * table name
	  */
     protected $table = 'job_seeker_fb';
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
     protected $fillable = ['fb_id','token','age_range','link','gender',
     						'picture','n_status','created'];
	 /**
	  * not mass assignable property
	  */
     protected $guarded = ['updated'];
}
?>

