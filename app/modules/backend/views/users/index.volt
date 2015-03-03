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
			  <td>ID</td>
			  <td>Name</td>
			  <td>Email</td>
			  <td>Last Login</td>
			  <td>Roles</td>
			  <td>Action</td>
			</tr>
		  </thead>
		  <tbody>
			{% if users is iterable %}
				{% for user in users %}
					<tr>
					  <td>{{ user.id|e }}</td>
					  <td>{{ user.getName()|e }}</td>
					  <td>{{ user.email|e }}</td>
					  <td>
						{% if user.logins.count() %}
							{{ date(config.defaults.datetimeFormat, user.logins.getLast().attempt) }}
						{% else %}
							Never
						{% endif %}
					  </td>
					  <td>
						| 
						{% if user.roles is iterable %}
							{% for role in user.roles %}
								{{ role.name|e }} |
							{% endfor %}
						{% endif %}
					  </td>
					  <td>
						  <a href="{{ url('admin/users/edit/' ~ user.id) }}" class="btn-tiny blue darken-2">Edit</a>
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