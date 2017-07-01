<?php 

namespace App\Model;
  
use Illuminate\Database\Eloquent\Model;
  
class VacancySector extends Model
{
     /**
	  * table name
	  */
     protected $table = 'vacancy_sector';
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
     protected $fillable = ['vacancy_id','sector_id','n_status',
     						'created'];
	 /**
	  * not mass assignable property
	  */
     protected $guarded = ['updated'];
}
?>

