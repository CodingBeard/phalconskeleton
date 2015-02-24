<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	{% block head %}
	{% endblock %}
  </head>
  <body itemscope itemtype="http://schema.org/EmailMessage">
	<table class="body-wrap">
	  <tr>
		<td></td>
		<td class="container">
		  <div class="content">
			<table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope itemtype="http://schema.org/ConfirmAction">
			  <tr>
				<td class="header">
				  {% block header %}
				  {% endblock %}
				</td>
			  </tr>
			  <tr>
				<td class="content-wrap">
				  {% block content %}
				  {% endblock %}
				</td>
			  </tr>
			</table>
			<div class="footer">
			  {% block footer %}
			  {% endblock %}
			</div>
		  </div>
		</td>
		<td></td>
	  </tr>
	</table>
  </body>
</html>
{% block text %}
{% endblock %}