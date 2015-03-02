{# 
phalconskeleton
author Tim Marshall
copyright (c) 2015, Tim Marshall
#}
<style type="text/css">
  #editor {
	  margin: 0;
	  position: absolute;
	  top: 170px;
	  bottom: 100px;
	  left: 250px;
	  right: 10px;
  }
  .form-footer {
	  position: absolute;
	  bottom: 20px;
  }
  .form-footer a {
	  position: relative;
	  left: 400%;
  }
</style>
<pre id="editor" name="{{ key|escape_attr }}">{{ default|e }}</pre>
<script type="text/javascript">
	function inject(options, callback) {
	  var baseUrl = options.baseUrl || "{{ url('js/ace/') }}";

	  var load = function (path, callback) {
		var head = document.getElementsByTagName('head')[0];
		var s = document.createElement('script');
		s.src = baseUrl + "/" + path;
		head.appendChild(s);
		s.onload = s.onreadystatechange = function (_, isAbort) {
		  if (isAbort || !s.readyState || s.readyState == "loaded" || s.readyState == "complete") {
			s = s.onload = s.onreadystatechange = null;
			if (!isAbort)
			  callback();
		  }
		};
	  };
	  load("ace.js", function () {
		ace.config.loadModule("ace/ext/settings_menu", function () {
			
		  callback && callback();
		});
	  });
	}

	var editor;
	inject({}, function () {
	  editor = ace.edit("editor");
	  editor.setTheme("ace/theme/dawn");
	  editor.getSession().setMode("ace/mode/php");
	});

	$('#submit').click(function (e) {
	  e.preventDefault();
	  e.stopPropagation();
	  $.post(window.location, {'{{ key }}': editor.getValue()})
			  .done(function (data) {
				var response = JSON.parse(data);
				console.log(response);
				if (response.status === 1) {
				  window.location.href = response.redirect;
				}
			  });
	});
</script>