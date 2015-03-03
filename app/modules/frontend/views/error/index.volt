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
    <div class="row">
      <div class="col s12 center">
        <h1>Whoops</h1>

        <div class="section">
          <img class="responsive-img" src="{{ url('img/500.png') }}" alt="500"/>
        </div>
        <hr/>
        <p>Sorry, an error has occured and we are unable to fulfil your request. We keep track of these
          automatically.</p>
      </div>
    </div>
  </div>
{% endblock %}

{% block javascripts %}
  <script type="text/javascript">

  </script>
{% endblock %}

{% block footer %}
  {% include "layouts/footer.volt" %}
{% endblock %}