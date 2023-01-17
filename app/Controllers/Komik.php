<?php

namespace App\Controllers;
use App\Models\KomikModel;

class Komik extends BaseController
{
    protected $komikModel;
    public function __construct()
    {   
        // KomikModel
        $this->komikModel = new KomikModel();
    }
    public function index()
    {
        // SELECT * FROM
        // $komik = $this->komikModel -> findAll();
        $data = [
            'title' => 'Daftar Komik',
            'komik' =>  $this->komikModel->getKomik()
        ];

        // //  Cara konek db tanpa model
        // $db = \Config\Database::connect();
        // $komik = $db->query("SELECT * FROM komik");
        // foreach($komik->getResultArray()as $row){
        //     d($row);
        // }
            // instansasi
        // $komikModel = new \App\Models\KomikModel();


        return view('komik/index', $data);
    }

    public function detail($slug)
    {
        $komik = $this->komikModel->getKomik($slug);
        $data = [
            'title' => 'Detail Komik',
            'komik'=> $this->komikModel->getKomik($slug)
        ];

        // jika komik tidak ada di dat
        if(empty($data['komik'])){
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Judul komik '.$slug.' tidak ada');
        }
        return view('komik/detail', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Form tambah data komik',
            // 'komik'=> $this->komikModel->getKomik($slug)
            'validation' => \Config\Services::validation()
        ];

        return view('komik/create', $data);
    }

    // kelola data untuk insert ke database
    public function save()
    {   
        // validasi input
        if(!$this->validate([
            'judul' => [
                'rules' => 'required|is_unique[komik.judul]',
                'errors' =>[
                    'required' => '{field} Komik harus diisi.',
                    'is_unique' => '{field} Komik sudah terdaftar'
                ]
                ],
                // wajib diisi gambar
                'sampul' => [
                    'rules' => 'max_size[sampul,1024]|is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
                    'errors' => [
                        'max_size' => 'Ukuran gambar terlalu besar',
                        'is_image' => 'Yang anda pilih bukan gambar',
                        'mime_in' => 'Yang anda pilih bukan gambar'
                    ]

                ]
        ])){
            // $validation = \Config\Services::validation();
            // return redirect()->to('/komik/create')->withInput()->with('validation', $validation);
            return redirect()->to('/komik/create')->withInput();
        }

        // ambil gambar
        $fileSampul = $this->request->getFile('sampul');
        // apakah idak ada gambar yang diupload
        if($fileSampul->getError() == 4){
            $namaSampul = 'default.png';
        } else{ 
            // generate nama sampul random
            $namaSampul = $fileSampul->getRandomName();
            // pindahkan file ke folder img
            $fileSampul->move('img', $namaSampul);

        }
        


        // ramah URL
        $slug = url_title($this->request->getVar('judul'), '-', true);
        // Ambil apapun
        $this->komikModel->save([
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $namaSampul
        ]);

        session()->setFlashdata('pesan','Data berhasil ditambahkan');

        return redirect()->to('/komik');
    }

    public function delete($id)
    {   
        // cari gambar berdasarkan id
        $komik = $this->komikModel->find($id);

        // cek jika file gambar default.png
        if($komik['sampul'] != 'default.png'){
            // hapus gambar
            unlink('img/'.$komik['sampul']);
        }


        $this->komikModel->delete($id);
        session()->setFlashdata('pesan','Data berhasil dihapus');
        return redirect()->to('/komik');
    }

    public function edit($slug)
    {
        $data = [
            'title' => 'Form Ubah data komik',
            'validation' => \Config\Services::validation(),
            'komik' => $this->komikModel->getKomik($slug)
        ];

        return view('komik/edit', $data);
    }

    public function update($id)
    {
        //cel judul
        $komikLama = $this->komikModel->getKomik($this->request->getVar('slug'));
        if($komikLama['judul'] == $this->request->getVar('judul')){
            $rule_judul = 'required';
        } else{
            $rule_judul = 'required|is_unique[komik.judul]';
        }

        if(!$this->validate([
            'judul' => [
                'rules' => $rule_judul,
                'errors' =>[
                    'required' => '{field} Komik harus diisi.',
                    'is_unique' => '{field} Komik sudah terdaftar'
                ]
                
                ],
                'sampul' => [
                    'rules' => 'max_size[sampul,1024]|is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
                    'errors' => [
                        'max_size' => 'Ukuran gambar terlalu besar',
                        'is_image' => 'Yang anda pilih bukan gambar',
                        'mime_in' => 'Yang anda pilih bukan gambar'
                    ]

                ]
        ])){
            // $validation = \Config\Services::validation();
            // return redirect()->to('/komik/edit/'.$this->request->getVar('slug'))->withInput()->with('validation', $validation);
            return redirect()->to('/komik/edit/'.$this->request->getVar('slug'))->withInput();
        }

        $fileSampul = $this->request->getFile('sampul');

        // cek gambar, apkaah tetap gambar lama
        if($fileSampul->getError() == 4){
            $namaSampul = $this->request->getVar('sampulLama');
        } else {
            // generate file random
            $namaSampul = $fileSampul->getRandomName();
            // pundahkan gambar baru
            $fileSampul->move('img',$namaSampul);
            // hapus file lama
            unlink('img/'.$this->request->getVar('sampulLama'));
        }

        $slug = url_title($this->request->getVar('judul'), '-', true);
        // Ambil apapun
        $this->komikModel->save([
            'id' => $id,
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            'sampul' => $namaSampul
        ]);

        session()->setFlashdata('pesan','Data berhasil diubah');

        return redirect()->to('/komik');
    }
    
}