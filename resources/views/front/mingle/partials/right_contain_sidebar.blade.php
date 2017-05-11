<div class="inner-rightcol">
    <div class="rightcol-outer mrg-bottom">
        <div class="clearfix filter-grid">
            <div class="perpage col-md-5 padd-leftnone">
                <span class="normal">Items Per Page</span>
                <div class="selectbox width100"> 
                    <select class="selectpicker mingle-selectpicker">
                        <option selected="" value="12">12</option>
                        <option value="24">24</option>
                        <option value="48">48</option>
                        <option value="96">96</option>
                    </select>
                </div>
            </div>

            <div class="text-right padd-rightnone">
            <ul class="pagination"> 
                <li>
<!--                <li class="disabled">-->
                    <a href="javascript:void(0)" aria-label="Previous" id="firstpage">
                        <span class="pagi-parent previous"></span>
                    </a>
                </li> 
                <li class="active">
                    <a href="javascript:void(0)" id="prev">
                        <span class="pagi-child previous"></span>
                        Prev
                    </a>
                </li> 
                <li><a href="#"><span id="pageData">{{$page}}</span> of <span id="mingle_count">{{$totalPages}}</span></a></li> 
                <li>
                    <a href="javascript:void(0)" id="next">
                        Next
                        <span class="pagi-child next"></span>
                    </a>
                </li> 
                <li>
                    <a href="javascript:void(0)" aria-label="Next" id="lastpage">
                        <span class="pagi-parent next" ></span>
                    </a>
                </li> 
            </ul>
        </div>
            
        </div>
        <div class="filterbar mrg-top10 clearfix">
            <span class="bartext mingle_bartext" style="display: none;">Found <span id="search_count_mingle">{{$usersCount}}</span> contacts of {{$usersCount}}</span>
            <div class="gridlist-nav">

                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active grid-icon"><a href="#gridview" role="tab" data-toggle="tab" title="Grid">Grid</a></li>
                    <li role="presentation" class="list-icon"><a href="#listview" role="tab" data-toggle="tab" title="List">List</a></li>
                </ul>

                <!-- Tab panes -->


            </div>

        </div>
    </div>
    <div class="rightcol-outer">
        <div class="tab-content content-white clearfix">
            <div role="tabpanel" class="tab-pane active clearfix" id="gridview">
                @include('front.mingle.partials.grid_view')
            </div>
            <div role="tabpanel" class="tab-pane clearfix" id="listview">
                @include('front.mingle.partials.list_view')
            </div>
        </div> 

    </div>
</div>

@push('scripts')
<script>
var nextPage=2;
var previousPage=1;
$(document).ready(
    function(){
        $('#lastpage span').show();
    }
);

var mingle_page_count=0;
var mingle_page_limit=12;
$( "body" ).delegate( "#next,#prev,#firstpage,#lastpage", "click", function(event, state) {
        var selfObj=$(this);
        //var mingle_page_count=Math.ceil((parseInt($('#mingle_count').html())/mingle_page_limit));
        var mingle_page_count=$('#mingle_count').html();
        
        if(selfObj.attr('id')=='next'){
            page=nextPage;
        }        
        else if(selfObj.attr('id')=='prev'){
            page=previousPage;
        }
        else if(selfObj.attr('id')=='firstpage'){
            page=1;
        }
        else if(selfObj.attr('id')=='lastpage'){
            page=mingle_page_count;
        }
        
        
        if( page==0 || page>mingle_page_count ){
            return false;
        }
        
        jQuery.ajax({
            type: "get",
            url: window.location.href+'/'+page+'/'+mingle_page_limit,
            async: true,
            dataType:'json',
            data:'',
            success: function (response) {                
                if(response.status=='success' && response.gridview_html!='' && response.listview_html!=''){
                    $('#gridview').html(response.gridview_html);
                    $('#listview').html(response.listview_html);
                    if($('#mingle_count').html() > response.pageData)
                        $('#pageData').html(response.pageData);
                    else
                        $('#pageData').html($('#mingle_count').html());
                    nextPage=response.nextPage;
                    previousPage=response.previousPage;
                }
                else if(response.status=='success' && response.html==''){                    
                    selfObj.remove();
                }
                else {
                    alert('Could not get the Data. Please contact Administrator!!');
                    return false;
                }
            }
        });
  });
  
  $( "body" ).delegate( ".mingle-selectpicker", "change", function(event, state) {
        var page = 1;
        var selfObj=$(this);
        var limit = $('.mingle-selectpicker').val();
        jQuery.ajax({
            type: "get",
            url: window.location.href+'/'+page+'/'+limit,
            async: true,
            dataType:'json',
            data:'',
            success: function (response) {                
                if(response.status=='success' && response.gridview_html!='' && response.listview_html!=''){
                    $('#gridview').html(response.gridview_html);
                    $('#listview').html(response.listview_html);
                    if($('#mingle_count').html() > response.pageData)
                        $('#pageData').html(response.pageData);
                    else
                        $('#pageData').html($('#mingle_count').html());
                    mingle_page_limit = $('.mingle-selectpicker').val();
                }
                else if(response.status=='success' && response.html==''){                    
                    selfObj.remove();
                }
                else {
                    alert('Could not get the Data. Please contact Administrator!!');
                    return false;
                }
            }
        });
  });
  
  $( "body" ).delegate( ".mingle_follow", "click", function(event, state) {
        
        var following_id = $(this).attr('data-bind');
        var following_type = $(this).attr('data-title');
        var thisdata = $(this);
         $.ajax({
        url: '{{route("mingleFollow")}}',
                type: 'POST',
                dataType: 'json',
                data: {following_id: following_id,following_type:following_type},
                success: function (r) {
                    if(following_type == 'following'){
                        thisdata.addClass('active');
                        thisdata.text('Unfollow');
                        thisdata.attr('data-title','unfollowing');
                        thisdata.attr('title','Unfollow');
                        //toastr.success("{{ trans('mingle.validation_message.following') }}");
                    }else{
                        thisdata.removeClass('active');
                        thisdata.text('Follow');
                        thisdata.attr('data-title','following');
                        thisdata.attr('title','follow');
                        //toastr.success("{{ trans('mingle.validation_message.unfollowing') }}");
                    }
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
  
  $( "body" ).delegate( ".mingle_invite", "click", function(event, state) {
        
        var inviation_id = $(this).attr('data-bind');
        var inviation_type = $(this).attr('data-title');
        var thisdata = $(this);
         $.ajax({
        url: '{{route("mingleInvitation")}}',
                type: 'POST',
                dataType: 'json',
                data: {inviation_id: inviation_id,inviation_type:inviation_type},
                success: function (r) {
                        
                        thisdata.addClass('active');
                        thisdata.removeClass('mingle_invite');
                        thisdata.text('invited');
                        thisdata.attr('data-title','invited');
                        toastr.success("{{ trans('mingle.validation_message.invite') }}");
                    
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
 
 
  </script>
@endpush