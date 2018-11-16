<div class="app-preview">
    <div class="app-entry">
        <div class="app-field">
            <h1><span>会员卡</span></h1>

            <div class="member-card">
                <div class="card-region" style="@if(isset($card) && $card->card_cover==1)background-color: #{{$card->bg_color}};@elseif(isset($card) && $card->card_cover==2)background-image: url({{$card->bg_img}});@else background-color: #55bd47;@endif">
                    <div class="card-header">
                        <h4 class="shop-name">
                            <span class="shop-logo" style="background-image:url('{{isset($card) ? $card->store_logo : ''}}')"></span>
                            <div class="shop-name-title">
                                <p class="card_store_name">{{ isset($card) ? $card->store_name : '' }}</p>
                                <p class="card_name">{{ isset($card) ? $card->name : '' }}</p>
                            </div>
                        </h4>
                        <div class="qr-code"></div>
                    </div>
                    <h3 class="member-type"></h3>
                    <div class="card-content">
                        <p class="expiry-date">有效期：<span>无限期</span></p>
                    </div>
                </div>
            </div>

            <div class="top_nav">
                <ul class="top_nav_ul">
                    @if(isset($card) && !empty($card->custom_field1_name))
                        <li class="top_nav_li top_nav_li_1"><p class="top_nav_li_p top_nav_li_p_1">{{$card->custom_field1_name}}</p><a class="top_nav_li_a">查看</a></li>
                    @endif
                    @if(isset($card) && !empty($card->custom_field2_name))
                        <div class="div_line_2" style="display: block; border-right: 1px #e6e4e4 solid;"></div>
                        <li class="top_nav_li top_nav_li_2"><p class="top_nav_li_p top_nav_li_p_2">{{$card->custom_field2_name}}</p><a class="top_nav_li_a">查看</a></li>
                    @endif
                    @if(isset($card) && !empty($card->custom_field3_name))
                        <div class="div_line_3" style="display: block; border-right: 1px #e6e4e4 solid;"></div>
                        <li class="top_nav_li top_nav_li_3"><p class="top_nav_li_p top_nav_li_p_3">{{$card->custom_field3_name}}</p><a class="top_nav_li_a">查看</a></li>
                    @endif
                </ul>
            </div>
            <div style="clear: both"></div>

            <div class="center_title_div">
                @if(isset($card) && !empty($card->center_title))
                    <a class="center_title">{{$card->center_title}}</a>
                @endif
            </div>

            <div class="msg_card_cell custom_detail">
                <ul class="list" id="js_custom_url_preview">
                    @if(isset($card) && !empty($card->custom_cell1_name))
                        <li class="msg_card_section msg_card_section_1"><div class="li_panel"><div class="li_content"><p class="custom_cell"><span class="supply_area"><span class="js_custom_url_tips_pre1">{{$card->custom_cell1_tips}}</span><span class="ic ic_go"></span></span><span class="js_custom_url_name_pre1">{{$card->custom_cell1_name}}</span></p></div></div></li>
                    @endif
                    @if(isset($card) && !empty($card->custom_url_name))
                        <li class="msg_card_section msg_card_section_2"><div class="li_panel"><div class="li_content"><p class="custom_cell"><span class="supply_area"><span class="js_custom_url_tips_pre2">{{$card->custom_url_tips}}</span><span class="ic ic_go"></span></span><span class="js_custom_url_name_pre2">{{$card->custom_url_name}}</span></p></div></div></li>
                    @endif
                    @if(isset($card) && !empty($card->promotion_url_name))
                        <li class="msg_card_section msg_card_section_3"><div class="li_panel"><div class="li_content"><p class="custom_cell"><span class="supply_area"><span class="js_custom_url_tips_pre3">{{$card->promotion_url_tips}}</span><span class="ic ic_go"></span></span><span class="js_custom_url_name_pre3">{{$card->promotion_url_name}}</span></p></div></div></li>
                    @endif
                    @if(isset($card) && ($card->custom_cell1_name || $card->custom_url_name || $card->promotion_url_name))
                        <li class="msg_card_section msg_card_section_last" data-action="update"><div class="li_panel"><div class="li_content"><p class="custom_cell"><span class="supply_area"><span class="js_custom_url_tips_pre"></span><span class="ic ic_go"></span></span><span class="js_custom_url_name_pre">会员卡详情</span></p></div></div></li>
                        <li class="msg_card_section last_li"><div class="li_panel"><div class="li_content"><p class="custom_cell"><span class="supply_area"><span class="js_custom_url_tips_pre"></span><span class="ic ic_go"></span></span><span class="js_custom_url_name_pre">公众号</span></p></div></div></li>

                    @endif
                </ul>
            </div>

        </div>
    </div>
</div>