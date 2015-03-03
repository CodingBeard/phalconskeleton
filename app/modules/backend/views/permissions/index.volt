{# 
@package: phalconskeleton
@author: Tim Marshall <Tim@CodingBeard.com>
@copyright: (c) 2015, Tim Marshall
@license: New BSD License
#}
{% extends 'layouts/master.volt' %}

{% block head %}

{% endblock %}

{% block header %}
	{% include "layouts/header.volt" %}
	{% include "layouts/navigation.volt" %}
{% endblock %}

{% block content %}
	<div id="flash-container">
	  {{ flashSession.output() }}
	</div>
	<div class="row">
	  <div class="col s12">
		<table class="table centered bordered striped condensed responsive-table datatable invisible">
		  <thead>
			<tr>
			  <td class="search center">Module</td>
			  <td class="search center">Controller</td>
			  <td class="search center">Action</td>
			  <td>Access</td>
			  <td>Actions</td>
			</tr>
		  </thead>
		  <tbody>
			{% if permissions is defined %}
				{% for permission in permissions %}
					<tr>
					  <td>{{ permission.module|e }}</td>
					  <td>{{ permission.controller|e }}</td>
					  <td>{{ permission.action|e }}</td>
					  <td>
						<form class="update-permission" method="POST" action="/admin/permissions/set/{{ permission.id }}">
						  <ul class="roles">
							{% for role in permission.getRoles(['order': 'models\Roles.id']) %}
								<li>{{ role.name }}</li>
								{% endfor %}
						  </ul>
					  </td>
					  <td>
						<input title="{{ permission.id }}" type="submit" class="btn btn-tiny blue darken-2" value="Update">
						</form>
					  </td>
					</tr>
				{% endfor %}
			{% endif %}
		  </tbody>
		</table>
	  </div>
	</div>
{% endblock %}

{% block javascripts %}
	<script type="text/javascript">
		$(function () {
		  var tagValues = {{ json_encode(tagValues) }};
		  var tagLabels = {{ json_encode(tagLabels) }};
		  $('.roles').tagit({
			availableTags: tagLabels,
			showAutocompleteOnFocus: true,
			removeConfirmation: true,
			beforeTagAdded: function (event, ui) {
				if ($.inArray(ui.tagLabel, tagLabels) === -1) {
					return false;
				}
			},
			afterTagAdded: function (event, ui) {
				$(this).append('<input type="hidden" value="' + tagValues[ui.tagLabel] + '" name="roles[]" class="roleIDs">');
			},
			afterTagRemoved: function (event, ui) {
				$('.roleIDs[value="' + tagValues[ui.tagLabel] + '"]').remove();
			}
		  });

		  $('.update-permission').submit(function (e) {
			e.preventDefault();
			$.post($(this).attr('action'), $(this).serialize())
					.success(function (data) {
					  var response = $.parseJSON(data);
					  if (response.status === 'OK') {
						$('#flash-container').html('<div class="alert alert-success">' + response.message + '</div>');
					  }
					  else {
						$('#flash-container').html('<div class="alert alert-warning">' + response.message + '</div>');
					  }
					}).fail(function () {
			  $('#flash-container').html('<div class="alert alert-warning">Ajax error.</div>');
			});
		  });

		  var table = $('.datatable').DataTable({
			"order": [[0, 'asc'], [1, 'asc']],
			"ordering": false
		  });
		  $('.dataTables_filter').remove();

		  $('.datatable .search').each(function () {
			var title = $('.datatable .search').eq($(this).index()).text();
			$(this).html('<input class="thead-search-input" type="text" placeholder="' + title + '" />');
		  });

		  table.columns().eq(0).each(function (colIdx) {
			$('input', table.column(colIdx).header()).on('keyup change', function () {
			  table.column(colIdx).search(this.value).draw();
			});
		  });
		});
	</script>
{% endblock %}

{% block footer %}
	{% include "layouts/footer.volt" %}
{% endblock %}