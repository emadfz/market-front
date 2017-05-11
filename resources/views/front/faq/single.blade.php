@extends('front.layouts.app')

@section('content')
    <!-- Start container here -->
    <section class="content">
        <div class="container">
            <!--breadcrumb Start-->
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li><a href="index.html">Home</a></li>
                        <li><a href="account.html">Help Center</a></li>
                        <li class="active">{{ $topic->topic_name}}</li>
                    </ul>
                </div>
            </div>
            <!--breadcrumb Start-->
            <h2>Help &amp; Support </h2>
            <div class="account-page"> <a href="#" id="productnav" class="btn btn-info visible-xs-inline-block">Filter Option</a>
                <div class="widecol-bg clearfix">
                    <!--Leftside Start -->
                    <div class="leftcol-bg"> <a href="#" class="close-filter">Close</a>
                        <div class="searchouter">
                            <div class="input-search">
                                <input type="text" class="form-control padd-right35" placeholder="Search">
                                <a href="#" class="search-icon"></a> </div>
                        </div>
                        <ul class="product-nav">
                            @foreach($topics as $topic_s)

                                <li class="account-querie"><a href="{{route('helpcenter_single', $topic_s->id)}}" title="Top Queries"><span></span><small>{{$topic_s->topic_name}}</small></a></li>
                            @endforeach
                        </ul>
                    </div>
                    <!--Leftside End -->
                    <!--Rightside Start -->
                    <div class="rightcol-bg clearfix account-inner">
                        <div class="inner-rightcol">
                            <div class="rightcol-outer">
                                <h3>{{$topic->topic_name}}</h3>
                                <p>You can track your orders in 'My Orders'</p>
                                <div class="account-faq">
                                    <div class="panel-group witharrow" id="Faqaccordion" role="tablist" aria-multiselectable="true">
                                        @foreach($topics as $topic)
                                                {{!$x=1}}
                                                @if ($topic->questions->count())
                                                    @foreach($topic->questions as $question)
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading" role="tab" id="Faq{{$x}}">
                                                                    <h4 class="panel-title"> <a role="button" data-toggle="collapse" data-parent="#Faqaccordion" href="#collapse{{$x}}" aria-expanded="false" aria-controls="collapse{{$x}}">{{$question->question}}</a></h4>
                                                                </div>
                                                                <div id="collapse{{$x}}" class="panel-collapse collapse @if($x ==1) in @endif" role="tabpanel" aria-labelledby="Faq{{$x}}">
                                                                    <div class="panel-body">{{$question->answer}}</div>
                                                                </div>
                                                            </div>
                                                            {{!$x++}}
                                                    @endforeach
                                                @else
                                                    <div class="panel panel-default">
                                                       <h3>No Questions for this Topic yet!</h3>
                                                    </div>
                                                @endif
                                            @endforeach   
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
    <!-- End container here -->
@endsection