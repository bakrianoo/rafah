<html>
<head>
  <meta charset="utf-8">
  <title>{{env('APP_NAME')}}</title>
  <script>
    window.opener.postMessage({ token: "{{ $token }}", name: "{{ $name }}" }, "{{env('CLIENT_URL', 'http://localhost/rafah')}}");
    window.close();
  </script>
</head>
<body>
</body>
</html>