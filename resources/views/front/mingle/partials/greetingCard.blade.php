

<div class="modal" id="greeting_card" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-inner clearfix">
                <a href="#" class="close" data-dismiss="modal">close</a>
                <div class="clearfix title-selectbox">
                    <span class="modal-title">Greeting Cards</span>
<!--                    <div class="selectbox">
                        <label class="small-semibold">Category</label>
                        <div class="btn-group bootstrap-select"><button type="button" class="btn dropdown-toggle btn-default" data-toggle="dropdown" title="Birthday" aria-expanded="false">
                                <span class="filter-option pull-left">Birthday</span>&nbsp;<span class="bs-caret"><span class="caret"></span></span></button><div class="dropdown-menu open" style="max-height: 387px; overflow: hidden; min-height: 0px;"><ul class="dropdown-menu inner" role="menu" style="max-height: 387px; overflow-y: auto; min-height: 0px;"><li data-original-index="0" class="selected"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">Birthday</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="1"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">Anniversary</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li></ul></div>
                                </div>
                    </div>-->
                </div>  
                <ul class="modal-greetingcard">
                    <li>
                        <div class="custom-radio">
                            <img src="{{ URL("/assets/front/img/giftcard-1.jpg" ) }}" data-src="{{ URL("/assets/front/img/giftcard-large-1.jpg" ) }}"  alt="Gift" width="223" height="162">
                            <div class="overly"></div>
                            <label for="giftcard1">
                                <input type="radio" id="giftcard1" class="giftcard" checked="checked" name="giftcard">
                                <span>                                    
                                </span>
                            </label>
                            <p>Lorem Ipsum</p>
                        </div>
                    </li>
                    <li>
                        <div class="custom-radio">
                            <img src="{{ URL("/assets/front/img/giftcard-2.jpg" ) }}" data-src="{{ URL("/assets/front/img/giftcard-large-2.jpg" ) }}" alt="Gift" width="223" height="162">
                            <div class="overly"></div>
                            <label for="giftcard2"><input type="radio" id="giftcard2" class="giftcard" name="giftcard"><span></span></label>
                            <p>Lorem Ipsum</p>
                        </div>
                    </li>
                    <li>
                        <div class="custom-radio">
                            <img src="{{ URL("/assets/front/img/giftcard-3.jpg" ) }}" data-src="{{ URL("/assets/front/img/giftcard-large-3.jpg" ) }}" alt="Gift" width="223" height="162">
                            <div class="overly"></div>
                            <label for="giftcard3"><input type="radio" id="giftcard3" class="giftcard" name="giftcard"><span></span></label>
                            <p>Lorem Ipsum</p>
                        </div>
                    </li>
                    <li>
                        <div class="custom-radio">
                            <img src="{{ URL("/assets/front/img/giftcard-4.jpg" ) }}" data-src="{{ URL("/assets/front/img/giftcard-large-4.jpg" ) }}" alt="Gift" width="223" height="162">
                            <div class="overly"></div>
                            <label for="giftcard4"><input type="radio" id="giftcard4" class="giftcard" name="giftcard"><span></span></label>
                            <p>Lorem Ipsum</p>
                        </div>
                    </li>
                    <li>
                        <div class="custom-radio">
                            <img src="{{ URL("/assets/front/img/giftcard-5.jpg" ) }}" data-src="{{ URL("/assets/front/img/giftcard-large-5.jpg" ) }}" alt="Gift" width="223" height="162">
                            <div class="overly"></div>
                            <label for="giftcard5"><input type="radio" id="giftcard5" class="giftcard" name="giftcard"><span></span></label>
                            <p>Lorem Ipsum</p>
                        </div>
                    </li>
                    <li>
                        <div class="custom-radio">
                            <img src="{{ URL("/assets/front/img/giftcard-6.jpg" ) }}" data-src="{{ URL("/assets/front/img/giftcard-large-6.jpg" ) }}" alt="Gift" width="223" height="162">
                            <div class="overly"></div>
                            <label for="giftcard6"><input type="radio" id="giftcard6" class="giftcard" name="giftcard"><span></span></label>
                            <p>Lorem Ipsum</p>
                        </div>
                    </li>
                </ul>

                <textarea class="form-control" id="greeting_text" rows="4" cols="5" placeholder="Type your message here"></textarea>
                <div class="form-btnblock clearfix text-right">
                    <a href="#" title="Cancel" class="cancel-link" data-dismiss="modal">Cancel</a>
                    <a href="#large_greeting_card" title="Attach" class="btn btn-primary sendbutton" data-message-type="greeting" data-toggle="modal" data-dismiss="modal">attach</a>
                </div>
            </div>
        </div>
    </div>
</div>
