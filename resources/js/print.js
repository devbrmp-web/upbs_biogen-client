document.addEventListener('DOMContentLoaded', function() {
  var btn = document.getElementById('btn-print');
  if (!btn) return;
  btn.addEventListener('click', function() {
    var code = btn.getAttribute('data-order-code');
    if (!code) return;
    var url = '/pesanan/' + encodeURIComponent(code) + '/receipt';
    window.open(url, '_blank');
  });
});
