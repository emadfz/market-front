<div class="leftcol-bg"> 
    <a href="#" class="close-filter">Close</a>
    <div class="searchouter">
        <!--        <div class="input-search">
                    <input type="text" class="form-control padd-right35" id="" placeholder="Search Topic">
                    <a href="#" class="search-icon"></a> 
                </div>-->

        <div class="input-search ui-widget">
            <label for="forum_tags_global" class="sr-only">Search Topic: </label>
            <input id="forum_tags_global" type="text" class="form-control padd-right35 forum_tags_global" placeholder="Search Topic">
            <a href="#" class="search-icon"></a> 
        </div>
    </div>
    <ul class="product-nav">
        <li class="topic">
            <a href="{{route('forumCreate')}}" class="btn btn-primary mrg-topnone" title="Create Topic">create topic</a>
            <a href="{{route('forumMostRecent')}}" class="btn btn-border" title="Most Recent Topic">most recent topic</a>
            <a href="{{route('forumPopular')}}" class="btn btn-border" title="popular Topic">popular topic</a>
        </li>
        <li class="blacktitle">Caterories of Choice</li>

        @foreach ($categories as $item)
        <li><a href="{{ route('forumTopic',$item->id) }}" title="{{ ucwords($item->department_name) }}">{{ ucwords($item->department_name) }}<span>({{ $item->topics }})</span></a></li>
        @endforeach
<!--        <li><a href="#" title="Collectibles & Art">Collectibles &amp; Art<span>(5)</span></a></li>
        <li><a href="#" title="Electronics">Electronics<span>(3)</span></a></li>
        <li><a href="#" title="Fashion">Fashion<span>(8)</span></a></li>
        <li><a href="#" title="Home & Garden">Home &amp; Garden<span>(5)</span></a></li>
        <li><a href="#" title="Motors">Motors<span>(3)</span></a></li>-->
    </ul>
</div>

@push('scripts')
<script type="text/javascript">

    $(document).ready(function () {
        $(".info-back").hide();
        $(".forums-cate li").on("click", function () {
            $('.forum_tags').val('');
            $(this).addClass('active').siblings('.active').removeClass('active');
        });
    });

    (function ($) {

        // Custom autocomplete instance.
        $.widget("app.autocomplete", $.ui.autocomplete, {
            _renderItem: function (ul, item) {
                // Replace the matched text with a custom span. This
                // span uses the class found in the "highlightClass" option.
                var re = new RegExp("(" + this.term + ")", "gi"),
                        cls = this.options.highlightClass,
                        template = "<span class='" + cls + "'>$1</span>",
                        label = item.label.replace(re, template),
                        $li = $("<li/>").appendTo(ul);

                if (typeof item.cid == 'undefined') {
                    item.cid = '';
                } else {
                    item.cid = '/' + item.cid
                }

                // Create and return the custom menu item content.
                $("<a/>").attr("href", "{{env('APP_URL')}}" + '/forum/' + item.pid + item.cid)
                        .html(label)
                        .appendTo($li);

                return $li;

            }

        });

        // Demo data for autocomplete source.
        // var tags = ["javascript","jquery","html5","javascript javascript javascript"]

        // Create autocomplete instances.
        $(function () {
            var count = 0;
            $(".forum_tags,.forum_tags_global").autocomplete({
                source: function (request, response) {

                    var cid = this.element.attr('id');
                    var cid_tmp = this.element.attr('id');
                    //search global topic
                    if (cid == 'forum_tags_global')
                        cid_tmp = '';
                    //end

                    $.ajax({
                        url: "{{ route('topicListing') }}",
                        dataType: "json",
                        type: "post",
                        data: {
                            cid: cid_tmp,
                            //maxRows: 15,
                            term: request.term
                        },
                        success: function (data) {
                            count = data.count;

                            res = $.map(data.data, function (item) {
                                return {
                                    label: item.topic_name,
                                    pid: item.topic_department_id,
                                    cid: item.id,
                                    count: count
                                }
                            });

                            if (cid != 'forum_tags_global')
                                res.unshift({label: 'See All (' + count + ')', pid: cid})
                            response(res)
                        }
                    })
                },
                //source: tags,
                highlightClass: "autocomplate-select",
                //appendTo: $("#autotags")
            });



        });

    })(jQuery);
</script>
@endpush