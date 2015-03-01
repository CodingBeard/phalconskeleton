{# 
phalconskeleton
author Tim Marshall
copyright (c) 2015, Tim Marshall
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
			  <td>Action</td>
			</tr>
		  </thead>
		  <tbody>
			{% if navbars is iterable %}
				{% for navbar in navbars %}
					<tr>
					  <td>{{ navbar.id|e }}</td>
					  <td>{{ navbar.name|e }}</td>
					  <td>
						<a class="btn btn-tiny blue darken-2" href="{{ url('admin/navbars/edit/' ~ navbar.id) }}">Edit</a>
						<a class="btn btn-tiny blue darken-2" href="{{ url('admin/navbars/manage/' ~ navbar.id) }}">Manage</a>
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