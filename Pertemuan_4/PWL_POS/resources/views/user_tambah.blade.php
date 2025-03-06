<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Update</title>
</head>
<body>
    <h1>Form Tambah Data User</h1>

    <form method="post" action="/PWL/Pertemuan_4/PWL_POS/public/user/tambah_simpan">
        {{ csrf_field() }}
        
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Masukkan Username">

        <label for="nama">Nama</label>
        <input type="text" id="nama" name="nama" placeholder="Masukkan Nama">

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Masukkan Password">

        <label for="level_id">Level ID</label>
        <input type="number" id="level_id" name="level_id" placeholder="Masukkan ID Level">

        <input type="submit" class="btn-success" value="Simpan">
    </form>

</body>
</html>