<?php

namespace App\Http\Controllers\Front;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\TopicDepartment;
use App\Models\Forum;
use App\Models\ForumLike;
use App\Models\ForumComment;
use App\Models\ReportAbuse;
use Illuminate\Support\Facades\DB;

class ForumController extends Controller {

    public function __construct() {
        $this->TopicDepartment = new TopicDepartment();
        $this->Forum = new Forum();
        $this->ForumLike = new ForumLike();
        $this->ForumComment = new ForumComment();
        $this->ReportAbuse = new ReportAbuse();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //$categories = DB::table('topic_departments')->get();
        $categories = $this->TopicDepartment->getDepartment();
        return view('front.forum.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $categories = $this->TopicDepartment->getDepartment();
        $departments = $this->TopicDepartment->getDepartmentNames();
        return view('front.forum.create', compact('categories', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $this->validate($request, [
            'topic_department_id' => 'required',
            'topic_name' => 'required',
            'topic_description' => 'required'
                ], ['topic_department_id.required' => trans("forum.validation_custom.topic_department_id"),
            'topic_name.required' => trans("forum.validation_custom.topic_name"),
            'topic_description.required' => trans("forum.validation_custom.topic_description")
        ]);

        $data = $request->only('topic_department_id', 'topic_name', 'topic_description');
        $data['admin_users_id'] = $userId = Auth::id();
        $data['status'] = 'Active';
        $incrementdepartment = $this->TopicDepartment->incrementDepartmentTopic($data['topic_department_id']);
        $res = $this->Forum->create($data);

        if ($res) {
            \Flash::success(trans('forum.common.add_success'));
            return ($request->ajax()) ?
                    response()->json(['status' => 'success', 'redirectUrl' => route('forum')]) :
                    redirect()->route('forum.index');
        } else {
            \Flash::error(trans('forum.common.failure'));
            return redirect()->route('forum');
        }


        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    public function topicListing(Request $request) {
        $data = $request->only('cid', 'term');

        $topics = $this->Forum->getTopicsByCharacter($data['term'], $data['cid'])->toArray();
        $topics_count = $this->Forum->getTopicsCount($data['cid'])->toArray();

        return JsonResponse::create(array('data' => $topics, 'count' => count($topics_count)));

        //$topics = $topics->toJson();
        //return JsonResponse::create($response);
    }

//    public function topic($id) {
//        $categories = $this->TopicDepartment->getDepartment();
//        $topics = $this->Forum->getTopics($id);
//        return view('front.forum.topic', compact('categories', 'topics'));
//    }
    public function topic($id, Request $request, $page = 1) {
        $categories = $this->TopicDepartment->getDepartment();
        $categoriesName = $this->TopicDepartment->getDepartmentName($id);
        
        //DB::enableQueryLog();
        $topics = $this->Forum->getTopics($id, ($page - 1) * config('project.forum_listing_limit'), config('project.forum_listing_limit'));
        //dd(DB::getQueryLog());
        
        $topicsCount = count($this->Forum->getTopicsCount($id));
        if ($topicsCount > config('project.forum_listing_limit')) {
            $pageData = $page * config('project.forum_listing_limit');
        } else {
            $pageData = $topicsCount;
        }

        if ($request->ajax()) {
            return response()->json([
                        'status' => 'success',
                        'html' => view('front.forum.partials.topics_right', compact('categories','categoriesName', 'topics', 'topicsCount'))->render(),
                        'nextPage' => $page + 1,
                        'previousPage' => $page - 1,
            ]);
        }

        return view('front.forum.topic', compact('categories','categoriesName', 'topics', 'topicsCount'));
    }

    public function getTopic($pid, $tid, Request $request, $page = 1) {
        $categories = $this->TopicDepartment->getDepartment();
        $getTopicData = $this->Forum->getTopicData($tid)->toArray();
        
        $getCommentData = $this->ForumComment->getCommentData($tid, ($page - 1) * config('project.forum_listing_limit'), config('project.forum_listing_limit'));
        $topicCommentsCount = $this->ForumComment->getCountCommentData($tid);
        if ($topicCommentsCount > config('project.forum_listing_limit')) {
            $pageData = $page * config('project.forum_listing_limit');
        } else {
            $pageData = $topicCommentsCount;
        }
        
        if ($request->ajax()) {
            return response()->json([
                        'status' => 'success',
                        'html' => view('front.forum.partials.listing_comments', compact('categories', 'getTopicData', 'getCommentData','topicCommentsCount'))->render(),
                        'nextPage' => $page + 1,
                        'previousPage' => $page - 1,
            ]);
        }
        
        return view('front.forum.get_topic', compact('categories', 'getTopicData', 'getCommentData','topicCommentsCount'));
    }

    public function storeComment(Request $request) {
        $this->validate($request, [
            'comment' => 'required',
                ], ['comment.required' => trans("forum.validation_custom.comment"),
        ]);

        $data = $request->only('comment', 'forum_id', 'topic_department_id');
        $data['user_id'] = Auth::id();
        $data['total_dislikes'] = 0;
        $data['total_likes'] = 0;

        $res = $this->ForumComment->create($data);
        $incrementType = $this->Forum->incrementTypeTopic($data['forum_id'], 'total_comments');
        if ($res) {
            \Flash::success(trans('forum.common.add_success_comment'));
            return ($request->ajax()) ?
                    response()->json(['status' => 'success', 'redirectUrl' => route('getTopic', [$data['topic_department_id'], $data['forum_id']])]) :
                    redirect()->route('getTopic', [$data['topic_department_id'], $data['forum_id']]);
        } else {
            \Flash::error(trans('forum.common.failure'));
            return redirect()->route('getTopic', [$data['topic_department_id'], $data['forum_id']]);
        }
    }
    
    public function storereportFlag(Request $request){
        
        $data = $request->only('report_value', 'something_else','report_type','ref_id');
        
        if(empty($data['report_value']) && empty($data['something_else'])){
            $this->validate($request, [
        'report_value' => 'required'],
            ['report_value.required' => trans("forum.validation_custom.atleast_one")
            ]);
        }
//        $this->validate($request, [
//            'report_value' => 'required',
//            'something_else'=>'required'], 
//            ['report_value.required' => trans("forum.validation_custom.atleast_one"),'something_else.required' => trans("forum.validation_custom.value")
//        ]);
        
        $report_value_comma_seprated = '';
        if(!empty($data['report_value'])){
            $report_value_comma_seprated = implode(',', array_keys($data['report_value']));
        }
       
        $reportData = array();
        $reportData['report_value'] = $report_value_comma_seprated;
        $reportData['something_else'] = $data['something_else'];
        $reportData['type'] = $data['report_type'];
        $reportData['ref_id'] = $data['ref_id'];
        $reportData['user_id'] = Auth::id();
        
        $res= $this->ReportAbuse->create($reportData);
        return JsonResponse::create(array('data' => $res, 'status' => 'success'));
        
    }
    
    public function getReportFlag($ref_id='',$type='',$ftype=''){
        return view('front.forum.partials.report_flag', compact('ref_id', 'type'));
    }

    public function updateLike(Request $request) {

        $data = $request->only('data', 'forum_id', 'type');

        $saveData = array();
        $saveData['forum_id'] = $data['forum_id'];
        $saveData['user_id'] = Auth::id();
        $saveData['type'] = $data['type'];
        $saveData['status'] = $data['data'];

        $checkExist = $this->ForumLike->getExistData($saveData);

        if (!$checkExist) {
            $id = $this->ForumLike->create($saveData);
            if ($data['type'] == 'topic') {
                //increment in forum table to manage our count
                $incrementType = $this->Forum->incrementTypeTopic($data['forum_id'], $data['data']);
            } else {
                $incrementType = $this->ForumComment->incrementTypeComment($data['forum_id'], $data['data']);
            }
        } else {
            if ($data['type'] == 'topic') {
                //increment in forum table to manage our count
                $decrementType = $this->Forum->decrementTypeTopic($data['forum_id'], $checkExist['status']);
                $incrementType = $this->Forum->incrementTypeTopic($data['forum_id'], $data['data']);
            } else {
                $decrementType = $this->ForumComment->decrementTypeComment($data['forum_id'], $checkExist['status']);
                $incrementType = $this->ForumComment->incrementTypeComment($data['forum_id'], $data['data']);
            }
            $this->ForumLike->where('forum_id', $data['forum_id'])->update($saveData);
        }

        if ($data['type'] == 'topic') {
            $topics = $this->Forum->getTopicTypeCount($data['forum_id']);
            return JsonResponse::create(array('data' => $topics, 'success' => 1));
        } else {
            $comments = $this->ForumComment->getCommentTypeCount($data['forum_id']);
            return JsonResponse::create(array('data' => $comments, 'success' => 1));
        }
    }
    
    public function mostrecent(Request $request, $page = 1){
        
        $categories = $this->TopicDepartment->getDepartment();
        $topics = $this->Forum->getAllTopics(($page - 1) * config('project.forum_listing_limit'), config('project.forum_listing_limit'));
        
        $topicsCount = count($this->Forum->getTopicsCount());
        if ($topicsCount > config('project.forum_listing_limit')) {
            $pageData = $page * config('project.forum_listing_limit');
        } else {
            $pageData = $topicsCount;
        }

        if ($request->ajax()) {
            return response()->json([
                        'status' => 'success',
                        'html' => view('front.forum.partials.topics_right', compact('categories', 'topics', 'topicsCount'))->render(),
                        'nextPage' => $page + 1,
                        'previousPage' => $page - 1,
            ]);
        }
        
        return view('front.forum.mostrecent', compact('categories', 'topics', 'topicsCount'));
    }
    
    public function popular(Request $request, $page = 1){
        $categories = $this->TopicDepartment->getDepartment();
        $topics = $this->Forum->getAllPopularTopics(($page - 1) * config('project.forum_listing_limit'), config('project.forum_listing_limit'));
        
        $topicsCount = count($this->Forum->getTopicsCount());
        if ($topicsCount > config('project.forum_listing_limit')) {
            $pageData = $page * config('project.forum_listing_limit');
        } else {
            $pageData = $topicsCount;
        }

        if ($request->ajax()) {
            return response()->json([
                        'status' => 'success',
                        'html' => view('front.forum.partials.topics_right', compact('categories', 'topics', 'topicsCount'))->render(),
                        'nextPage' => $page + 1,
                        'previousPage' => $page - 1,
            ]);
        }
        
        return view('front.forum.popular', compact('categories', 'topics', 'topicsCount'));
    }

}
