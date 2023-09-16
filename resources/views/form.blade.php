<div class="d-flex align-items-start">
    <div class="container">
        <div style="text-align: center">
            <h1  href="{{ route('user.create') }}">Tambah user</h1>
        </div>
        <form method="POST" action="{{ route('user.store') }}">
            @csrf   
            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="no_tlp">Nomor Telepon</label>
                <input type="text" name="no_tlp" id="no_tlp" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="tipe">Tipe</label>
                <select name="tipe" id="tipe" class="form-select" required>
                    <option selected>Masukan</option>
                    <option value="S">S</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <br>
            <div style="text-align: center;">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            
        </form>
    </div>
</div>