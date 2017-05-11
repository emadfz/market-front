<?php

namespace App\Models;

use PDO;
use Illuminate\Database\Eloquent\Model;
use Datatables;
use Carbon\Carbon;
use DB;
use App\Models\ReceiverMessageList;

class MessageList extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'messages';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['msg_subject','msg_content','msg_status','id','created_at','deleted_at'];       
    
    public function send_message($data)
    {
        return $this->create($data);
    }

    public function getAllMessages($user_type = NULL) {
        
        if(!empty(trim($user_type) ))
        {
            if(trim($user_type) == 'employee')
            {
                $result = DB::table('messages as msg')
                    ->select(['msg.msg_subject'
                        , DB::raw(' group_concat( case when emp_dept.department_name != "" then emp_dept.department_name else concat( emp.first_name ," ", emp.last_name ) end ) as name')
                        ,DB::raw('DATE_FORMAT(msg.created_at,"%e %b %Y") as created_at') ,'recv.msg_isRead', 'recv.msg_isFlagged' , 'msg.id'])
                    ->join('msgs_receiver as recv','recv.messages_id', '=', 'msg.id')
                    ->join('msgs_sender as send','send.messages_id', '=', 'msg.id')
                    ->leftjoin('admin_users as emp','emp.id','=','send.sender_employee_msgs_id')
                    ->leftjoin('employee_departments as emp_dept','emp_dept.id','=','emp.department_id')
                    ->where('recv.receiver_employee_msgs_id','=',\Auth::guard('admin')->user()->id)
                    ->WhereNotNull('send.sender_employee_msgs_id')
                    ->where('send.sender_employee_msgs_id','!=','0')
                    ->where('msg.msg_status','!=','draft')
                    ->WhereNull('recv.deleted_at')  
                    ->whereNull('recv.msg_isMovedto_folder')
                    ->groupby('msg.id')
                    ->orderBy(DB::raw('IFNULL(recv.msg_isRead,0)'), 'ASC')
                    ->orderBy('msg.created_at', 'DESC')
                    ->get();
            }
            else if(trim($user_type) == 'member')
            {
                $result = DB::table('messages as msg')
                    ->select([
                        DB::raw('group_concat(concat(u.first_name," ",u.last_name)) as name' )
                        ,'msg.msg_subject' 
                       , DB::raw('DATE_FORMAT(msg.created_at,"%e %b %Y") as created_at') , 'recv.msg_isRead', 'recv.msg_isFlagged','msg.id'])
                    ->join('msgs_receiver as recv','recv.messages_id', '=', 'msg.id')
                    ->join('msgs_sender as send','send.messages_id', '=', 'msg.id')
                    ->leftjoin('users as u','u.id','=','send.sender_member_msgs_id')
                    ->where('recv.receiver_employee_msgs_id','=',\Auth::guard('admin')->user()->id)
                    ->WhereNotNull('send.sender_member_msgs_id')
                    ->where('send.sender_member_msgs_id','!=','0')
                    ->where('msg.msg_status','!=','draft')
                    ->WhereNull('recv.deleted_at')
                    ->whereNull('recv.msg_isMovedto_folder')
                    ->groupby('msg.id')
                    ->orderBy(DB::raw('IFNULL(recv.msg_isRead,0)'), 'ASC')
                    ->orderBy('msg.created_at', 'DESC')
                    ->get();
            }
            else if(trim($user_type) == 'other')
            {
                $result = DB::table('messages as msg')
                    ->select([
                        DB::raw('group_concat(othr.Name) as name')
                        , 'msg.msg_subject' 
                        ,DB::raw('DATE_FORMAT(msg.created_at,"%e %b %Y") as created_at') , 'recv.msg_isRead' , 'recv.msg_isFlagged' , 'msg.id'])
                    ->join('msgs_receiver as recv','recv.messages_id', '=', 'msg.id')
                    ->join('msgs_sender as send','send.messages_id', '=', 'msg.id')
                    ->leftjoin('other_users as othr','othr.id','=','send.sender_otheruser_msgs_id')
                    ->where('recv.receiver_employee_msgs_id','=',\Auth::guard('admin')->user()->id)
                    ->WhereNotNull('send.sender_otheruser_msgs_id')
                    ->where('send.sender_otheruser_msgs_id','!=','0')
                    ->where('msg.msg_status','!=','draft')
                    ->WhereNull('recv.deleted_at')
                    ->whereNull('recv.msg_isMovedto_folder')
                    ->groupby('msg.id')
                    ->orderBy(DB::raw('IFNULL(recv.msg_isRead,0)'), 'ASC')
                    ->orderBy('msg.created_at', 'DESC')
                    ->get();
            }            
        }
        else
        {
            $result = DB::table('messages as msg')
                    ->select([
                        DB::raw('group_concat(case when sender_employee_msgs_id IS NOT NULL '
                                . ' then (case when emp_dept.department_name != "" then emp_dept.department_name else concat(emp.first_name," ",emp.last_name) end ) '
                                . ' when sender_member_msgs_id IS NOT NULL then concat(u.first_name," ",u.last_name)'
                                . ' when sender_otheruser_msgs_id IS NOT NULL then othr.Name'
                                . ' else " " '
                                . ' end ) as name')
                        ,'msg.msg_subject'
                        ,DB::raw('DATE_FORMAT(msg.created_at,"%e %b %Y") as created_at') 
                        , 'recv.msg_isRead','recv.msg_isFlagged','msg.id'])
                    ->join('msgs_receiver as recv','recv.messages_id', '=', 'msg.id')
                    ->join('msgs_sender as send','send.messages_id', '=', 'msg.id')                    
                    ->leftjoin('admin_users as emp','emp.id','=','send.sender_employee_msgs_id')
                    ->leftjoin('employee_departments as emp_dept','emp_dept.id','=','emp.department_id')
                    ->leftjoin('users as u','u.id','=','send.sender_member_msgs_id')
                    ->leftjoin('other_users as othr','othr.id','=','send.sender_otheruser_msgs_id')
                    ->where('recv.receiver_employee_msgs_id','=',\Auth::guard('admin')->user()->id)   
                    ->where('msg.msg_status','!=','draft')
                    ->WhereNull('recv.deleted_at')
                    ->whereNull('recv.msg_isMovedto_folder')
                    ->groupby('msg.id')
                    ->orderBy(DB::raw('IFNULL(recv.msg_isRead,0)'), 'ASC')
                    ->orderBy('msg.created_at', 'DESC')
                    ->get();
        }
                        
        return getAuthorizedButton($result)->toJson();
    }
    
    public function getAllSentMessages(){
               
        $result = DB::table('messages as msg')
                    ->select([
                        DB::raw('concat("To :",group_concat(case when receiver_employee_msgs_id IS NOT NULL '
                                . ' then (case when emp_dept.department_name != "" then emp_dept.department_name else concat(emp.first_name," ",emp.last_name) end ) '
                                . ' when receiver_member_msgs_id IS NOT NULL then concat(u.first_name," ",u.last_name)'
                                . ' when receiver_otheruser_msgs_id IS NOT NULL then othr.Name'
                                . ' else " " '
                                . ' end )) as name')
                        ,'msg.msg_subject'
                        , DB::raw('DATE_FORMAT(msg.created_at,"%e %b %Y") as created_at') 
                        , 'send.msg_isRead', 'send.msg_isFlagged' ,'msg.id'])
                    ->join('msgs_sender as send','send.messages_id', '=', 'msg.id')
                    ->leftjoin('msgs_receiver as recv','recv.messages_id', '=', 'msg.id')
                    ->leftjoin('admin_users as emp','emp.id','=','recv.receiver_employee_msgs_id')
                    ->leftjoin('employee_departments as emp_dept','emp_dept.id','=','emp.department_id')
                    ->leftjoin('users as u','u.id','=','recv.receiver_member_msgs_id')
                    ->leftjoin('other_users as othr','othr.id','=','recv.receiver_otheruser_msgs_id')
                    ->where( 'send.sender_employee_msgs_id','=',\Auth::guard('admin')->user()->id ) 
                    ->where('msg.msg_status','!=','draft')
                    ->WhereNull('send.deleted_at')
                    ->whereNull('send.msg_isMovedto_folder')
                    ->groupby('msg.id')               
                    ->orderBy('msg.created_at', 'DESC')
                    ->get();
        
        return getAuthorizedButton($result)->toJson();
    }
    
    public function getAllDraftMessages()
    {
        $result = DB::table('messages as msg')
                    ->select([
                        DB::raw('"Draft" as name')
                        ,'msg.msg_subject'
                        , DB::raw('DATE_FORMAT(msg.created_at,"%e %b %Y") as created_at') 
                        , 'recv.msg_isRead','recv.msg_isFlagged' ,'msg.id'])
                    ->leftjoin('msgs_sender as send','send.messages_id', '=', 'msg.id')
                    ->leftjoin('msgs_receiver as recv','recv.messages_id', '=', 'msg.id')
                    ->leftjoin('admin_users as emp','emp.id','=','recv.receiver_employee_msgs_id')
                    ->leftjoin('employee_departments as emp_dept','emp_dept.id','=','emp.department_id')
                    ->leftjoin('users as u','u.id','=','recv.receiver_member_msgs_id')
                    ->leftjoin('other_users as othr','othr.id','=','recv.receiver_otheruser_msgs_id')
                    ->where( 'send.sender_employee_msgs_id','=',\Auth::guard('admin')->user()->id ) 
                    ->WhereNull('recv.deleted_at')
                    ->where('msg.msg_status','=','draft')
                    ->whereNull('recv.msg_isMovedto_folder')
                    ->groupby('msg.id')                    
                    ->orderBy('msg.created_at', 'DESC')
                    ->get();
        
        return getAuthorizedButton($result)->toJson();
    }
    
    public function getAllTrashedMessages()
    {
        $result = DB::table('messages as msg')
                    ->select([
                        DB::raw('case when sender_employee_msgs_id IS NOT NULL '
                                . ' then (case when emp_dept.department_name != "" then emp_dept.department_name else concat(emp.first_name," ",emp.last_name) end ) '
                                . ' when sender_member_msgs_id IS NOT NULL then concat(u.first_name," ",u.last_name)'
                                . ' when sender_otheruser_msgs_id IS NOT NULL then othr.Name'
                                . ' else " " '
                                . ' end as name')
                        ,'msg.msg_subject'
                        ,DB::raw('DATE_FORMAT(msg.created_at,"%e %b %Y") as created_at')
                        , 'recv.msg_isRead', 'recv.msg_isFlagged' ,'msg.id'])
                    ->join('msgs_receiver as recv','recv.messages_id', '=', 'msg.id')
                    ->join('msgs_sender as send','send.messages_id', '=', 'msg.id')                    
                    ->leftjoin('admin_users as emp','emp.id','=','send.sender_employee_msgs_id')
                    ->leftjoin('employee_departments as emp_dept','emp_dept.id','=','emp.department_id')
                    ->leftjoin('users as u','u.id','=','send.sender_member_msgs_id')
                    ->leftjoin('other_users as othr','othr.id','=','send.sender_otheruser_msgs_id')
                    ->where('recv.receiver_employee_msgs_id','=',\Auth::guard('admin')->user()->id)                    
                    ->WhereNotNull('recv.deleted_at')                    
                    ->groupby('msg.id')                    
                    ->orderBy('recv.deleted_at', 'DESC')
                    ->get();
        
        return getAuthorizedButton($result)->toJson();
    }
    
    public function searchUser($data)
    {
        $results = array();
        if($data['user_type'] == 'employee')
        {
            $users = DB::table('admin_users')
		->where('first_name', 'LIKE', '%'.$data['keyword'].'%')
		->orWhere('last_name', 'LIKE', '%'.$data['keyword'].'%')
                ->orWhere('personal_email', 'LIKE', '%'.$data['keyword'].'%')
                ->whereNull('deleted_at')
		->take(10)->get();
	
            foreach ($users as $value)
            {
                $results[] = [ 'id' => 'employee-'.$value->id, 'text' => $value->first_name.' '.$value->last_name .'<'.$value->personal_email.'>' ];
            }
        }
        else if($data['user_type'] == 'member')
        {
            $users = DB::table('users')
		->where('first_name', 'LIKE', '%'.$data['keyword'].'%')
		->orWhere('last_name', 'LIKE', '%'.$data['keyword'].'%')
                ->orWhere('email', 'LIKE', '%'.$data['keyword'].'%')
                ->whereNull('deleted_at')
		->take(10)->get();
	
            foreach ($users as $value)
            {
                $results[] = [ 'id' => 'member-'.$value->id, 'text' => $value->first_name.' '.$value->last_name .'<'.$value->email.'>' ];
            }
        }
        else if($data['user_type'] == 'other_users')
        {
            $users = DB::table('other_users')
		->where('Name', 'LIKE', '%'.$data['keyword'].'%')
                ->orWhere('email_id', 'LIKE', '%'.$data['keyword'].'%')
                ->whereNull('deleted_at')
		->take(10)->get();
	
            foreach ($users as $value)
            {
                $results[] = [ 'id' => 'other-'.$value->id, 'text' => $value->Name .'<'.$value->email_id.'>' ];
            }
        }        
        $final_call['item'] = $results;
        return  json_encode($results);
    }
    
    public function counterallmessages()
    {
            $result['employee'] = DB::table('messages as msg')
                    ->select(['msg.id'])
                    ->leftjoin('msgs_receiver as recv','recv.messages_id', '=', 'msg.id')
                    ->leftjoin('msgs_sender as send','send.messages_id', '=', 'msg.id')
                    ->where('recv.receiver_employee_msgs_id','=',\Auth::guard('admin')->user()->id)    
                    ->WhereNotNull('send.sender_employee_msgs_id')
                    ->where('send.sender_employee_msgs_id','!=','0')
                    ->where('msg.msg_status','!=','draft')
                    ->WhereNull('recv.msg_isRead')
                    ->WhereNull('recv.deleted_at')     
                    ->whereNull('recv.msg_isMovedto_folder')
                    ->count();           
            
            $result['member'] = DB::table('messages as msg')
                    ->select(['msg.id'])
                    ->join('msgs_receiver as recv','recv.messages_id', '=', 'msg.id')
                    ->join('msgs_sender as send','send.messages_id', '=', 'msg.id')                    
                    ->where('recv.receiver_employee_msgs_id','=',\Auth::guard('admin')->user()->id)
                    ->WhereNotNull('send.sender_member_msgs_id')
                    ->where('send.sender_member_msgs_id','!=','0')
                    ->where('msg.msg_status','!=','draft')
                    ->WhereNull('recv.msg_isRead')
                    ->WhereNull('recv.deleted_at')
                    ->whereNull('recv.msg_isMovedto_folder')
                    ->count();
           
            $result['other'] = DB::table('messages as msg')
                    ->select(['msg.id'])
                    ->join('msgs_receiver as recv','recv.messages_id', '=', 'msg.id')
                    ->join('msgs_sender as send','send.messages_id', '=', 'msg.id')                    
                    ->where('recv.receiver_employee_msgs_id','=',\Auth::guard('admin')->user()->id)
                    ->WhereNotNull('send.sender_otheruser_msgs_id')
                    ->where('send.sender_otheruser_msgs_id','!=','0')
                    ->where('msg.msg_status','!=','draft')
                    ->WhereNull('recv.msg_isRead')
                    ->WhereNull('recv.deleted_at')
                    ->whereNull('recv.msg_isMovedto_folder')
                    ->count();
           
            $result['inbox'] = DB::table('messages as msg')
                    ->select(['msg.id'])
                    ->join('msgs_receiver as recv','recv.messages_id', '=', 'msg.id')
                    ->join('msgs_sender as send','send.messages_id', '=', 'msg.id')                    
                    ->where('recv.receiver_employee_msgs_id','=',\Auth::guard('admin')->user()->id)   
                    ->where('msg.msg_status','!=','draft')
                    ->WhereNull('recv.msg_isRead')
                    ->WhereNull('recv.deleted_at')
                    ->whereNull('recv.msg_isMovedto_folder')
                    ->count();
             
             $result['draft'] = DB::table('messages as msg')
                    ->select(['msg.id'])
                    ->join('msgs_sender as send','send.messages_id', '=', 'msg.id')
                    ->leftjoin('msgs_receiver as recv','recv.messages_id', '=', 'msg.id')
                    ->where( 'send.sender_employee_msgs_id','=',\Auth::guard('admin')->user()->id ) 
                    ->WhereNull('recv.deleted_at')
                    ->where('msg.msg_status','=','draft')
                    ->whereNull('recv.msg_isMovedto_folder')
                    ->count();
             
             $result['trash'] = DB::table('messages as msg')
                    ->select(['msg.id'])
                    ->join('msgs_receiver as recv','recv.messages_id', '=', 'msg.id')
                    ->join('msgs_sender as send','send.messages_id', '=', 'msg.id')                    
                    ->where('recv.receiver_employee_msgs_id','=',\Auth::guard('admin')->user()->id)                    
                    ->WhereNull('recv.msg_isRead')
                    ->WhereNotNull('recv.deleted_at')
                    ->whereNull('recv.msg_isMovedto_folder')
                    ->count();
            
            return $result;        
    }
    public function getmsgdetail( $msg_id, $type='' )
    {
        if($type != 'sent')
        {            
            $result = DB::table('messages as msg')
                    ->select([
                        DB::raw(' group_concat(case when sender_employee_msgs_id IS NOT NULL '
                                . ' then concat((case when emp_dept.department_name != "" then emp_dept.department_name else concat(emp.first_name," ",emp.last_name) end )," < ",emp.personal_email," > ") '
                                . ' when sender_member_msgs_id IS NOT NULL then concat(u.first_name," ",u.last_name," < ",u.email," > ")'
                                . ' when sender_otheruser_msgs_id IS NOT NULL then concat(othr.Name," < ",othr.email_id," > ")'
                                . ' else " " '
                                . ' end ) as name')   
                        ,DB::raw(' group_concat(case when sender_employee_msgs_id IS NOT NULL '
                                . ' then concat("employee-",sender_employee_msgs_id) '
                                . ' when sender_member_msgs_id IS NOT NULL then concat("member-",sender_member_msgs_id)'
                                . ' when sender_otheruser_msgs_id IS NOT NULL then concat("other-",sender_otheruser_msgs_id)'
                                . ' else " " '
                                . ' end ) as user_ids')                        
                        ,'msg.msg_subject'
                        ,'msg.msg_content'
                        ,DB::raw('DATE_FORMAT(msg.created_at,"%r %e %b %Y") as created_at')
                        ,'recv.msg_isRead','recv.msg_isFlagged' ,'msg.id'])
                    ->join('msgs_receiver as recv','recv.messages_id', '=', 'msg.id')
                    ->join('msgs_sender as send','send.messages_id', '=', 'msg.id')                    
                    ->leftjoin('admin_users as emp','emp.id','=','send.sender_employee_msgs_id')
                    ->leftjoin('employee_departments as emp_dept','emp_dept.id','=','emp.department_id')
                    ->leftjoin('users as u','u.id','=','send.sender_member_msgs_id')
                    ->leftjoin('other_users as othr','othr.id','=','send.sender_otheruser_msgs_id')                    
                    ->where('msg.id','=',$msg_id)                    
                    ->groupby('msg.id')               
                    ->orderBy('msg.created_at', 'DESC')
                    ->get(); 
        }
        else
        {
        
        $result = DB::table('messages as msg')
                    ->select([
                        DB::raw(' group_concat(case when receiver_employee_msgs_id IS NOT NULL '
                                . ' then concat((case when emp_dept.department_name != "" then emp_dept.department_name else concat(emp.first_name," ",emp.last_name) end )," < ",emp.personal_email," > ") '
                                . ' when receiver_member_msgs_id IS NOT NULL then concat(u.first_name," ",u.last_name," < ",u.email," > ")'
                                . ' when receiver_otheruser_msgs_id IS NOT NULL then concat(othr.Name," < ",othr.email_id," > ")'
                                . ' else " " '
                                . ' end ) as name')
                        ,'msg.msg_subject'
                        ,'msg.msg_content'
                        ,DB::raw('DATE_FORMAT(msg.created_at,"%r %e %b %Y") as created_at')
                        ,'recv.msg_isRead','recv.msg_isFlagged' ,'msg.id'])
                    ->join('msgs_sender as send','send.messages_id', '=', 'msg.id')
                    ->leftjoin('msgs_receiver as recv','recv.messages_id', '=', 'msg.id')
                    ->leftjoin('admin_users as emp','emp.id','=','recv.receiver_employee_msgs_id')
                    ->leftjoin('employee_departments as emp_dept','emp_dept.id','=','emp.department_id')
                    ->leftjoin('users as u','u.id','=','recv.receiver_member_msgs_id')
                    ->leftjoin('other_users as othr','othr.id','=','recv.receiver_otheruser_msgs_id')                    
                    ->where('msg.id','=',$msg_id)                    
                    ->groupby('msg.id')               
                    ->orderBy('msg.created_at', 'DESC')
                    ->get();        
            
        }
        return getAuthorizedButton($result);
    }
    public function msgasread( $msg_id )
    {
        $data = array();
        $data['msg_isRead'] = '1';
        $data['msg_isNotified'] = '1';
        return ReceiverMessageList::where('messages_id', $msg_id )->withTrashed()->update($data);        
    }

    public function allmessagesfromfolder($folder_name)
    {
        $result = DB::table('messages as msg')
                    ->select([
                        DB::raw('group_concat(case when sender_employee_msgs_id IS NOT NULL '
                                . ' then (case when emp_dept.department_name != "" then emp_dept.department_name else concat(emp.first_name," ",emp.last_name) end ) '
                                . ' when sender_member_msgs_id IS NOT NULL then concat(u.first_name," ",u.last_name)'
                                . ' when sender_otheruser_msgs_id IS NOT NULL then othr.Name'
                                . ' else " " '
                                . ' end ) as name')
                        ,'msg.msg_subject'
                        ,DB::raw('DATE_FORMAT(msg.created_at,"%e %b %Y") as created_at') 
                        , 'recv.msg_isRead','recv.msg_isFlagged','msg.id'])
                    ->join('msgs_receiver as recv','recv.messages_id', '=', 'msg.id')
                    ->join('msgs_sender as send','send.messages_id', '=', 'msg.id')                    
                    ->leftjoin('admin_users as emp','emp.id','=','send.sender_employee_msgs_id')
                    ->leftjoin('employee_departments as emp_dept','emp_dept.id','=','emp.department_id')
                    ->leftjoin('users as u','u.id','=','send.sender_member_msgs_id')
                    ->leftjoin('other_users as othr','othr.id','=','send.sender_otheruser_msgs_id')
                    ->join('message_folders as fld','recv.msg_folder_id', '=', 'fld.id')
                    ->where('recv.receiver_employee_msgs_id','=',\Auth::guard('admin')->user()->id)   
                    ->where('msg.msg_status','!=','draft')
                    ->WhereNull('recv.deleted_at')
                    ->where('fld.folder_name','=',"$folder_name")
                    ->groupby('msg.id')
                    ->orderBy(DB::raw('IFNULL(recv.msg_isRead,0)'), 'ASC')
                    ->orderBy('msg.created_at', 'DESC')
                    ->get();    
        
        return getAuthorizedButton($result)->toJson();
    }    
    public function getmsgnotified()
    {
        $result = DB::table('messages as msg')
                ->select([
                    DB::raw('group_concat(case when sender_employee_msgs_id IS NOT NULL '
                            . ' then (case when emp_dept.department_name != "" then emp_dept.department_name else concat(emp.first_name," ",emp.last_name) end ) '
                            . ' when sender_member_msgs_id IS NOT NULL then concat(u.first_name," ",u.last_name)'
                            . ' when sender_otheruser_msgs_id IS NOT NULL then othr.Name'
                            . ' else " " '
                            . ' end ) as name')
                    ,'msg.msg_subject'
                    ,'msg.created_at'
                    ,'fld.folder_name'
                    , 'recv.msg_isRead','recv.msg_isFlagged','msg.id'])
                ->join('msgs_receiver as recv','recv.messages_id', '=', 'msg.id')
                ->join('msgs_sender as send','send.messages_id', '=', 'msg.id')                    
                ->leftjoin('admin_users as emp','emp.id','=','send.sender_employee_msgs_id')
                ->leftjoin('employee_departments as emp_dept','emp_dept.id','=','emp.department_id')
                ->leftjoin('users as u','u.id','=','send.sender_member_msgs_id')
                ->leftjoin('other_users as othr','othr.id','=','send.sender_otheruser_msgs_id')
                ->leftjoin('message_folders as fld','recv.msg_folder_id', '=', 'fld.id')
                ->where('recv.receiver_employee_msgs_id','=',\Auth::guard('admin')->user()->id)   
                ->where('msg.msg_status','!=','draft')
                ->WhereNull('recv.deleted_at')                
                ->whereNull('recv.msg_isRead')
                ->whereNull('recv.msg_isNotified')
                ->groupby('msg.id')
                ->orderBy(DB::raw('IFNULL(recv.msg_isRead,0)'), 'ASC')
                ->orderBy('msg.created_at', 'DESC')
                ->get();        
        foreach($result as $index=>$value){
            if(empty($value->folder_name)){
                $result[$index]->folder_name='inbox';
            }            
        }        
        return $result;
    }
    
    public function getmsgnotifiedcounter()
    {
        $result = DB::table('messages as msg')
                ->select([ DB::raw('counter(msg.id) as id') ])
                ->join('msgs_receiver as recv','recv.messages_id', '=', 'msg.id')
                ->join('msgs_sender as send','send.messages_id', '=', 'msg.id')                    
                ->leftjoin('admin_users as emp','emp.id','=','send.sender_employee_msgs_id')
                ->leftjoin('employee_departments as emp_dept','emp_dept.id','=','emp.department_id')
                ->leftjoin('users as u','u.id','=','send.sender_member_msgs_id')
                ->leftjoin('other_users as othr','othr.id','=','send.sender_otheruser_msgs_id')
                ->where('recv.receiver_employee_msgs_id','=',\Auth::guard('admin')->user()->id)   
                ->where('msg.msg_status','!=','draft')
                ->WhereNull('recv.deleted_at')                
                ->whereNull('recv.msg_isRead')
                ->whereNull('recv.msg_isNotified')
                ->groupby('msg.id')
                ->orderBy(DB::raw('IFNULL(recv.msg_isRead,0)'), 'ASC')
                ->orderBy('msg.created_at', 'DESC')
                ->count();
        
        return getAuthorizedButton($result)->toJson();
    }
}
