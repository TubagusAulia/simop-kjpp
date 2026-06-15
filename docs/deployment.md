# SIMOP KJPP — Panduan Deployment

> Setiap anggota tim melakukan deployment ke server masing-masing. Panduan ini menjelaskan seluruh proses dari awal hingga selesai.

---

## 1. Prerequisites (di Server Kamu)

Server kamu membutuhkan software berikut:

| Software | Versi Minimum | Fungsi |
|---|---|---|
| **Docker CE** | 24.0+ | Menjalankan container |
| **Docker Compose** | 2.0+ | Mengatur container |
| **Git** | 2.x | Pull code dari GitHub |
| **Ansible** (di laptop kamu) | 2.14+ | Deploy ke server |

### Install Docker di Server

**Ubuntu / Debian:**
```bash
sudo apt update
sudo apt install -y ca-certificates curl gnupg
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.asc
echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/ubuntu $(. /etc/os-release && echo $VERSION_CODENAME) stable" | sudo tee /etc/apt/sources.list.d/docker.list
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin
```

**AlmaLinux / RHEL / CentOS:**
```bash
sudo yum install -y yum-utils
sudo yum-config-manager --add-repo https://download.docker.com/linux/centos/docker-ce.repo
sudo yum install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin
sudo systemctl start docker
sudo systemctl enable docker
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
1. Install Docker di server (jika belum ada)
2. Clone repo
3. Build Docker image
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

Ini akan revert ke Docker image tag sebelumnya.

---

## 9. Perintah Berguna

```bash
# Cek status container
docker ps

# Lihat logs
docker logs simop-app -f

# Jalankan perintah artisan di dalam container
docker exec -it simop-app php artisan tinker

# Jalankan tests di dalam container
docker exec -it simop-app php artisan test

# Stop semua container
docker compose down

# Rebuild dari awal
docker compose build --no-cache && docker compose up -d
```

---

## Troubleshooting

| Masalah | Solusi |
|---|---|
| `docker: permission denied` | `sudo usermod -aG docker $USER` lalu logout dan login kembali |
| `port 8080 already in use` | Ganti port di `docker-compose.override.yml` |
| `SQLite database locked` | `docker compose down && docker compose up -d` |
| `500 Server Error` | Cek logs: `docker logs simop-app -f` |
| `SSH connection refused` | Cek firewall: `sudo ufw allow 22` |
