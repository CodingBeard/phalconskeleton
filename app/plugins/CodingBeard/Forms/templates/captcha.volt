{# 
phalconskeleton
author Tim Marshall
copyright (c) 2015, Tim Marshall
#}
{% if (guestsOnly is false) or (guestsOnly is true and auth is false) %}
  <div class="col l12 m12 s12">
    {% if errorMessage %}
      <div class="alert alert-danger alert-dismissible">
        {{ errorMessage }}
      </div>
    {% endif %}
    {{ captcha.getHtml() }}
  </div>
{% endif %}