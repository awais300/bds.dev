jQuery(document).ready(function($){
/*
*****************************************************************************************
* Start Search Page scripts
*																						
*****************************************************************************************
*/
	/*live search tirgger*/
	$('#live-search input[name=s]').liveSearch({url: site_url +'/search/?search='});


	/*globals*/
	var searchField = $('#bds-search-input');
	var alt_content = $('#alternate-content');

	/*show/hide the search suggestion and search listing box*/
	$(document).on('keyup', '#bds-search-input', function(e){
		if(alt_content.is(":visible")){
			alt_content.fadeOut(100);
		}
		if($(this).val() == '') {
			$(this).children('ul').remove();
			alt_content.fadeOut(100);
		}
		if(e.keyCode == 13) {
			e.stopPropagation();
			return;
		} else {
			searchField.next().fadeIn(1);
		}
	});


	/*ajax request to get the child search terms - upon clicked on suggestions*/
	$(document).on('click', '.search-term', function(e){
		e.preventDefault();
		disable(searchField);

		var pid = $(this).attr('id');
		var title = $(this).attr('title');

		title = $.trim(title);
		searchField.val(title);
		searchField.next().fadeOut(10); //hide the search dropdown

		$.ajax({
		    'type': 'POST',
		    'url': site_url + '/search',
		    'data': {
		      'pid': pid,
		      'search_term': 'Yes',
		      'ptitle': title
		    },
		    'dataType': 'html',
		    'success': function(data) {
		    	enable(searchField);
		    	if(data != '') {
		    		alt_content.children('ul').remove();
		    		alt_content.fadeIn(100, function(){
		    			alt_content.html(data);
		    		});
		    	} else {

		    	}
		    },
		    'error': function() {
		      alert("System could not process search. Please try again in a moment. Thanks!");
		      window.location = site_url;
		    }
		  });
	});



	/*ajax request to get child terms - upon form submit*/
	$(document).on('submit', '#bds-search', function(e){
		e.preventDefault();
		disable(searchField);
		var alt_content = $('#alternate-content');
		var title = searchField.val();
		title = $.trim(title);
		if(title == ''){
			enable(searchField);
			return;
		}
		searchField.next().fadeOut(10); //hide the search dropdown
		$.ajax({
		    'type': 'POST',
		    'url': site_url + '/search',
		    'data': {
		      'search_term_form': 'Yes',
		      'ptitle': title
		    },
		    'dataType': 'html',
		    'success': function(data) {
		    	enable(searchField);
		    	if(data != '') {
		    		alt_content.children('ul').remove();
		    		alt_content.fadeIn(100, function(){
		    			alt_content.html(data);
		    		});
		    	} else {

		    	}
		    },
		    'error': function() {
		      alert("System could not process search. Please try again in a moment. Thanks!");
		      window.location = site_url;
		    }
		  });
	});

	/*
*****************************************************************************************
* End Search Page scripts
*																						
*****************************************************************************************
*/



/*
*****************************************************************************************
* Start submit product Page scripts
*																						
*****************************************************************************************
*/

//globals
var $ban_product = $('#ban-product');
var $country = $('#country');
var submit_btn = $('#submit-btn');

var $info_message = $('#info-message');
var $alt_cont = $('#alt-container');
var $pp = $('#pp');
var $cp = $('#cp');
var $pid = $('#pid');


/*parent proudct search*/
$ban_product.on('change', function(e) {
	disable(submit_btn);
	changeBtnText(submit_btn, 'Wait...');

	if($.trim($(this).val()) == '') {
		var all = $('.alt-products');
		disable(all);
	} else {
		var all = $('.alt-products');
		enable(all);
	}

	//clear child proudcts fields and notices if parent content is changed
	$('.alt-products').val('');
	$('.child-prod').remove();

	var title = $ban_product.val();
	$.ajax({
	    'type': 'POST',
	    'url': site_url + '/search',
	    'data': {
	      'search_parent': 'Yes',
	      'ptitle': title
	    },
	    'dataType': 'json',
	    'success': function(data) {
	    	if(data != '') {
	    		processParentData(data, $ban_product, $pp, $cp, $pid, submit_btn);
	    	} else {
	    		enable(submit_btn);
				changeBtnText(submit_btn, 'Submit');
	    	}
	    },
	    'error': function() {
	      alert("System error! Please try again in a moment. Thanks!");
	      window.location = site_url;
	    }
	});
});


/*child product search*/
$(document).on('change', '.alt-products', function(e) {
	$this = $(this);
	var title = $ban_product.val();
	var isParent = $pp.val();
	var isChild = $cp.val();
	var parent_id = $pid.val();
	var ctitle = $this.val();

	if($.trim(isParent) == 'Yes' && $.trim(isChild) == 'Yes') {
		;
	} else {
		return;
	}

	disable(submit_btn);
	changeBtnText(submit_btn, 'Wait...');

	$.ajax({
	    'type': 'POST',
	    'url': site_url + '/search',
	    'data': {
	      'search_child': 'Yes',
	      'ptitle': title,
	      'ctitle': ctitle,
	      'pid' : parent_id
	    },
	    'dataType': 'json',
	    'success': function(data) {
	    	if(data != '') {
	    		processChildData(data, $this, submit_btn);
	    	} else {
	    		enable(submit_btn);
				changeBtnText(submit_btn, 'Submit');
	    	}
	    },
	    'error': function() {
	      alert("System error! Please try again in a moment. Thanks!");
	      window.location = site_url;
	    }
	});
});


/*submit products*/
$(document).on('submit', '#submit-product', function(e) {
	e.preventDefault();
	$this = $(this);
	formData = $this.serialize();
	var buycott = $.trim($ban_product.val());
	var country = $.trim($country.val());

	

	
	if(buycott == '') {
		showError('buycott', $info_message);
		return;
	}

	if(country == '') {
		showError('country', $info_message);
		return;
	}

	//check Fields and Notices are same in length or not
	$f = $('.alt-products');
	$n = $('.child-prod');
	if($f.length == $n.length){
		showError('length', $info_message);
		return;
	}

	disable(submit_btn);
	changeBtnText(submit_btn, 'Submitting...');

	$notices = $('.buycott');
	$newinputs = $('input.new');
	$newinputs.next('span').remove();
	$notices.remove();

	$.ajax({
	    'type': 'POST',
	    'url': site_url + '/search',
	    'data': formData + "&submit_product=Yes",
	    'dataType': 'json',
	    'success': function(data) {
	    	if(data.message != '' && 0) {
	    		showError('required', $info_message);
	    	} else {
	    		showError('success', $info_message);
	    	}

	    	document.getElementById('submit-product').reset();
	    	enable(submit_btn);
			changeBtnText(submit_btn, 'Submit');
	    },
	    'error': function() {
	      //alert("System error! Please try again in a moment. Thanks!");
	      //window.location = site_url;
	    }
	});
});

var count = 0;
$('#addnew').on('click', function(e){
	e.preventDefault();
	if($.trim($ban_product.val()) == '') {
		return;
	}

	count++;
	if(count < 5) {
		html = '<input type="text" name="alt-product[]" focus class="alt-products new" /><span><a href="#" class="remove">Remove this</a></span>';
		$alt_cont.append(html);
	}
});

$(document).on('click', 'a.remove', function(){
	count--;
	$this = $(this);
	$this.parent().prev().remove();
	if($this.parent().next().hasClass('child-prod')) {
			$this.parent().next().remove();
	}
	$this.parent().remove();
});

/*
*****************************************************************************************
* End submit product Page scripts
*																						
*****************************************************************************************
*/

}); //document.ready



