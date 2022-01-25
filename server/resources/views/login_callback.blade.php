<html>
<head>
  <meta charset="utf-8">
  <title>Callback</title>
  <script>
    window.opener.postMessage({ token: "{{ $token }}", name: "{{ $name }}" }, "{{env('CLIENT_URL', 'http://localhost:8080')}}");
    window.close();
  </script>
</head>
<body>
</body>
</html>