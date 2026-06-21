# SIMOP KJPP — Panduan Deployment

> Setiap anggota tim melakukan deployment ke server masing-masing. Panduan ini menjelaskan seluruh proses dari awal hingga selesai.

---

## Tentang Container Runtime: Podman

Project ini menggunakan **Podman** sebagai container runtime, bukan Docker CE.

**Mengapa Podman?**
- Podman adalah drop-in replacement untuk Docker — `docker-compose.yml` dan `Dockerfile` tetap kompatibel
- Rootless by default (lebih aman)
- Daemonless architecture (tanpa `dockerd`)
- `podman-docker` menyediakan alias `docker` → `podman`, sehingga semua perintah Docker familiar tetap bekerja

**Podman vs Docker — Apa yang sama?**
| Konsep | Docker | Podman |
|---|---|---|
| Dockerfile | ✅ `docker build` | ✅ `podman build` |
| docker-compose.yml | ✅ `docker compose` | ✅ `podman-compose` |
| Images / Containers | ✅ | ✅ |
| Volumes / Networks | ✅ | ✅ |
| Dockerfile format | Identik | Identik |

---

## 1. Prerequisites (di Server Kamu)

Server kamu membutuhkan software berikut:

| Software | Versi Minimum | Fungsi |
|---|---|---|
| **Podman** | 5.x | Menjalankan container |
| **podman-compose** | 1.x | Mengatur container (via pip) |
| **podman-docker** | — | Alias `docker` → `podman` |
| **Git** | 2.x | Pull code dari GitHub |
| **Ansible** (di laptop kamu) | 2.14+ | Deploy ke server |

> **Catatan:** Podman biasanya sudah ter-install di AlmaLinux. Ansible webserver role akan menginstall podman-compose (via pip) dan podman-docker secara otomatis.

### Install Podman di Server (Jika Belum Ada)

**AlmaLinux / RHEL / CentOS:**
```bash
sudo dnf install -y podman podman-docker
pip install podman-compose
```

**Ubuntu / Debian:**
```bash
sudo apt update
sudo apt install -y podman podman-docker
pip install podman-compose
```

### Install Ansible (di Laptop Kamu)

```bash
# Ubuntu/Debian
sudo apt install -y ansible

# macOS
brew install ansible

# Windows (WSL)
sudo apt install -y ansible
```

---

## 2. Clone Repository

```bash
git clone https://github.com/TubagusAulia/simop-kjpp.git
cd simop-kjpp
```

---

## 3. Konfigurasi Inventory Server

```bash
cp ansible/inventory/hosts.ini.example ansible/inventory/hosts.ini
```

Edit `hosts.ini` dengan detail server kamu:

```ini
[webserver]
YOUR_SERVER_IP ansible_user=YOUR_SSH_USER ansible_python_interpreter=/usr/bin/python3
```

**Contoh:**
```ini
[webserver]
192.168.1.100 ansible_user=deploy ansible_python_interpreter=/usr/bin/python3
```

> ⚠️ `hosts.ini` sudah di-gitignore — aman untuk di-edit, tidak akan ter-commit.

---

## 4. Setup SSH Access

Ansible butuh akses SSH ke server kamu:

```bash
# Generate SSH key (di laptop)
ssh-keygen -t ed25519 -C "simop-deploy"

# Copy public key ke server
ssh-copy-id -i ~/.ssh/id_ed25519.pub YOUR_SSH_USER@YOUR_SERVER_IP

# Test koneksi
ssh YOUR_SSH_USER@YOUR_SERVER_IP "echo 'SSH OK'"
```

---

## 5. (Opsional) Kustomisasi Docker Compose

Jika kamu ingin mengubah port atau menambah environment variables:

```bash
cp docker-compose.override.yml.example docker-compose.override.yml
```

Edit `docker-compose.override.yml` untuk override setting dari `docker-compose.yml`. File ini sudah di-gitignore.

---

## 6. Deploy!

```bash
ansible-playbook -i ansible/inventory/hosts.ini ansible/site.yml --extra-vars "git_repo=https://github.com/TubagusAulia/simop-kjpp.git"
```

Perintah ini akan:
1. Install Podman + podman-compose + podman-docker di server (jika belum ada)
2. Clone repo
3. Build container image
4. Start container
5. Jalankan migration
6. Cache config/routes/views
7. Verifikasi app bisa diakses

**Output yang diharapkan:**
```
✅ SIMOP KJPP is live at http://YOUR_SERVER_IP:8080/
```

---

## 7. Update (Redeploy)

Setelah ada code baru yang di-push ke GitHub:

```bash
ansible-playbook -i ansible/inventory/hosts.ini ansible/site.yml --extra-vars "git_repo=https://github.com/TubagusAulia/simop-kjpp.git"
```

Ansible akan pull code terbaru, rebuild image, dan restart container.

---

## 8. Rollback

Jika deploy bermasalah dan perlu dikembalikan ke versi sebelumnya:

```bash
ansible-playbook -i ansible/inventory/hosts.ini ansible/site.yml --tags rollback
```

Ini akan revert ke container image tag sebelumnya.

---

## 9. Perintah Berguna

```bash
# Cek status container (pakai `docker` alias atau `podman` langsung)
docker ps
podman ps

# Lihat logs
podman logs simop-app -f

# Jalankan perintah artisan di dalam container
podman exec -it simop-app php artisan tinker

# Jalankan tests di dalam container
podman exec -it simop-app php artisan test

# Stop semua container
podman-compose down

# Rebuild dari awal
podman-compose build --no-cache && podman-compose up -d
```

---

## Troubleshooting

| Masalah | Solusi |
|---|---|
| `podman-compose: command not found` | `pip install podman-compose` di server |
| `port 8080 already in use` | Ganti port di `docker-compose.override.yml` |
| `SQLite database locked` | `podman-compose down && podman-compose up -d` |
| `500 Server Error` | Cek logs: `podman logs simop-app -f` |
| `SSH connection refused` | Cek firewall: `sudo ufw allow 22` |
