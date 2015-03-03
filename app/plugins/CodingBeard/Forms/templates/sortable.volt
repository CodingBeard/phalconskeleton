{# 
phalconskeleton
author Tim Marshall
copyright (c) 2015, Tim Marshall
#}
<div class="col l{{ size|escape_attr }} m12 s12">
  {% if errorMessage %}
	  <div class="alert alert-danger alert-dismissible">
		{{ errorMessage }}
	  </div>
  {% endif %}
  <table class="table centered bordered striped condensed table-sort-{{ key|escape_attr }} {{ class|escape_attr }}">
	<thead>
	  <tr>
		{% for name in headers %}
			<td>
			  {{ name }}
			</td>
		{% endfor %}
		<td class="center">Delete</td>
		<td class="center">Drag</td>
	  </tr>
	</thead>
	<tbody>
	  {% if options is iterable %}
		  {% for rowId, row in options %}
			  <tr>
				{% for field in row %}
					<td>
					  {{ form.renderField(field) }}
					</td>
				{% endfor %}
				<td><a class="btn btn-small red remove-row" href="#">Delete</a></td>
				<td><i class="mdi-navigation-unfold-more"></i></td>
			  </tr>
		  {% endfor %}
	  {% endif %}
	</tbody>
	<tfoot>
	  <tr>
		<td colspan="{{ headers|length }}" class="center">
		  <a class="btn btn-small green add-new" href="#">Add Row</a>
		</td>
		<td class="center">Delete</td>
		<td class="center">Drag</td>
	  </tr>
	</tfoot>
  </table>
</div>
<script type="text/javascript">
	$(function () {
	  $(".table-sort-{{ key|escape_js }} tbody").sortable({
		helper: function (e, tr) {
		  var $originals = tr.children();
		  var $helper = tr.clone();
		  $helper.children().each(function (index) {
			$(this).width($originals.eq(index).width());
		  });
		  return $helper;
		},
		stop: function (e, ui) {
		  $(".table-sort-{{ key|escape_js }} tbody tr").each(function (rowCount, row) {
			$(this).find('input').each(function () {
			  var name = $(this).attr('name');
			  $(this).attr('name', name.replace(/{{ key|escape_js }}\[\d+\]/, '{{ key|escape_js }}[' + rowCount + ']'));
			});
		  });
		}
	  }).disableSelection();
	  
	  var count = {{ options|length }};
		$('.add-new').click(function (e) {
			e.preventDefault();
			addRow(count);
		});
		$('.remove-row').click(function (e) {
			  e.preventDefault();
			  $(this).parent().parent().remove();
		});

		function addRow(i) {
			var row = "{{ newTextRow }}";
			$('tbody').append(row.replace(/_ROWID_/g, i));

			$('.remove-row').click(function (e) {
			  e.preventDefault();
			  $(this).parent().parent().remove();
			});
			count++;
		  }
	  
	});
</script>