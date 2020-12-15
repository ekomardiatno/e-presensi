## **Status Response**
```text
OK | BAD_REQUEST | EMPTY | UNAUTHORIZED | EXPIRED
```
---
## **Login Request**
```javascript
{
  method: 'POST',
  body: {
    nip_pegawai: STRING,
    password: STRING,
    merk_perangkat: STRING,
    id_unik_perangkat: STRING,
    model_perangkat: STRING
  },
  response: {
    status: 'OK',
    data: {
      user: {
        nip_pegawai: STRING,
        nama_pegawai: STRING,
        foto_pegawai: STRING,
        jabatan_pegawai: STRING,
        unit_kerja: STRING,
        golongan_pegawai: STRING
      },
      history: [
        {
          id_absensi: STRING,
          nip_pegawai: STRING,
          tipe_pegawai: INTEGER,
          lampiran: STRING,
          tanggal_mulai: DATE,
          tanggal_selesai: DATE,
          keterangan: STRING,
          tanggal_dibuat: TIMESTAMP
        },
        {
          id_presensi: STRING,
          tipe_presensi: INTEGER,
          nip_pegawai: STRING,
          foto: STRING,
          waktu: TIMESTAMP,
          latitude: FLOAT,
          longitude: FLOAT
        }
      ],
      authorization_key: STRING
    }
  }
}
```
---
## **Preference Request**
```javascript
{
  method: 'GET',
  header: {
    authorization_key: STRING
  },
  params: {
    nip_pegawai: STRING
  },
  response: {
    status: 'OK',
    data: {
      pegawai: {
        nip_pegawai: STRING,
        nama_pegawai: STRING,
        foto_pegawai: STRING,
        jabatan_pegawai: STRING,
        unit_kerja: STRING,
        golongan_pegawai: STRING
      },
      aturan_presensi: {
        jam_masuk: TIME,
        jam_pulang: TIME,
        latitude: FLOAT,
        longitude: FLOAT,
        radius_toleransi: INTEGER,
        toleransi_waktu: INTEGER,
        inisial_lokasi: STRING
      }
    }
  }
}
```
---
## **Presence Request**
```javascript
{
  method: 'POST',
  header: {
    authorization_key: STRING
  },
  body: {
    nip_pegawai: STRING,
    latitude: STRING,
    longitude: STRING,
    tipe_presensi: STRING,
    foto: STRING_BASE64,
    status_konfirmasi: STRING,
    inisial_lokasi: STRING,
    merk_perangkat: STRING,
    id_unik_perangkat: STRING,
    model_perangkat: STRING,
    nip_pemilik_perangkat: STRING
  },
  response: {
    status: 'OK',
    data: {
      nip_pegawai: STRING,
      foto: STRING,
      latitude: FLOAT,
      longitude: FLOAT,
      tipe_presensi: INTEGER,
      waktu: TIMESTAMP,
      status_konfirmasi: INTEGER
    }
  }
}
```
---
## **Permit Request**
```javascript
{
  method: 'POST',
  header: {
    authorization_key: STRING
  },
  body: {
    nip_pegawai: STRING,
    tipe_absensi: STRING,
    lampiran: STRING_BASE64,
    tanggal_mulai: DATE,
    tanggal_selesai: DATE,
    keterangan: STRING,
    status_konfirmasi: STRING
  },
  response: {
    status: 'OK',
    data: {
      nip_pegawai: STRING,
      tipe_absensi: INTEGER,
      lampiran: URL,
      tanggal_mulai: DATE,
      tanggal_selesai: DATE,
      keterangan: STRING,
      status_konfirmasi: INTEGER
    }
  }
}
```
---
## **Presence/Permit History Request**
```javascript
{
  method: 'GET',
  header: {
    authorization_key: STRING
  },
  params: {
    nip_pegawai: STRING,
    tanggal: DATE
  },
  response: {
    status: 'OK',
    data: [
      {
        id_absensi: STRING,
        nip_pegawai: STRING,
        tipe_absensi: INTEGER,
        lampiran: URL,
        tanggal_mulai: DATE,
        tanggal_selesai: DATE,
        keterangan: STRING,
        status_konfirmasi: INTEGER
      },
      {
        id_presensi: STRING,
        nip_pegawai: STRING,
        tipe_presensi: INTEGER,
        latitude: FLOAT,
        longitude: FLOAT,
        foto: URL,
        waktu: TIMESTAMP,
        status_konfirmasi: INTEGER,
        keterangan: STRING
      }
    ]
  }
}
```
---
## **Collague List Request**
```javascript
{
  method: 'GET',
  header: {
    authorization_key: STRING
  },
  params: {
    nip_pegawai: STRING,
    nama_pegawai: STRING
  },
  body: [
    {
      pegawai: {
        nip_pegawai: STRING,
        nama_pegawai: STRING,
        foto_pegawai: URL
      },
      aturan_presensi: {
        tipe_presensi: INTEGER,
        jam_masuk: TIME,
        jam_pulang: TIME,
        latitude: FLOAT,
        longitude: FLOAT,
        radius_toleransi: INTEGER,
        toleransi_waktu: INTEGER,
        inisial_lokasi: STRING
      }
    }
  ]
}
```
---
## **Schedule Request**
```javascript
{
  method: 'GET',
  header: {
    authorization_key: STRING
  },
  params: {
    nip_pegawai: STRING
  },
  response: [
    {
      tanggal: DATE,
      jam_masuk: TIME,
      jam_pulang: TIME,
      radius_toleransi: INTEGER,
      toleransi_waktu: INTEGER,
      lokasi_presensi: STRING
    }
  ]
}
```
