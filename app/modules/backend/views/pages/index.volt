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
			  <td>Title</td>
			  <td>Standalone</td>
			  <td>Url</td>
			  <td>Action</td>
			</tr>
		  </thead>
		  <tbody>
			{% if pages is iterable %}
				{% for page in pages %}
					<tr>
					  <td>{{ page.id|e }}</td>
					  <td>{{ page.name|e }}</td>
					  <td>{{ page.title|e }}</td>
					  <td>
						{% if page.standalone %}
							Yes
						{% else %}
							No
						{% endif %}
					  </td>
					  <td>{{ page.url|e }}</td>
					  <td>
						<a class="btn btn-tiny blue darken-2" href="{{ url('admin/pages/edit/' ~ page.id) }}">Edit</a>
						<a class="btn btn-tiny green darken-2" href="{{ url('admin/pages/manage/' ~ page.id) }}">Manage</a>
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