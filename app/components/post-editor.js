const MediumEditor = require('medium-editor/dist/js/medium-editor')
import TurndownService from 'turndown'
import autosize from 'autosize'


var editor = new MediumEditor('.editable', {
  toolbar: {
    buttons: [
      'bold',
      'italic',
      'underline',
      'anchor',
      'h1',
      'h2',
      'h3',
      'quote',
      'justifyLeft',
      'justifyCenter',
      'justifyFull'
    ],
    allowMultiParagraphSelection: false,
  },
    anchor: {
        placeholderText: 'Escriba la URL',
    },
  placeholder: {
    /* This example includes the default options for placeholder,
       if nothing is passed this is what it used */
    text: 'Escribí algo de la foto =)',
    hideOnClick: true
  }
});

window.changeCover = function () {
  console.log('called!')
  var file = document.getElementById("file-cover");
  if (file.files.length > 0) {
    document
      .getElementById('filename-cover')
      .innerHTML = file
        .files[0]
        .name;
  }
  var reader = new FileReader();
  reader.onloadend = function () {
    document
      .getElementById('header-post-cover')
      .setAttribute('style', 'background-image: url("' +
        reader.result +
        '")');
  }
  if (file.files[0]) {
    reader.readAsDataURL(file.files[0]);
  } else {

  }
};

window.submitPost = function () {
  var turndownService = new TurndownService()
  var markdown = turndownService.turndown(editor.getContent())
  console.log(markdown)
  document.getElementById('input-post-cuerpo').value = markdown
  document.getElementById('form-post').submit()
}

autosize(document.getElementsByClassName('autosize-area'));
