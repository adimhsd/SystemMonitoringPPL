<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DplImport implements ToModel, WithHeadingRow
{
    /**
     * Map baris Excel ke model User DPL.
     */
    public function model(array $row): Model|array|null
    {
        $namaLengkap = trim((string) ($row['nama_lengkap_dpl'] ?? $row['nama_lengkap'] ?? $row['nama'] ?? ''));
        $nipNidn = trim((string) ($row['nip_nidn'] ?? $row['nip'] ?? $row['nidn'] ?? ''));
        $username = trim((string) ($row['username'] ?? ''));

        // Jika baik nama maupun username/nip kosong, lewati baris ini
        if (empty($namaLengkap) && empty($username) && empty($nipNidn)) {
            return null;
        }

        // Generate username otomatis jika belum ada di Excel
        if (empty($username)) {
            if (!empty($nipNidn)) {
                $username = 'dpl_' . preg_replace('/[^a-zA-Z0-9]/', '', $nipNidn);
            } else {
                $username = 'dpl_' . Str::slug($namaLengkap, '_');
            }
        }

        if (empty($namaLengkap)) {
            $namaLengkap = 'DPL ' . strtoupper($username);
        }

        $noHp = isset($row['no_hp_whatsapp']) ? trim((string) $row['no_hp_whatsapp']) : (isset($row['no_hp']) ? trim((string) $row['no_hp']) : null);
        $email = isset($row['email']) ? trim((string) $row['email']) : null;
        $passwordInput = trim((string) ($row['password'] ?? 'password123'));
        if (empty($passwordInput)) {
            $passwordInput = 'password123';
        }

        $statusInput = strtolower(trim((string) ($row['status_akun'] ?? $row['status'] ?? 'aktif')));
        $isActive = !in_array($statusInput, ['non-aktif', 'nonaktif', '0', 'false', 'disabled', 'inaktif']);

        $user = User::where('role', 'dpl')
            ->where(function ($q) use ($username, $nipNidn) {
                $q->where('username', $username);
                if (!empty($nipNidn)) {
                    $q->orWhere('nip_nidn', $nipNidn);
                }
            })
            ->first();

        if ($user) {
            $user->update([
                'username' => $username,
                'nama_lengkap' => $namaLengkap,
                'nip_nidn' => !empty($nipNidn) ? $nipNidn : $user->nip_nidn,
                'no_hp' => !empty($noHp) && $noHp !== '-' ? $noHp : $user->no_hp,
                'email' => !empty($email) && $email !== '-' ? $email : $user->email,
                'is_active' => $isActive,
            ]);

            return $user;
        }

        return User::create([
            'username' => $username,
            'password' => Hash::make($passwordInput),
            'role' => 'dpl',
            'nama_lengkap' => $namaLengkap,
            'nip_nidn' => $nipNidn ?: null,
            'no_hp' => ($noHp && $noHp !== '-') ? $noHp : null,
            'email' => ($email && $email !== '-') ? $email : null,
            'must_change_password' => true,
            'is_active' => $isActive,
        ]);
    }
}
