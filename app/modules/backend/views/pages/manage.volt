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
	{%- macro getPanel(page, content) %}
	{% if content.children.count() %}
		<div class="col s{{ content.width }} offset-s{{ content.offset }}">
		  <div class="card-panel white">
			<input type="hidden" name="ordering[]" value="{{ content.id }}" />
			<a href="#" data-selectID="{{ content.id }}" class="green-text selectable left">Select</a>
			<a href="#" class="right"><i  class="fa fa-arrows-h"></i></a>
			<div class="row">
			  <div class="center"><strong>ID {{ content.id }}</strong></div>
			  <div class="divider"></div>
			  <div class="sortable">
				{% for child in content.getChildren(['order': 'ordering']) %}
					{{ getPanel(page, child) }}
				{% endfor %}
			  </div>
			</div>
			<div class="divider"></div>
			<div class="center">
			  <a href="/admin/pages/content/{{ content.id }}" class="blue-text edit-section left" target="_blank">Edit</a>
			  <a href="/admin/pages/emancipate/{{ content.id }}" data-message="Are you sure you want to remove this as a child?" class="confirm red-text delete-section">Emancipate</a>
			  <a href="/admin/pages/deletecontent/{{ content.id }}" data-message="Are you sure you want to delete that?" class="confirm red-text delete-section right">Delete</a>
			</div>
		  </div>
		</div>
	{% else %}
		<div class="col s{{ content.width }} offset-s{{ content.offset }}">
		  <div class="card-panel white">
			<input type="hidden" name="ordering[]" value="{{ content.id }}" />
			<a href="#" data-selectID="{{ content.id }}" class="green-text selectable left">Select</a>
			<a href="#" class="right"><i  class="fa fa-arrows-h"></i></a>
			<div class="center"><strong>ID {{ content.id }}</strong></div>
			<div class="divider"></div>
			<p>{{ content.content }}</p>
			<div class="divider"></div>
			<div class="center">
			  <a href="/admin/pages/content/{{ content.id }}" class="blue-text edit-section left" target="_blank">Edit</a>
			  <a href="/admin/pages/emancipate/{{ content.id }}" data-message="Are you sure you want to remove this as a child?" class="confirm red-text delete-section">Emancipate</a>
			  <a href="/admin/pages/deletecontent/{{ content.id }}" data-message="Are you sure you want to delete that?" class="confirm red-text delete-section right">Delete</a>
			</div>
		  </div>
		</div>
	{% endif %}
	{%- endmacro %}

	<div id="flash-container">
	  {{ flashSession.output() }}
	</div>
	<div class="container">
	  <div class="row">
		<div class="col s12 center">
		  <h3>Manage Page: {{ page.name|e }}</h3>
		</div>
	  </div>
	</div>
	<div class="row">
	  <div class="col s12">
		<div class="designer">
		  <a href="#" class="green-text selectable left" data-selectID="0">Select</a>
		  <div class="row">
			<div class="center"><strong>ID 0</strong></div>
		  </div>
		  <div class="sortable row">
			{% if page.contents is iterable %}
				{% for content in page.getContents(['parent_id IS NULL', 'order': 'ordering']) %}
					{{ page.newRow(content) }}
					{{ getPanel(page, content) }}
					{{ page.endRow(content) }}
				{% endfor %}
			{% endif %}
		  </div>
		</div>
	  </div>
	</div>
	<div class="row">
	  <div class="col s12">
		<h5>Add section to: ID <span class="selected-section">0</span></h5>
		{{ form.getHtml() }}
	  </div>
	</div>
{% endblock %}

{% block javascripts %}
	<script type="text/javascript">
		$(function () {
	  {{ form.getJs() }}
			$('.selectable').click(function (e) {
			  e.preventDefault();
			  $('.selected-section').html($(this).attr('data-selectID'));
			  $('.selectable').parent().css({"border": "2px transparent dashed"});
			  $(this).parent().css({"border": "2px black dashed"});
			  $('input[name="parent_id"]').val($(this).attr('data-selectID'));
			});
			$(".sortable").sortable({
			  helper: function (e, tr) {
				var $originals = tr.children();
				var $helper = tr.clone();
				$helper.children().each(function (index) {
				  $(this).width($originals.eq(index).width());
				});
				return $helper;
			  },
			  stop: function (e, ui) {
				var order = [];
				$('input[name="ordering[]"]').each(function (count) {
				  order[count] = $(this).val();
				});
				$.ajax({
				  type: 'POST',
				  url: '/admin/pages/reorder/{{ page.id }}',
				  data: {'ordering[]': order},
				  dataType: "json"
				});
			  }
			}).disableSelection();
		  });
	</script>
{% endblock %}

{% block footer %}
	{% include "layouts/footer.volt" %}
{% endblock %}