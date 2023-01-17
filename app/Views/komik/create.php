<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container">
    <div class="col">
        <div class="row">
            <h2 class="my-3">Form Tambah Data Komik</h2>
        </div>
        <div class="row">
            <!-- simpan data dan validasi -->
            <form action="/komik/save" method="post" enctype="multipart/form-data">
                <!-- untuk menjaga agar input dari form ini aja -->
                <?= csrf_field(); ?>
                <div class="row mb-3">
                    <label for="judul" class="col-sm-2 col-form-label">Judul</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control <?= ($validation->hasError('judul')) ? 'is-invalid' : ''; ?>" 
                    id="judul" autofocus name="judul" value ="<?= old('judul'); ?>">
                    <div class="invalid-feedback" >
                        <?= $validation->getError('judul'); ?>
                    </div>
                    </div>
                </div>
                <!-- old('judul), agar field sudah diisi tidak hilang jika error judul-->
                <div class="row mb-3">
                    <label for="penulis" class="col-sm-2 col-form-label">Penulis</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" id="penulis" name="penulis" value ="<?= old('penulis'); ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="penerbit" class="col-sm-2 col-form-label">Penerbit</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" id="penerbit" name="penerbit" value ="<?= old('penerbit'); ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="sampul" class="col-sm-2 col-form-label" >Sampul</label>
                    <div class="col-sm-2">
                        <img src="/img/default.png" class="img-thumbnail img-preview">
                    </div>
                    <div class="col-sm-8">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input <?= ($validation->hasError('sampul')) ? 'is-invalid' : ''; ?>" id="sampul" 
                        name="sampul" onchange="previewImg()">
                        <div class="invalid-feedback" >
                            <?= $validation->getError('sampul'); ?>
                        </div>
                        <label class="custom-file-label" for="sampul">Pilih gambar</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Tambah data</button>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>