$ = jQuery;

$(function() {
	$('li.article-mobile-view').css('cursor', 'pointer').click(function() {
		window.location.href = "#";
		return false;
	});
});
$(document).ready(function() {
	$(".step-info-wrap").click(function(){
		if($(this).parent().hasClass('active')){
			$(this).parent().removeClass('active');
		}
		else {
			$('.col').removeClass('active');
			$(this).parent().addClass('active');
		}
	});

	// Check if user has already agreed and then hide the text.
	if(get_cookie("agree")) {
		$(".cookie-block").hide();
	}

	// Hide Cookies text after user agrees and set cookie.
	$("#hidecookie").click(function () {
		set_cookie("agree", true);
		$(".cookie-block").slideUp("slow");
	});
});

function set_cookie(key, value) {
	var year    = 365 * 24 * 60 * 60 * 1000;
	var expires = new Date();
	expires.setTime(expires.getTime() + year);
	document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
}

function get_cookie(key) {
	var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');	// (document.cookie.match(/^(?:.*;)?\s*agree\s*=\s*([^;]+)(?:.*)?$/)||[,null])[1];
	return keyValue ? keyValue[2] : null;
}

$('.country-select-dropdown').click(function(){
	$(this).parent().toggleClass('active-li');
	$('.country-select-box').toggle();
});

$('.country-select-box .country-list li').click(function(){
	$(this).parents('.country-select-box').find('li').removeClass('active');
	$(this).addClass('active');
});

// Footer JS goes in this
// Header JS goes in this.

// Hide Header on on scroll down
if($( window ).width() <= 768) {
	var didScroll;
	var lastScrollTop = 0;
	var delta = 5;
	var navbarHeight = $('.top-navigation').outerHeight();
	$(window).scroll(function(event){
		didScroll = true;
	});
	setInterval(function() {
		if (didScroll) {
			hasScrolled();
			didScroll = false;
		}
	}, 250);
	function hasScrolled() {
		var st = $(this).scrollTop();
		if(Math.abs(lastScrollTop - st) <= delta)
			return;
		if (st > lastScrollTop && st > navbarHeight){
			$('.top-navigation').removeClass('nav-down').addClass('nav-up');
		} else {
			if(st + $(window).height() < $(document).height()) {
				$('.top-navigation').removeClass('nav-up').addClass('nav-down');
			}
		}
		lastScrollTop = st;
	}
	var $slider = $('.mobile-menus');
	$(document).click(function() {
		if($('.menu').hasClass('active')){
			//Hide the menus if visible
			$slider.animate({
				left: parseInt($slider.css('left'),10) == 0 ?
					-320 : 0
			});
			$('.menu').removeClass('active');
		}
		if($('.search-box').hasClass('active')){
			//Hide the search if visible
			$searchBox.slideToggle().toggleClass('active');;
		}
	});

	$('.menu').click(function() {
		event.stopPropagation();
		$(this).toggleClass('active');
		$slider.animate({
			left: parseInt($slider.css('left'),10) == -320 ?
				0 : -320
		});
	});

	var $searchBox = $('#search .search-box');
	var $searchTrigger = $('#search-trigger');

	$searchTrigger.on('click', function(e) {
		event.stopPropagation();
		$searchBox.slideToggle().toggleClass('active');
	});
}
// Search page.
$(function() {
	var $search_form      = $( '#search_form' );
	var $load_more_button = $( '.btn-load-more' );
	var load_more_count   = 0;

	$( '#search-type button' ).click(function() {
		$( '#search-type button' ).removeClass( 'active' );
		$( this ).addClass( 'active' );
	});

	$( '.btn-filter:not( .disabled )' ).click(function() {
		$( '#filtermodal' ).modal( 'show' );
	});

	// Submit form on Sort change event.
	$( '#select_order' ).off( 'change' ).on( 'change', function() {
		$( '#orderby', $search_form ).val( $( this ).val() ).parent().submit();
		return false;
	});

	// Submit form on Filter click event or on Apply button click event.
	$( 'input[name^="f["]:not(.modal-checkbox), .applybtn' ).off( 'click' ).on( 'click', function() {
		$search_form.submit();
	});

	// Add all selected filters to the form submit.
	$search_form.on( 'submit', function() {
		if ( 0 === $('.filter-modal.show').length ) {
			$( 'input[name^="f["]:not(.modal-checkbox):checked' ).each( function () {
				$search_form.append( $( this ).clone( true ) );
			} );
		} else {
			$( 'input[name^="f["].modal-checkbox:checked').each( function () {
				$search_form.append( $( this ).clone( true ) );
			} );
		}
	});

	// Add filter by clicking on the page type label inside a result item.
	$( '.search-result-item-head' ).off( 'click' ).on( 'click', function() {
		$( '.custom-control-input[value=' + $( this ).data( 'term_id' ) + ']' ).prop( 'checked', true);
		$search_form.submit();
	});

	// Clear single selected filter.
	$( '.activefilter-tag' ).off( 'click' ).on( 'click', function() {
		$( '.custom-control-input[value=' + $( this ).data( 'id' ) + ']' ).prop('checked', false );
		$search_form.submit();
	});

	// Clear all selected filters.
	$( '.clearall' ).off( 'click' ).on( 'click', function() {
		$( 'input[name^="f["]' ).prop( 'checked', false );
		$search_form.submit();
	});

	// Add click event for load more button in blocks.
	$load_more_button.off( 'click' ).on( 'click', function() {
		var $row = $( '.row-hidden', $load_more_button.closest( '.container' ) );

		if ( 1 === $row.size() ) {
			$load_more_button.closest( '.load-more-button-div' ).hide( 'fast' );
		}
		$row.first().show( 'fast' ).removeClass( 'row-hidden' );
	});

	// Reveal more results just by scrolling down the first 2 times.
	$( window ).scroll(function() {
		if ( $load_more_button.length > 0 ) {
			var element_top = $load_more_button.offset().top,
				element_height = $load_more_button.outerHeight(),
				window_height = $(window).height(),
				window_scroll = $(this).scrollTop();

			if (window_scroll > ( element_top + element_height - window_height )) {
				load_more_count++;
				if (load_more_count <= 2) {
					$load_more_button.click();
				}
				return false;
			}
		}
	});
});
// First Index
currentIndex = $('.carousel-item.active').next('.carousel-item').find('img').attr('src');

$('#carousel-wrapper').on('slid.bs.carousel', function () {
	currentIndex = $('.carousel-item.active').next('.carousel-item');
	var e = currentIndex.find('img').attr('src');
	// Last Index
	if(e === 'undefined' || e === undefined) {
		currentIndex = $('.carousel-item').first('.carousel-item').find('img').attr('src');
	} else {
		currentIndex = currentIndex.find('img').attr('src');
	}
	$('a.carousel-control-next').css('background-image', 'url(' + currentIndex + ')');

});

$('a.carousel-control-next').css('background-image', 'url(' + currentIndex + ')');
