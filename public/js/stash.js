(function ()
{
  var composer = document.getElementById('composer'),
      composed = localStorage.getItem('composed');

  if (composed)
  {
    composer.value = composed;
  }

  composer.onkeyup = function ()
  {
    localStorage.setItem('composed', this.value);
  };
})();
