<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="card border-0 shadow-sm">
  <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
    <h6 class="mb-0 fw-bold">Manajemen User Admin</h6>
    <button type="button" class="btn btn-sm btn-navy" data-bs-toggle="modal" data-bs-target="#modalUser" onclick="resetForm()">
      <i class="bi bi-plus-lg me-1"></i>Tambah User
    </button>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover table-striped align-middle">
        <thead>
          <tr>
            <th width="50">No</th>
            <th>Nama Lengkap</th>
            <th>Email / Login</th>
            <th>Role</th>
            <th>Status</th>
            <th width="100">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $i => $u): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td class="fw-semibold"><?= esc($u['nama']) ?></td>
            <td><?= esc($u['email']) ?></td>
            <td>
              <?php if ($u['role'] === 'super_admin'): ?>
                <span class="badge bg-danger">Super Admin</span>
              <?php else: ?>
                <span class="badge bg-primary">Admin</span>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($u['is_active']): ?>
                <span class="badge bg-success">Aktif</span>
              <?php else: ?>
                <span class="badge bg-secondary">Nonaktif</span>
              <?php endif; ?>
            </td>
            <td>
              <button class="btn btn-sm btn-outline-primary btn-edit-user"
                      data-id="<?= $u['id'] ?>"
                      data-nama="<?= esc($u['nama']) ?>"
                      data-email="<?= esc($u['email']) ?>"
                      data-role="<?= esc($u['role']) ?>"
                      data-aktif="<?= $u['is_active'] ?>"
                      title="Edit">
                <i class="bi bi-pencil"></i>
              </button>
              <?php if ($u['id'] != session('user_id')): ?>
              <button class="btn btn-sm btn-outline-danger btn-hapus-user"
                      data-id="<?= $u['id'] ?>"
                      data-nama="<?= esc($u['nama']) ?>"
                      title="Hapus">
                <i class="bi bi-trash"></i>
              </button>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
          
          <?php if (empty($users)): ?>
            <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data user.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Form User -->
<div class="modal fade" id="modalUser" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formUser" action="<?= base_url('admin/pkpa/users/simpan') ?>" method="post" data-swal-confirm="Ya, simpan user">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="user_id">
        
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Form User Admin</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="nama" id="nama" class="form-control" required maxlength="100">
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Email Login <span class="text-danger">*</span></label>
            <input type="email" name="email" id="email" class="form-control" required maxlength="150">
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Password</label>
            <input type="password" name="password" id="password" class="form-control" minlength="8">
            <div class="form-text" id="pass-help">Wajib diisi untuk user baru. Kosongkan jika tidak ingin mengubah password (untuk edit).</div>
          </div>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Role <span class="text-danger">*</span></label>
              <select name="role" id="role" class="form-select" required>
                <option value="admin">Admin Biasa</option>
                <option value="super_admin">Super Admin</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Status <span class="text-danger">*</span></label>
              <select name="is_active" id="is_active" class="form-select" required>
                <option value="1">Aktif</option>
                <option value="0">Nonaktif</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-navy">Simpan User</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->section('scripts') ?>
<script>
document.querySelectorAll('.btn-edit-user').forEach(btn => {
  btn.addEventListener('click', function() {
    document.getElementById('user_id').value = this.dataset.id;
    document.getElementById('nama').value = this.dataset.nama;
    document.getElementById('email').value = this.dataset.email;
    document.getElementById('role').value = this.dataset.role;
    document.getElementById('is_active').value = this.dataset.aktif;
    
    document.getElementById('password').required = false;
    
    new bootstrap.Modal('#modalUser').show();
  });
});

document.querySelectorAll('.btn-hapus-user').forEach(btn => {
  btn.addEventListener('click', async function() {
    const id = this.dataset.id;
    const nama = this.dataset.nama;
    
    const ok = await swalConfirm({
      title: 'Hapus User?',
      text: `Anda yakin ingin menghapus akun admin "${nama}"?`,
      dangerMode: true,
      confirmText: 'Ya, hapus'
    });
    
    if (ok) {
      const res = await csrfPost(`<?= base_url('admin/pkpa/users/hapus') ?>/${id}`);
      if (res.success) {
        showToast('success', res.message);
        setTimeout(() => location.reload(), 1000);
      } else {
        Swal.fire('Gagal', res.message, 'error');
      }
    }
  });
});

function resetForm() {
  document.getElementById('formUser').reset();
  document.getElementById('user_id').value = '';
  document.getElementById('password').required = true;
  document.getElementById('formUser').classList.remove('was-validated');
}

// Override submit (AJAX JSON)
document.getElementById('formUser').addEventListener('submit', async function(e) {
  if (this.dataset.confirmed !== '1') return;
  e.preventDefault();
  
  const formData = new FormData(this);
  const csrfName = document.querySelector('meta[name="csrf-name"]').content;
  const csrfHash = document.querySelector('meta[name="csrf-hash"]').content;
  formData.append(csrfName, csrfHash);
  
  try {
    const res = await fetch(this.action, { method: 'POST', body: formData });
    const json = await res.json();
    
    if (json.csrf_hash) document.querySelector('meta[name="csrf-hash"]').content = json.csrf_hash;
    
    if (json.success) {
      bootstrap.Modal.getInstance('#modalUser').hide();
      showToast('success', json.message);
      setTimeout(() => location.reload(), 1000);
    } else {
      Swal.fire('Gagal', json.message || 'Data tidak valid.', 'error');
    }
  } catch (err) {
    Swal.fire('Error', 'Terjadi kesalahan.', 'error');
  } finally {
    this.dataset.confirmed = '0';
    const btn = this.querySelector('[type="submit"]');
    btn.disabled = false;
    btn.innerHTML = 'Simpan User';
  }
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
