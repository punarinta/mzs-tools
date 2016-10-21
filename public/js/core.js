var core =
{
  isJson: function (testable)
  {
    return testable && /^[\],:{}\s]*$/.test(testable.replace(/\\["\\\/bfnrtu]/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''));
  },

  api: function (endpoint, method, data, callback, error)
  {
    var r = new XMLHttpRequest(), ps = null, pl;

    r.open('POST', '/api/' + endpoint, true);
    r.setRequestHeader('Content-Type', 'application/json; charset=utf-8');

    r.onload = function ()
    {
      var r = this.response.toString();
      if (core.isJson(r))
      {
        var json = JSON.parse(r);
        if (this.status >= 200 && this.status < 400)
        {
          callback(json);
        }
        else
        {
          console.log('Status:', this.status);
        }
      }
      else
      {
        console.log('Not JSON:', this.response, this.getAllResponseHeaders());
      }
    };
    r.onerror = function(e)
    {
      console.log('onerror()', e.error);
      if (error) error(e.error)
    };

    if (data && typeof data.pageStart != 'undefined')
    {
      ps = data.pageStart;
      pl = data.pageLength;
    }

    r.send(JSON.stringify({ 'method': method, 'data': data, 'pageStart': ps, 'pageLength': pl }));
  },

  getQueryVar: function (v)
  {
    var q = window.location.search.substring(1),
        i, p, vs = q.split('&');

    for (i = 0; i < vs.length; i++)
    {
      p = vs[i].split('=');
      if (p[0] == v) return p[1];
    }
    return null;
  },

  ts: function (ts, mode)
  {
    mode = mode || 3;

    var td = new Date(), pfx = '',
        date = new Date(ts * 1000), gy = date.getYear(),
        year = gy >= 100 ? gy - 100 : gy,
        month = '0' + (date.getMonth() + 1),
        day = '0' + date.getDate(),
        hours = '0' + date.getHours(),
        minutes = '0' + date.getMinutes();

    // noinspection JSBitwiseOperatorUsage
    if (mode & 1)
    {
      if (td.getTime() - date.getTime() < 24 * 3600 && td.getDate() == date.getDate()) pfx = 'today';
      else pfx = day.substr(-2) + '.' + month.substr(-2) + '.' + year;
      pfx += ' '
    }

    // noinspection JSBitwiseOperatorUsage
    if (mode & 2)
    {
      pfx += ' ' + hours.substr(-2) + ':' + minutes.substr(-2);
    }

    return pfx;
  },

  uniques: function (arr, sens)
  {
    var i = 0, a = [], l = arr.length;
    if (sens)
    {
      for (; i<l; i++)
        if (a.indexOf(arr[i]) === -1 && arr[i] !== '')
          a.push(arr[i]);
    }
    else
    {
      for (; i<l; i++)
        if (a.indexOf(arr[i].toLowerCase()) === -1 && arr[i] !== '')
          a.push(arr[i].toLowerCase());
    }
    return a;
  },

  load: function (fn)
  {
    if (typeof ML._loaded[fn] != 'undefined') return;
    ML._loaded[fn] = 1;
    var f = document.createElement('script');
    f.setAttribute('type', 'text/javascript');
    f.setAttribute('src', '/' + fn + '.js');
    document.querySelector('head').appendChild(f)
  },


  unpush: function (array, index)
  {
    var rest = array.slice(index + 1 || array.length);
    this.length = index < 0 ? array.length + index : index;
    return array.push.apply(array, rest);
  },

  isEmail: function (email)
  {
    var r = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return r.test(email);
  },

  // find first parent of the specified type
  par: function (x, type)
  {
    while (x && x.nodeName.toLowerCase() != type)
    {
      if (x == document) break;
      x = x.parentElement;
    }
    return x;
  }
};

