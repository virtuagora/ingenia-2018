# Pagebrick: Simple text
---

The `simple-text` pagebrick is one of the bricks that are used to build static pages inside the platform.
Inside a `twig` template, the brick is included and it renders a `<section>` with all the information that is passed through the passing them after the with keyword
```twig
    {% if brick.type == 'simple-text' %}
        {% include 'pagebricks/simple-text.twig' with brick only %}
    {% elseif ... %}
        ...
    {% endif %}
```

Here is the available API for `simple-text` pagebrick
```json
{  
    "type": "simple-text",
    "order": Number,
    "size": String,
    "colorText": ColorHex,
    "background-image": Url || null,
    "backgroundColor": ColorHex || null,
    "content":
        {
        "title": String,
        "subtitle": String || null,
        "body": MarkdownText,
        },
    "actionButtons":
        [
            {
            "label": String,
            "href": URL
            },
            ...
        ]
 }
```

### Example

A working example

```json
{  
    "type": "simple-text",
    "order": 2,
    "size": "large",    
    "colorText": "FFFFFF",
    "backgroundColor": null,
    "backgroundImage": "http://lorempixel.com/g/600/200/" ,
    "content":
        {
        "title": "This brick is amazing, wanna know why?",
        "subtitle": "Cause it's amazing, that is why",
        "body": "Here is some lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque scelerisque, ante quis cursus viverra, ligula ligula accumsan libero, ac finibus magna sapien ut massa. Praesent leo enim, condimentum sit amet efficitur eu, ullamcorper ut sapien. Vivamus vel urna ut ante lobortis efficitur. Aenean ut urna risus. Aenean vestibulum volutpat vestibulum.",
        },
    "actionButtons":
        [
            {
            "label": "Go search it!",
            "href": "https://duckduckgo.com/"
            },
            {
            "label": "Know more...",
            "href": "/testing"
            },
            ...
        ]
 }
```