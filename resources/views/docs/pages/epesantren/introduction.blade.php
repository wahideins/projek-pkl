@guest
<div class="contents">
    <p>Selamat Datang</p>
</div>
@endguest

@auth
<div class="menuid">
    </div>
    <div class="main-container">
        <div class="editor-container" id="editor-container">
            <form>

                <textarea name="content" id="editor" class="ckeditor">              
                    <p>Selamat Datang</p>
                </textarea>

        </form>
    </div>
    <div class="buttons">   
        <button class="btn btn-simpan"><a href="#">Simpan</a></button>
        <button class="btn btn-batal"><a href="#">Batal</a></button>
        <button class="btn btn-hapus"><a href="#">Hapus</a></button>
    </div>
</div>
@endauth