/**
 * Scroll up to the given elemeent (ele)
 * @param  string ele [ele is in the form of '#div-name']
 */
function scrollTo(ele)
{
	if (ele instanceof jQuery) {
		$("body, html").animate({
    	scrollTop: ele.offset().top 
        }, 600);

	} else {
		$("body, html").animate({
    	scrollTop: $(ele).offset().top 
        }, 600);
	}
}


/*disable an element using html disabled property*/
function disable($ele)
{
	$ele.attr("disabled","disabled");
}


/*enable an element using by removing disabled property*/
function enable($ele)
{
	$ele.removeAttr("disabled");
}

function changeBtnText(ele, text) {
	ele.val(text);
}

/**
 * process JSON data, extrat html|message|parent_id and insert after ele
 * @param  JSON data
 * @param  jQuery ele
 * @paream jQuery $pp
 * @paream jQuery $pcp
 * @paream jQuery $pid
 */
function processParentData(data, ele, $pp, $cp, $pid, btn) {
	if($.trim(data.has_data) == 'Yes')	{
		if(ele.next().hasClass('buycott')) {
			ele.next().remove();
		}
		ele.after(data.message);
		ele.next().slideDown(200, function(){
			enable(btn);
			changeBtnText(btn, 'Submit');
		});

		$pp.val('Yes');
		$cp.val(data.has_child);
		$pid.val(data.parent_id);

	} else {
		if(ele.next().hasClass('buycott')) {
			ele.next().slideUp(200, function () {
				ele.next().remove();
				enable(btn);
				changeBtnText(btn, 'Submit');
			});
		}
		enable(btn);
		changeBtnText(btn, 'Submit');

		$pp.val('No');
		$cp.val(data.has_child);
		$pid.val(data.parent_id);
	}
}


/**
 * process JSON data, extrat message and insert after ele
 * @param  JSON data
 */
function processChildData(data, ele, btn) {
	if($.trim(data.message) != '') {
		if(ele.next().next().hasClass('child-prod')) {
			ele.next().next().remove();
		} 
		ele.next().after(data.message);
		ele.next().next().slideDown(200, function(){
			enable(btn);
			changeBtnText(btn, 'Submit');
		});
	} else if(ele.next().next().hasClass('child-prod')) {
			ele.next().next().slideUp(200, function () {
				ele.next().next().remove();
				enable(btn);
				changeBtnText(btn, 'Submit');
			});
			enable(btn);
			changeBtnText(btn, 'Submit');
	  } else {
	  	enable(btn);
		changeBtnText(btn, 'Submit');
	  }
}

/**
 * show error
 * @param  string $key
 */
function showError(missed, ele) { 
	html = '';
	if(missed == 'buycott') {
		html = '<p class="buycott">Please enter buycott product/brand name.<p/>';
		ele.html(html);
	}

	if(missed == 'country') {
		html = '<p class="buycott">Please select country.<p/>';
		ele.html(html);
	}

	if(missed == 'required') {
		html = '<p class="buycott">Please complete the required fields.<p/>';
		ele.html(html);
	}

	if(missed == 'success') {
		var c = $('#country').val();
		html = '<p class="buycott success-msg">Thank you for your participation. ' +c+' should feel proud. :)<p/>';
		ele.html(html);
	}

	if(missed == 'length') {
		html = '<p class="buycott">All provided alternate proudcts already exist, please provide atleast one different. Thanks!<p/>';
		ele.html(html);
	}
	setTimeout(hideNotice, 5000, ele);
	scrollTo(ele);
}

function hideNotice(ele) {
	ele.children('p').fadeOut(3000);
}