{# 
QUKsite
author Tim Marshall
copyright (c) 2014, Tim Marshall
#}
{% if captcha is true %}
	<div class="col l12 m12 s12">
	  <div class="section">
		{{ captcha.getHtml() }}
	  </div>
	</div>
{% endif %}