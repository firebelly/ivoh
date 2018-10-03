<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
  <link href="https://fonts.googleapis.com/css?family=Libre+Baskerville:400,400i,700" rel="stylesheet">
  <!-- Inject SVGs Defs for SVG icon use -->
  <script>
    var ajax = new XMLHttpRequest();
    ajax.open("GET", "<?= get_stylesheet_directory_uri(); ?>/dist/svgs/build/svgs-defs.svg", true);
    ajax.send();
    ajax.onload = function(e) {
      var div = document.createElement("div");
      div.className += "svg-defs hidden";
      div.innerHTML = ajax.responseText;
      document.body.insertBefore(div, document.body.childNodes[0]);
    }
  </script>
</head>
