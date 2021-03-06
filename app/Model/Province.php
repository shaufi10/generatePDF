<?php 

namespace App\Model;
  
use Illuminate\Database\Eloquent\Model;
  
class Province extends Model
{
     /**
	  * table name
	  */
     protected $table = 'tbl_province';
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
     protected $fillable = ['province_name',
     						'created'];
	 /**
	  * not mass assignable property
	  */
     protected $guarded = ['updated'];
	 
	 protected $hidden = ['created','updated'];
}
?>

