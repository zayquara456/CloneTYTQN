/* Document ready */
jQuery(document).ready(function($) {
    /* Custom selectbox */
    $('.select').selectbox();

    /* Toggle next */
    $('.toggle-next').click(function(event) {
        event.preventDefault();
        $(this).next().slideToggle();
        $(this).toggleClass('active');
    });

    /* Accordion */
    $('.accordion-heading').click(function(event) {
        event.preventDefault();       

        thisHeading = $(this);
        thisContent = $(this).next('.accordion-content');

        thisGroup = $(this).parents('.accordion-group');

        $(thisGroup).find('.accordion-content').not(thisContent).slideUp();
        $(thisGroup).find('.accordion-heading').not(thisHeading).removeClass('active');

        $(thisContent).slideToggle();
        $(thisHeading).toggleClass('active');
    });


    /* TABS */
    $('.tabbed').each(function(){
        $(this).find('.tab-title li:first').addClass('current');
        $(this).find('.tab-content').not(":first").hide();
        $('.tab-title a').click(function(event) {
            event.preventDefault();
            $(this).parent().addClass("current");
            $(this).parent().siblings().removeClass("current");

            var tab = $(this).attr("href");
            $(this).parentsUntil('.tabbed').siblings(".tab-content").hide();
            $(tab).show();
        });
    });


    /* Share popup */
    $('body').delegate('.share-popup', 'click', function(event) {
        event.preventDefault();
        var sharer = $(this).attr('href');
        window.open( sharer, 'sharer', 'toolbar=0,status=0,width=580,height=369' );
    });


    $('.menu-item-has-children').hover(function() {
        $('.main').addClass('blurred');
    }, function() {
        $('.main').removeClass('blurred');
    });

    $('#main_menu').find('.menu-item-has-children > a').click(function(event) {
        event.preventDefault();
        $(this).next('ul').slideToggle();        

        var thisParent = $(this).parent('li');

        $(thisParent).toggleClass('opening');
        $(thisParent).siblings('li').removeClass('opening');
        $(thisParent).siblings('li').children('ul.sub-menu').slideUp();
    });

    $('#menu_toggler').click(function(event) {
        event.preventDefault();
        $('#main_menu').slideToggle();
        $('.main').toggle();
        $('.footer').toggle();
        $('.top-link').toggle();
    });

    $(window).resize(function(event) {
        if( $(window).width() > 700 ) {
            $('#main_menu').removeAttr('style');
            $('.main').removeAttr('style');
            $('.footer').removeAttr('style');
            $('.top-link').removeAttr('style');
        }
    });


    /* Single Vehicle */ 
    if( $('body').hasClass('single-vehicle') ) {
        $('.vehicle-detail .section').each(function() {
            $(this).bind('inview', function (event, visible) {
                var thisID = $(this).attr('id');
                if (visible) {
                    $('.vehicle-navigation').find('.'+thisID).addClass('current');
                    $('.vehicle-navigation').find('.'+thisID).siblings().removeClass("current");;
                }
            });
        });

        /* Vehicel navigation */
        var sections = {};
        imagesLoaded(document, function() {            
            $('.section').each(function(index) {
                sectionHeight = $(this).height();
                offsetTop = $(this).offset().top - 120;
                offsetBottom = offsetTop + sectionHeight;

                thisScreen = {};
                thisScreen['index'] = index;
                thisScreen['top'] = offsetTop;
                thisScreen['bottom'] = offsetBottom;

                sections[index] = thisScreen;
            });
        });

        $(window).scroll(function(event) {
            var scroolNote = $('.header').innerHeight() + $('.vehicle-banner').innerHeight();
            var scrollPos = $(this).scrollTop();

            if(scrollPos >= scroolNote) {
                $('.header').addClass('dark');
                $('.vehicle-navigation').addClass('fixed');
            } else {
                $('.header').removeClass('dark');
                $('.vehicle-navigation').removeClass('fixed');
            }

            /* Active the menu item */
            $.each(sections, function( key, theScreen ) {
                if( scrollPos > theScreen['top'] && scrollPos < theScreen['bottom'] ) {
                    var itemCurrent = $('#vehicle_navigation_menu li').eq( theScreen['index'] );
                    $(itemCurrent).addClass('current');
                    $(itemCurrent).siblings('li').removeClass('current');

                    vehicle_navigation_icon_bar();
                }
            });
        });


        $('#vehicle_navigation_menu').find('a').click(function(event) {
            event.preventDefault();

            target = $(this).attr('href');
            targetOffset = $(target).offset();

            $('body, html').stop().animate({scrollTop: targetOffset.top - 88});
            $(this).parent('li').addClass('current');
            $(this).parent('li').siblings('li').removeClass('current');

            vehicle_navigation_icon_bar();
        });

        function vehicle_navigation_icon_bar() {
            itemCurrent = $('#vehicle_navigation_menu').find('li.current > a');
            itemPosition = $(itemCurrent).position();
            itemWidth = $(itemCurrent).width();
            $('#vehicle_navigation_icon').stop().animate({left: itemPosition.left + itemWidth/2 - 5.5}, 100);
        }

        var color360viewer = $("#vehicle_color_360_viewer");
        var color360loader = $("#vehicle_color_360_loader");

        $(color360viewer).spritespin({
            width: 980,
            height: 430,
            animate: false,
            renderer: 'image'
        });

        /* Color 360 */
        $('#vehicle_color_list').find('img').click(function(event) {
            event.preventDefault();
            var thisColor = $(this);
            var thisItem = $(this).parent('li');

            $(color360viewer).addClass('blur');
            $(color360loader).show();

            $.ajax({
                url: wp_vars['ajaxurl'],
                type: 'POST',
                dataType: 'JSON',
                data: {
                    post: $(thisColor).attr('post'),
                    color: $(thisColor).attr('color'),
                    action: 'vehicle_color_select'
                },
                success: function(response) {
                    $(color360viewer).spritespin({
                        source : response
                    });

                    $(color360viewer).removeClass('blur');
                    $(color360loader).hide();

                    $(thisItem).addClass('current');
                    $(thisItem).siblings('li').removeClass('current');
                }
            });
        });

        /* First color */
        $('#vehicle_color_list').find('img').first().click();

        /* Vehicle Interior slide 360 (Get Inside) */
        $.ajax({
            url: wp_vars['ajaxurl'],
            dataType: 'JSON',
             type: 'POST',
            data: {
                post: $('#vehicle_interior_360').attr('for'),
                action: 'vehicle_interior_360'
            },
            success: function(response) {
                $('#vehicle_interior_360').spritespin({
                    width: 1200,
                    height: 600,
                    animate: false,
                    renderer: 'image',
                    source : response
                });
            }
        });

        /* Feature view detail */
        $('body.single-vehicle').delegate('.vehicle-feature-view-detail', 'click', function(event) {
            event.preventDefault();

            var thisLink = $(this);

            $.ajax({
                url: wp_vars['ajaxurl'],
                data: {
                    vehicle: $(thisLink).attr('for'),
                    terior: $(thisLink).attr('href'),
                    feature: $(thisLink).attr('value'),
                    action: 'vehicle_feature_view_detail'
                },
                success: function(response) {
                    $('#vehicle_popup_content').html( response );
                    $('body').addClass('overflow-hidden');
                    $('#vehicle_popup').css('top', '0');
                }
            });
        });


        /* Share */
        $('body').delegate('#vehicle_share_button', 'click', function(event) {
            event.preventDefault();
            $(this).toggleClass('opening');
            $(this).parent().toggleClass('expanded');
            $(this).toggleText('✘', '');
        });


        /* Popup close */
        $('#vehicle_popup_closer').click(function(event) {
            event.preventDefault();
            $('body').removeClass('overflow-hidden');
            $('#vehicle_popup').css('top', '-100%');
        });

        jQuery.fn.extend({
            toggleText: function (a, b){
                var isClicked = false;
                var that = this;
                this.click(function (){
                    if (isClicked) { that.text(a); isClicked = false; }
                    else { that.text(b); isClicked = true; }
                });
                return this;
            }
        });



        /* Flexslider */
        $('.vehicle-slider').flexslider();

        $('.vehicle-video').flexslider({
            directionNav: false            
        });

        $('.vehicle-feature, .vehicle-news').flexslider({
            animation: "slide",
            animationLoop: false,
            directionNav: false,
            itemWidth: 285,
            itemMargin: 20
        });

        $('.vehicle-version').flexslider({
            animation: "slide",
            animationLoop: false,
            directionNav: false,
            itemWidth: 387,
            itemMargin: 20
        });
    }


    /* Single dealer */
    if( $('body').hasClass('single-dealer') ) {
        $('#menu-item-209').addClass('current-menu-parent');

        function dealer_single_map_initialize() {
            /* Map properties */
            var mapProp = {
                center: new google.maps.LatLng(15.9030623,105.8066791),
                zoom: 6,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            /* Create map */
            dealerMap = new google.maps.Map( document.getElementById("dealer_map_sigle"), mapProp );

            var saved = $('#dealer_map_sigle').attr('for');

            console.log(saved);

            if( saved ) {
                saved = saved.split(',');
                savedPosition = new google.maps.LatLng( saved[0], saved[1] );

                dealerMarker = new google.maps.Marker({
                    position: savedPosition,
                    map: dealerMap,
                    icon: wp_vars['themeurl'] + '/images/marker.png'
                });

                dealerMap.setZoom(15);
                dealerMap.setCenter( savedPosition );
            }
        }

        google.maps.event.addDomListener(window, 'load', dealer_single_map_initialize);
    }


    /* Find a dealer */
    if( $('body').hasClass('page-template-pagesfind-a-dealer-php') ) {
        var dealerAlls = {};
        var dealerMaps;

        function dealer_archive_map_initialize() {
            /* Map properties */
            var mapProp = {
                center: new google.maps.LatLng(15.9030623,105.8066791),
                zoom: 6,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            /* Create map */
            dealerMaps = new google.maps.Map( document.getElementById("dealer_map"), mapProp );

            $.ajax({
                url: wp_vars['ajaxurl'],
                dataType: 'JSON',
                data: {
                    action: 'dealer_load_makers'
                },
                success: function(response) {
                    $.each(response, function(index, dealerData) {
                        var dealer = [];

                        dealer['marker'] = new google.maps.Marker({
                            position: new google.maps.LatLng( dealerData['lat'], dealerData['lng'] ),
                            map: dealerMaps,
                            title: dealerData['title'],
                            icon: wp_vars['themeurl'] + '/images/marker.png'
                        });

                        dealer['info'] = new google.maps.InfoWindow({
                            content: dealerData['content'],
                        });

                        var dealerID = dealerData['id'];

                        dealerAlls[dealerID] = dealer;

                        google.maps.event.addListener( dealer['marker'], 'click', function() {
                            dealer_get_direction( dealerID );
                        });
                    });
                }
            });

            function dealer_get_direction(dealerID) {
                $.each(dealerAlls, function(index, dealer) {
                    dealer['info'].close();
                });

                dealerAlls[dealerID]['info'].open( dealerMaps, dealerAlls[dealerID]['marker'] );
                dealerMaps.setCenter( dealerAlls[dealerID]['marker'].getPosition() );    
                dealerMaps.setZoom( 15 );       
            }

            $('.dealer-get-direction').click(function(event) {
                var dealerID = $(this).attr('for');
                dealer_get_direction( dealerID );
            });
        }

        google.maps.event.addDomListener(window, 'load', dealer_archive_map_initialize);

        /* Frist location */
        $('#dealer_nav').find('.accordion-heading').first().click();
    }
   

    /* Price calculating */
    if( $('body').hasClass('page-template-pagesprice-calculator-php') ) {
        /* Load version selectbox */
        function vehicle_load_version_selectbox( vehicleID ) {
            $.ajax({
                url: wp_vars['ajaxurl'],
                type: 'GET',
                data: {
                    action: 'vehicle_get_version',
                    vehicle: vehicleID
                },
                success: function(response) {
                    if( response ) {
                        $('#price_calculator_version').html( response ).show();
                        $('#price_calculator_version_select').selectbox();
                    } else {
                        $('#price_calculator_version').hide();
                    }
                }
            });
        }

        var queryID = $('#price_calculating_vehicle_select').val();
        vehicle_load_version_selectbox( queryID );

        $('#price_calculating_vehicle_select').selectbox({
            onChange: function( vehicleID ) {
                vehicle_load_version_selectbox( vehicleID );
            }
        });
	

        /* Submit form */
        $('#price_calculator_form').submit(function(event) {
            event.preventDefault();
            thisForm = $(this);

            $.ajax({
                url: wp_vars['ajaxurl'],
                type: 'GET',
                data: $(thisForm).serialize(),
                success: function(response) {
                    if( response ) {
                        $('#price_calculator_result').html(response).show();
                    }
                }
            });
        });
    }

	
	
    /* Loan calculating */
    if( $('body').hasClass('page-template-pagesloan-calculator-php') ) {
        /* Load version selectbox */
        function vehicle_load_version_selectbox( vehicleID ) {
            $.ajax({
                url: wp_vars['ajaxurl'],
                type: 'GET',
                data: {
                    action: 'vehicle_get_version',
                    vehicle: vehicleID
                },
                success: function(response) {
                    if( response ) {
                        $('#price_calculator_version').html( response ).show();
                        $('#price_calculator_version_select').selectbox();
                    } else {
                        $('#price_calculator_version').hide();
                    }
                }
            });
        }

        var queryID = $('#price_calculating_vehicle_select').val();
        vehicle_load_version_selectbox( queryID );

        $('#price_calculating_vehicle_select').selectbox({
            onChange: function( vehicleID ) {
                vehicle_load_version_selectbox( vehicleID );
            }
        });
		$('#thoihanvay').selectbox();

        /* Submit form */
        $('#loan_calculator_form').submit(function(event) {
            event.preventDefault();
            thisForm = $(this);

            $.ajax({
                url: wp_vars['ajaxurl'],
                type: 'GET',
                data: $(thisForm).serialize(),
                success: function(response) {
                    if( response ) {
                        $('#loan_calculator_result').html(response).show();
                    }
                }
            });
        });
    }

    /* Booking a test drive */
    /*if( $('body').hasClass('page-template-pagesbook-a-test-drive-php') || $('body').hasClass('page-template-find-out-xtrail') ) {     */ 
        var booking_select_vehicle_preview = $('#booking_select_vehicle_preview').selectbox({
            onChange: function(carID) {
                $('img.booking-preview-image').hide();
                $('img#booking_preview_' + carID).show();
            }
        });

        $('#booking_form').submit(function(event) {
            event.preventDefault();
            bookingForm = $(this);

            //console.log($(bookingForm).serialize());

            var successUrl = bookingForm.attr('data-success') || 'http://nissan-vietnam.vn/#no-success-link';

            $.ajax({
                url: wp_vars['ajaxurl'],
                type: 'POST',
                data: $(bookingForm).serialize(),
                success: function(response) {
                    if( !response ) {

                        window.location = successUrl;

                        /*
                        f( $('body').hasClass('page-template-pagesbook-a-test-drive-php') )
                            window.location = 'http://nissan-vietnam.vn/mua-xe/dang-ky-lai-thu/thanh-cong/';
                        else
                            window.location = 'http://www.nissan.com.vn/dang-ky-tim-hieu-thong-tin/thanh-cong/';
                        */

                    } else {
                        $(bookingForm).find('.alert').show();
                        $(bookingForm).find('.alert > ul').html(response);

                        var bookingFAO = $(bookingForm).find('.alert > ul').offset();
                        $('body, html').stop().animate({scrollTop: bookingFAO.top - 100});
                    }
                }
            });

            return false;
        });
    /*}*/
/*......*/
/*......*/

    /* Booking a test drive */
    if( $('body').hasClass('page-template-pagescontact-us-php') ) {
        $('#contact_form').submit(function(event) {
            contactForm = $(this);
            event.preventDefault();
            $.ajax({
                url: wp_vars['ajaxurl'],
                type: 'POST',
                data: $(contactForm).serialize(),
                success: function(response) {
                    if( !response ) {
                        $(contactForm).hide();
                        $(contactForm).prev().hide();
                        $(contactForm).next().show();
                        $('body, html').stop().animate({scrollTop: 0});
                    } else {
                        $(contactForm).find('.alert').show();
                        $(contactForm).find('.alert > ul').html(response);

                        var contactFAO = $(contactForm).find('.alert > ul').offset();
                        $('body, html').stop().animate({scrollTop: contactFAO.top - 100});
                    }
                }
            });
        });
    }
});


/* Window load */
$(window).load(function() {
    /* Home Slider */
    $('#home_slider').flexslider();
});