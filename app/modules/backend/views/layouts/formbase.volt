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
  <div class="container">
    <div id="flash-container">
      {{ flashSession.output() }}
    </div>
    {{ form.getHtml() }}
  </div>
{% endblock %}

{% block javascripts %}
  <script type="text/javascript">
    $(function () {
      {{ form.getJs() }}
    });
  </script>
{% endblock %}

{% block footer %}
  {% include "layouts/footer.volt" %}
{% endblock %}