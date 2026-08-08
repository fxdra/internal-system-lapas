<!DOCTYPE html>
<html>
<head>
    <title>Import Data WBP</title>
</head>
<body>

<h3>Import Excel Data WBP</h3>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p style="color:red">{{ session('error') }}</p>
@endif

<form method="POST" action="/import-wbp" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file_excel" required>
    <button type="submit">Import</button>
</form>

</body>
</html>
