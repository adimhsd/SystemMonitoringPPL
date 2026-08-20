<?php

namespace Tests\Feature;

use App\Exports\DplExport;
use App\Exports\MahasiswaExport;
use App\Exports\MitraExport;
use App\Imports\DplImport;
use App\Imports\MahasiswaImport;
use App\Imports\MitraImport;
use App\Models\KelompokPpl;
use App\Models\Mahasiswa;
use App\Models\Mitra;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExcelImportExportRoundtripTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_mitra_export_and_import_headings_are_matching(): void
    {
        $export = new MitraExport();
        $headings = $export->headings();
        $firstItem = $export->collection()->first();
        $mappedRow = $export->map($firstItem);

        // Convert exported heading + mapped row into slugified array key-value pairs (as Excel import heading row does)
        $slugifiedRow = [];
        foreach ($headings as $index => $heading) {
            $slugKey = str_replace([' ', '/', '-'], ['_', '_', '_'], strtolower($heading));
            $slugifiedRow[$slugKey] = $mappedRow[$index];
        }

        $import = new MitraImport();
        $resultModel = $import->model($slugifiedRow);

        $this->assertNotNull($resultModel);
        $this->assertInstanceOf(Mitra::class, $resultModel);
        $this->assertEquals($firstItem->nama_mitra, $resultModel->nama_mitra);
    }

    public function test_dpl_export_and_import_headings_are_matching(): void
    {
        $export = new DplExport();
        $headings = $export->headings();
        $firstItem = $export->collection()->first();
        $mappedRow = $export->map($firstItem);

        $slugifiedRow = [];
        foreach ($headings as $index => $heading) {
            $slugKey = str_replace([' ', '/', '-'], ['_', '_', '_'], strtolower($heading));
            $slugifiedRow[$slugKey] = $mappedRow[$index];
        }

        $import = new DplImport();
        $resultModel = $import->model($slugifiedRow);

        $this->assertNotNull($resultModel);
        $this->assertInstanceOf(User::class, $resultModel);
        $this->assertEquals($firstItem->username, $resultModel->username);
    }

    public function test_mahasiswa_export_and_import_headings_are_matching(): void
    {
        $export = new MahasiswaExport();
        $headings = $export->headings();
        $firstItem = $export->collection()->first();
        $mappedRow = $export->map($firstItem);

        $slugifiedRow = [];
        foreach ($headings as $index => $heading) {
            $slugKey = str_replace([' ', '/', '-'], ['_', '_', '_'], strtolower($heading));
            $slugifiedRow[$slugKey] = $mappedRow[$index];
        }

        $import = new MahasiswaImport();
        $resultModel = $import->model($slugifiedRow);

        $this->assertNotNull($resultModel);
        $this->assertInstanceOf(Mahasiswa::class, $resultModel);
        $this->assertEquals($firstItem->nim, $resultModel->nim);
    }
}
