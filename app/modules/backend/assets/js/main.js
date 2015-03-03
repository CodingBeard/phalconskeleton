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
  $('.confirm').click(function (e) {
    e.preventDefault();
    e.stopPropagation();
    if (confirm($(this).attr('data-message'))) {
      window.location.href = $(this).attr('href');
    }
  });
  $('li > a').each(function () {
    if (window.location.pathname === $(this).attr('href')) {
      $(this).parent().addClass('active');
    }
  });
  $('a[data-id="' + $('a[data-group]').attr('data-group') + '"]').click();
  $('li[data-id="' + $('a[data-parent]').attr('data-parent') + '"]').addClass('active');
});