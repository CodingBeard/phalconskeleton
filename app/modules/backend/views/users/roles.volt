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
		<table class="table bordered striped condensed datatable responsive-table invisible">
		  <thead>
			<tr>
			  <td>Level</td>
			  <td>User Count</td>
			  <td>Name</td>
			  <td>Description</td>
			  <td>Action</td>
			</tr>
		  </thead>
		  <tbody>
			{% if roles is iterable %}
				{% for role in roles %}
					<tr>
					  <td>{{ role.level|e }}</td>
					  <td>{{ role.users.count() }}</td>
					  <td>{{ role.name|e }}</td>
					  <td>{{ role.description|e }}</td>
					  <td>
						  <a href="{{ url('admin/users/editrole/' ~ role.id) }}" class="btn-tiny blue darken-2">Edit</a>
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
		  $('.datatable').DataTable();
		});
	</script>
{% endblock %}

{% block footer %}
	{% include "layouts/footer.volt" %}
{% endblock %}