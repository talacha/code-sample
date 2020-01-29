# My Code Sample

## Introduction
First of all thank you for taking the time to review my code. I apologize, that I had to strip a lot
for compliance. Stripping so many things may render some of these things not functional.
The purpose of the remaining pieces of code is to show code style, best practices and such.

Having said that, I would like to call out a few interesting pieces to assist you in the process.

## [Node Embed Block](https://github.com/talacha/code-sample/blob/master/cs_node_embed/src/Plugin/Block/NodeEmbedBlock.php "Node Embed Block")

Highlights the mastery of dependency injection, plugin system and using and extending the Drupal API.

This comes with a [blog post](http://rob.cr/blog/how-create-custom-block-embeds-nodes-drupal-8) that explains it bit by bit.

## Theme

Lots of cool things in here, but one place in particular to demo mastery of twig is group-members-view (https://github.com/talacha/code-sample/tree/master/cs_theme_sample/source/_patterns/02-molecules/group-members-view)

Notice the use of the demo folder. Notice how we cap tags to 10:

```twig
{# Product Area #}
  <div class="column products">
    {% if row.products %}
      {% for tag in row.products|slice(0, 10) %}
        {% include '@atoms_reborn/tags/_tags.twig' with {'tag': tag} %}
      {% endfor %}
      {% if row.products|length > 10 %}<span class="more">+10</span>{% endif %}
    {% endif %}
  </div>
```


