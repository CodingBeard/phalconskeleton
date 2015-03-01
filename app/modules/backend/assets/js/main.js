$(function () {
  $(".button-collapse").sideNav();
  $('.collapsible').collapsible({accordion: false});
  $('.dropdown-button').dropdown({
	inDuration: 0,
	outDuration: 225,
	constrain_width: false,
	hover: true,
	alignment: 'left',
	gutter: 0,
	belowOrigin: true
  });
  $('select').material_select(); 
  $('.confirm').click(function(e) {
	e.preventDefault();
	e.stopPropagation();
	if (confirm($(this).attr('data-message'))) {
	  window.location.href = $(this).attr('href');
	}
  });
});