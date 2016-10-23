var translated = [];

(function ()
{
  var input = document.getElementById('dynamic-in'),
      output = document.getElementById('dynamic-out'),
      translationPicker = document.getElementById('translation-picker'),
      wordformPicker = document.getElementById('wordform-picker');

  input.onkeyup = function ()
  {
    var i, html = '', words = input.value.match(/([a-zA-Z]+)/g);

    for (i in words)
    {
      var word = words[i].toLowerCase(), found = -1;

      for (var j = 0; j < translated.length; j++)
      {
        if (translated[j].eng == word)
        {
          found = j;
          break;
        }
      }

      if (found == -1)
      {
        html += '<div class="dynamic-word">' + word + '</div>';
      }
      else
      {
        var t = translated[found];
        html += '<div class="dynamic-word translated" data-eng="' + t.eng + '" data-mzs="' + t.mzs + '" data-type="' + t.type + '">' + t.mzs + '</div>';
      }
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
            var info = data[i].mzs + ' (' + (data[i].type || '?') + ')';
            html += '<li><a tabindex="-1" href="#" data-eng="' + data[i].eng + '" data-mzs="' + data[i].mzs + '" data-type="' + data[i].type + '">' + info + '</a></li>';
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
          t.classList.add('selected');

          var html = '';

          for (var i in data)
          {
            if (typeof data[i] == 'object')
            {
              html += '<li>';
              for (var j in data[i])
              {
                if (typeof data[i][j] == 'object')
                {
                  for (var k in data[i][j])
                  {
                    html += '<a href="#" data-form="' + data[i][j][k] + '">' + data[i][j][k] + ' (' + i + '.' + j + '.' + k + ')</a>';
                  }
                }
                else
                {
                  html += '<a href="#" data-form="' + data[i][j] + '">' + data[i][j] + ' (' + i + '.' + j + ')</a>';
                }
              }
              html += '</li>';
            }
            else
            {
              html += '<li><a href="#" data-form="' + data[i] + '">' + data[i] + ' (' + i + ')</a></li>';
            }
          }

          html += '<li class="cancel divider"></li><li><a tabindex="-1" href="#" class="remove">Remove</a></li><li><a tabindex="-1" href="#" class="cancel">Cancel</a></li>';

          wordformPicker.style.display = 'block';
          wordformPicker.style.left = e.pageX;
          wordformPicker.style.top = e.pageY;
          wordformPicker.querySelector('ul').innerHTML = html;
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
      sel.innerText = e.target.dataset.mzs;
      sel.classList.add('translated');
      sel.dataset.eng = e.target.dataset.eng.replace(/([^a-zA-Z]+)/g, '').toLowerCase();
      sel.dataset.mzs = e.target.dataset.mzs;
      sel.dataset.type = e.target.dataset.type;

      // TODO: do not push duplicates
      translated.push(
      {
        eng: sel.dataset.eng,
        mzs: sel.dataset.mzs,
        type: sel.dataset.type
      });
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

  wordformPicker.onclick = function (e)
  {
    wordformPicker.style.display = 'none';

    var sel = output.querySelector('.selected');

    if (!e.target.classList.contains('cancel'))
    {
      sel.innerText = e.target.dataset.form;
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
