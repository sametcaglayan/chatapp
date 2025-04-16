# Chat App Hoşgeldiniz

Proje, `docker compose up --build` komutu ile çalıştırılabilir. Bu komut, gerekli tüm container'ları yükler ve Swagger dokümantasyonunu oluşturur. [Swagger Dokümantasyonu](http://localhost:9000/api/documentation) adresinden erişebilirsiniz.

---

## Mobil API

- **`/auth`**: Cihaz kontrolü yapar ve bir token verir.
- **`/subscription`**: Abonelik satın alınıp alınmadığını kontrol eder.
- **`/buy`**: Abonelik satın alır ve satın alınan aboneliğe göre cihazınıza kredi ekler.
- **`/chat`**: Mesajlaşma yapılır.

**Not**: `auth` hariç diğer API'lar `bearer` token kullanır.

### Rate Limit

Tüm API route'ları için dakikada **60 istek** limiti uygulanmaktadır.

---

## Admin Panel

Admin paneline şu adres üzerinden giriş yapabilirsiniz: [http://localhost:9000/login](http://localhost:9000/login).

- **Kullanıcı adı**: `admin`
- **Şifre**: `123` (hard coded)

Giriş yaptıktan sonra, **Cihaz Listesi** görüntülenir. Kayıtlı cihazların listesinde **Chat butonuna** tıklanarak o cihaza ait **chat ID'leri** listelenir. Chat ID'lerine tıklanıldığında, ilgili chat'e ait mesajlar gösterilir.

**Not**: Admin paneli en basit şekilde Vue.js ile yazılmıştır.

---

## Teknolojiler

- **Laravel 10**
- **PHP 8.2**
- **MySQL 8.0**
- **Vue.js**
- **Docker**

---

## Docker ile Çalıştırma

Proje çalıştırıldığında, **veritabanı migration** işlemi otomatik olarak yapılır.

```bash
docker compose up --build
