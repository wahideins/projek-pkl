<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
		<link rel="stylesheet" href="{{ asset('ckeditor/style.css') }}">
		<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/45.2.1/ckeditor5.css" crossorigin>
	</head>
    <style>
        .buttons{
            min-width: 100%;
            margin: 10px;
            display: flex;
            padding: 10px;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .btn{
            padding: 12px;
            border-radius: 8px;
        }
        .btn-simpan{
            background-color: #45a65a;
            color: white;
        }

        .btn-batal{
            background-color: #00c0ef;
            color: white;
        }
        .btn-hapus{
            background-color: red;
            color: white;
        }
    </style>
	<body>
		<div class="main-container">
			<div
				class="editor-container editor-container_classic-editor editor-container_include-style editor-container_include-fullscreen"
				id="editor-container">
				<div class="editor-container__editor">
                    <form action="">
                        <textarea name="content" id="editor"></textarea>
                    </form>
                </div>
			</div>
            <div class="buttons">   
                <button class="btn btn-simpan"><a href="#">Simpan</a></button>
                <button class="btn btn-batal"><a href="#">Batal</a></button>
                <button class="btn btn-hapus"><a href="#">Hapus</a></button>
            </div>
		</div>
		<script src="https://cdn.ckeditor.com/ckeditor5/45.2.1/ckeditor5.umd.js" crossorigin></script>
		<script src="{{ asset('ckeditor/main.js') }}"></script>
	</body>
</html>