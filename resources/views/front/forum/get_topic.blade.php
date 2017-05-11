@extends('front.layouts.app')

@section('content')
<section class="content">
    <div class="container">
        <!-- BEGIN Breadcrumb -->
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="{{ route('homepage') }}">{{ trans('form.common.home') }}</a></li>
                    <li><a href="#">{{ trans('form.common.mingle') }}</a></li>
                    <li><a href="{{ route('forum') }}">{{ trans('form.common.forum') }}</a></li>
                    <li class="active">{{ trans('form.common.comment') }}</li>
                </ul>
            </div>
        </div>
        <!-- END Breadcrumb -->


        <h2>{{trans('form.common.comment')}}</h2>
        <div class="forums"> 

            <div class="widecol-bg clearfix bg-sidebar"> 

                <!--Leftside Start -->
                @include('front.forum.partials.left_sidebar')
                <!--Leftside End --> 

                <!--Rightside Start -->
                <div class="rightcol-bg clearfix forums-addcomment">
                    <div class="equal-column">
                    @include('front.forum.partials.display_topic')
                    
                    @include('front.forum.partials.add_comment')
                    
                    <div class="forumstopic-outer comment_inside_forum clearfix">
                    @include('front.forum.partials.listing_comments')
                    </div>
                    <a href="#" class="btn btn-block showmore" title="Show More" id='comment_view_more'>show more</a>
                    
                    <div class="modal addreport" id="addreport" role="basic" aria-hidden="true" tabindex="-1">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content clearfix">
                                <div class="modal-body">
                                    <img src="{{ asset('assets/front/img/loading-spinner-grey.gif') }}" alt="{{ trans('form.loading') }}" class="loading">
                                    <span> &nbsp;&nbsp;{{ trans('form.loading') }} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                     </div>
                </div>
                <!--Rightside End --> 
            </div>
        </div>



    </div>  			
</section>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        
         $('#add_new_comment_forum').on('click', function (e) {
             $('.forum_comments').show();
         });

        var showChar = 300;  // How many characters are shown by default
        var ellipsestext = "...";
        var moretext = "Show more<em></em>";
        var lesstext = "Show less";

 function readmorebind(){

    $('.more').each(function () {
    var content = $(this).html();
    
    if(/\<small/.test(content)){
        
    }else{
        if (content.length > showChar) {

        var c = content.substr(0, showChar);
        var h = content.substr(showChar, content.length - showChar);
        var html = c + '<small class="moreellipses">' + ellipsestext + '</small><span class="morecontent"><small>' + h + '</small><a href="" class="morelink">' + moretext + '</a></span>';
        $(this).html(html);
        }
    }

    });
    }
        readmorebind();

        $('body').delegate('.morelink', 'click', function (e) {
            if ($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(moretext);
            } else {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });
        
        $('body').delegate('.forumcomment', 'click', function (e) {
        

            var data = $(this).find('span').attr('id');
            var forum_id = $(this).find('span').attr('data-bind');
            
            
            var thisdata = $(this);

            $.ajax({
                url: '{{route("updateLike")}}',
                type: 'POST',
                dataType: 'json',
                data: {data: data, forum_id: forum_id, type: 'comment'},
                success: function (r) {
                    //oTable.draw(false);
                    //toastr.success(r.msg);
                    
                    if(data == 'like'){
                        thisdata.next().removeClass("active");
                    }else{
                        thisdata.prev().removeClass("active");
                    }
                    thisdata.addClass('active');
            
                    $('.like_count_comment' + r.data[0].id).html('(' + r.data[0].total_likes + ')');
                    $('.dislike_count_comment' + r.data[0].id).html('(' + r.data[0].total_dislikes + ')');
                },
                error: function (data) {
                    if (data.status === 401) {
                        //window.location="{{URL::to('individual-register')}}";
                        $(".signinModal").click();
                    }
                    if (data.status === 422) {
                        toastr.error("{{ trans('message.failure') }}");
                    }
                }
            });
        });
        
        
        <?php if ($topicCommentsCount > config('project.forum_listing_limit')) { ?>
            var nextPage = 2;
        <?php } else { ?>
        $('#comment_view_more').remove();
            var nextPage = 1;
        <?php } ?>
        var totalPage = {{ceil($topicCommentsCount / config('project.forum_listing_limit'))}}

        $('body').delegate('#comment_view_more', 'click', function(event, state) {
        if (totalPage == nextPage){
        $('#comment_view_more').remove();
        }
        var selfObj = $(this);
            $.ajax({
            type: "get",
                    url: window.location.href + '/comments/' + nextPage,
                    async: true,
                    dataType:'json',
                    data:'',
                    success: function (response) {
                    if (response.status == 'success' && response.html != ''){
                    $('.comment_inside_forum').append(response.html);
                    readmorebind();
                    nextPage = response.nextPage;
                    }
                    else if (response.status == 'success' && response.html == ''){
                    selfObj.remove();
                    }
                    else {
                    alert('Could not get the Data. Please contact Administrator!!');
                    return false;
                    }
                    }
            });
        });
        
        $("body").delegate('a[data-target=#reportabuse_ajax_modal_popup]', 'click', function(ev) {
        ev.preventDefault();
        var target = $(this).attr("href");
        // load the url and show modal on success
        $("#addreport .modal-body").load(target, function() {
        $("#addreport").modal("show");
        });
    
    });

    });
</script>
@endpush