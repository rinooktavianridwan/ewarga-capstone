<?php

namespace Modules\Umkm\Services;

use App\Services\WargaService;
use App\Exceptions\FlowException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Umkm\Entities\UmkmProduk;
use Modules\Umkm\Entities\UmkmWarga;
use Modules\Umkm\Services\UmkmFotoService;

class UmkmProdukService
{
    protected $wargaService;
    protected $umkmFotoService;

    public function __construct(WargaService $wargaService, UmkmFotoService $umkmFotoService)
    {
        $this->wargaService = $wargaService;
        $this->umkmFotoService = $umkmFotoService;
    }

    public function getFiltered(array $data)
    {
        $instansiId = $data['instansi_id'];
        $umkmId = $data['umkm_id'];
        $userId = auth()->user()->id;

        $hasAccess = $this->wargaService->hasAccess($userId, $instansiId);

        if (!$hasAccess) {
            throw new FlowException("Pengguna tidak memiliki akses ke instansi ini");
        }

        $produk = UmkmProduk::with(['umkm', 'instansi', 'fotos'])
            ->where('umkm_id', $umkmId)
            ->where('instansi_id', $instansiId);

        $produk = $produk->get();

        if ($produk->isEmpty()) {
            throw new ModelNotFoundException("Data produk tidak ditemukan");
        }

        return $produk;
    }

    public function getById($id): UmkmProduk
    {
        $produk = UmkmProduk::with([
            'umkm',
            'instansi',
            'fotos',
        ])->find($id);

        if (!$produk) {
            throw new ModelNotFoundException("Data produk tidak ditemukan");
        }

        return $produk;
    }

    public function create(array $data): UmkmProduk
    {
        $instansiId = $data['instansi_id'];
        $umkmId = $data['umkm_id'];
        $userId = auth()->user()->id;

        if (!$this->isOwner($umkmId, $userId)) {
            $hasAccess = $this->wargaService->hasAccess($userId, $instansiId);

            if (!$hasAccess) {
                throw new FlowException("Pengguna tidak memiliki izin untuk menambah produk pada umkm ini");
            }
        }

        $fotoFiles = $data['fotos'] ?? [];
        unset($data['fotos']);

        return DB::transaction(function () use ($data, $fotoFiles) {
            $produk = UmkmProduk::create($data);

            if (!empty($fotoFiles)) {
                $this->umkmFotoService->store($produk, $fotoFiles, $data['instansi_id']);
            }

            return $produk->load('umkm', 'instansi', 'fotos');
        });
    }

    public function update(UmkmProduk $produk, array $data)
    {
        $instansiId = $data['instansi_id'];
        $umkmId = $data['umkm_id'];
        $userId = auth()->user()->id;

        if (!$this->isOwner($umkmId, $userId)) {
            $hasAccess = $this->wargaService->hasAccess($userId, $instansiId);

            if (!$hasAccess) {
                throw new FlowException("Pengguna tidak memiliki izin untuk mengubah umkm ini");
            }
        }

        return DB::transaction(function () use ($produk, $data) {
            $fotoBaru = $data['fotos'] ?? [];
            $hapusFotoIds = $data['hapus_foto'] ?? [];

            unset($data['fotos'], $data['hapus_foto']);

            $fotoTersisa = $produk->fotos->count() - count($hapusFotoIds);

            if (($fotoTersisa + count($fotoBaru)) > 5) {
                throw new \Exception('Jumlah total foto tidak boleh lebih dari 5');
            }

            $produk->update($data);

            if (!empty($hapusFotoIds)) {
                $this->umkmFotoService->delete($produk, $hapusFotoIds);
            }

            if (!empty($fotoBaru)) {
                $this->umkmFotoService->store($produk, $fotoBaru, $data['instansi_id']);
            }

            return $produk->load('umkm', 'instansi', 'fotos');
        });
    }

    public function delete(UmkmProduk $produk): array
    {
        return DB::transaction(function () use ($produk) {
            $this->umkmFotoService->delete($produk, $produk->fotos->pluck('id')->toArray());
            $produkData = $produk->toArray();
            $produk->delete();

            return $produkData;
        });
    }

    public function isOwner($umkmId, $userId): bool
    {
        return UmkmWarga::where('umkm_id', $umkmId)
            ->whereHas('warga', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->exists();
    }
}
