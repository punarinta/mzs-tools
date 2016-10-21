(function ()
{
  var input = document.getElementById('dynamic-in'),
      output = document.getElementById('dynamic-out'),
      translationPicker = document.getElementById('translation-picker');

  input.onkeyup = function ()
  {
    var i, html = '', words = input.value.match(/([a-zA-Z]+)/g);

    for (i in words)
    {
      var word = words[i].toLowerCase();

      html += '<div class="dynamic-word">' + word + '</div>';
    }

    output.innerHTML = html;
  };

  output.onclick = function (e)
  {
    var t = e.target, token = t.innerText.toLowerCase().trim();

    if (t.classList.contains('dynamic-word'))
    {
      if (!t.classList.contains('translated'))
      {
        // TODO: try to make token a vocabulary form

        // make a translation request
        core.api('word', 'translate', {token: token, loose: !document.getElementById('strict-comparison').checked}, function (data)
        {
          t.classList.add('selected');

          var html = '';
          for (var i in data)
          {
            html += '<li><a tabindex="-1" href="#" data-mzs="' + data[i].mzs + '" data-type="' + data[i].type + '">' + data[i].mzs + '</a></li>';
          }

          html += '<li class="cancel divider"></li><li><a tabindex="-1" href="#" class="remove">Remove</a></li><li><a tabindex="-1" href="#" class="cancel">Cancel</a></li>';

          translationPicker.style.display = 'block';
          translationPicker.style.left = e.pageX;
          translationPicker.style.top = e.pageY;
          translationPicker.querySelector('ul').innerHTML = html;
        })
      }
      else
      {
        // make a declension/conjugation request
        core.api('word', 'showForms', {word: t.dataset.mzs, type: t.dataset.type}, function (data)
        {
          console.log(data)
        });
      }
    }
  };

  translationPicker.onclick = function (e)
  {
    translationPicker.style.display = 'none';

    var sel = output.querySelector('.selected');

    if (!e.target.classList.contains('cancel'))
    {
      sel.innerText = e.target.innerText;
      sel.classList.add('translated');
      sel.dataset.mzs = e.target.dataset.mzs;
      sel.dataset.type = e.target.dataset.type;
    }

    if (e.target.classList.contains('remove'))
    {
      sel.parentNode.removeChild(sel);
    }

    // clear item selection
    Array.prototype.forEach.call(output.querySelectorAll('.dynamic-word'), function (el)
    {
      el.classList.remove('selected');
    });
  };

  $(function()
  {
    $('#strict-comparison').bootstrapToggle();
  })
})();
