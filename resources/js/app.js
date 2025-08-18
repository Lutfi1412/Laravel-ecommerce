import './bootstrap';
import Swal from 'sweetalert2';

window.AddProduct = async function () {
    const { value: formValues } = await Swal.fire({
        title: "Tambah Produk",
        html: `
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <input id="nama" class="swal2-input" placeholder="Nama produk" style="margin: 5px 0;">
                <input id="desc" class="swal2-input" placeholder="Deskripsi" style="margin: 5px 0;">
                <input id="harga" class="swal2-input" placeholder="Harga produk" type="number" style="margin: 5px 0;">
                <input id="jumlah" class="swal2-input" placeholder="Jumlah" type="number" style="margin: 5px 0;">

                <div style="margin: 10px 0;">
                    <input id="image" type="file" accept="image/*"
                           style="
                               width: 100%;
                               padding: 8px 12px;
                               border: 2px dashed #ddd;
                               border-radius: 8px;
                               background: #f8f9fa;
                               cursor: pointer;
                               transition: all 0.3s ease;
                           ">
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal',
        focusConfirm: false,
        preConfirm: () => {
            const nama = document.getElementById('nama').value.trim();
            const desc = document.getElementById('desc').value.trim();
            const harga = document.getElementById('harga').value;
            const jumlah = document.getElementById('jumlah').value;
            const image = document.getElementById('image').files[0];

            if (!nama || !desc || !harga || !jumlah || !image) {
                Swal.showValidationMessage('Semua field wajib diisi!');
                return false;
            }
            return { nama, desc, harga, jumlah, image };
        }
    });

    if (formValues) {
        let formData = new FormData();
        formData.append('nama', formValues.nama);
        formData.append('desc', formValues.desc);
        formData.append('harga', formValues.harga);
        formData.append('jumlah', formValues.jumlah);
        formData.append('image', formValues.image);

        await fetch('/dashboard/seller/add-product', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async res => {
            if (!res.ok) {
                let text = await res.text();
                throw new Error(text);
            }
            return res.json();
        })
        .then(data => {
            Swal.fire({
                icon: data.success ? 'success' : 'error',
                title: data.message
            }).then(() => {
                if (data.success) {
                    location.reload(); // refresh halaman
                }
            });
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Terjadi kesalahan saat menyimpan produk'
            });
        });
    }
};


window.DetailProduct = async function (id) {
    const response = await fetch(`/dashboard/seller/detail-product/${id}`);
    const data = await response.json();

    Swal.fire({
        title: 'Detail Produk',
        html: `

                <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 10px;">
                    <img src="/database/image/${data.image}" alt="${data.name}"
                        style="width:150px; height:auto; border-radius:8px;">
                </div>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <input type="text" class="swal2-input" value="${data.name}" placeholder="Nama Produk" disabled>
                    <input type="text" class="swal2-input" value="${data.description}" placeholder="Deskripsi" disabled>
                    <input type="text" class="swal2-input" value="Rp ${data.price}" placeholder="Harga" disabled>
                    <input type="text" class="swal2-input" value="${data.stock}" placeholder="Stok" disabled>
                </div>


        `,
        confirmButtonText: 'Tutup',
        width: 400
    });
}


window.DeleteProduct = async function (id) {
    Swal.fire({
        title: "Apakah kamu yakin?",
        text: "Produk ini akan dihapus dan tidak bisa dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Hapus",
        cancelButtonText: "Batal"
    }).then(async (result) => {
        if (result.isConfirmed) {
            const response = await fetch(`/dashboard/seller/delete-product/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                }
            });

            if (response.ok) {
                Swal.fire("Terhapus!", "Produk berhasil dihapus.", "success")
                    .then(() => {
                        location.reload();
                    });
            } else {
                Swal.fire("Gagal!", "Produk gagal dihapus.", "error");
            }
        }
    });
};

window.EditProduct = async function (id, currentData) {
    const { value: formValues } = await Swal.fire({
        title: "Edit Produk",
        html: `
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <div style="width: 100%; display: flex; justify-content: center;">
                <img src="/database/image/${currentData.image}" alt="${currentData.name}"
                    style="width:150px;height:auto;border-radius:8px;">
            </div>


                <label for="swal-name" style="display:block; text-align:left;">Nama Produk</label>
                <input id="swal-name" class="swal2-input" placeholder="Nama" value="${currentData.name}" style="margin: 5px 0;">

                <label for="swal-description" style="display:block; text-align:left;">Deskripsi</label>
                <input id="swal-description" class="swal2-input" placeholder="Deskripsi" value="${currentData.description}" style="margin: 5px 0;">

                <label for="swal-price" style="display:block; text-align:left;">Harga</label>
                <input id="swal-price" type="number" class="swal2-input" placeholder="Harga" value="${currentData.price}" style="margin: 5px 0;">

                <label for="swal-stock" style="display:block; text-align:left;">Jumlah</label>
                <input id="swal-stock" type="number" class="swal2-input" placeholder="Stok" value="${currentData.stock}" style="margin: 5px 0;">

                <label for="swal-image" style="display:block; text-align:left; margin-top: 10px;">Gambar</label>
                <input id="swal-image" type="file" accept="image/*"
                    style="
                        width: 100%;
                        padding: 8px 12px;
                        border: 2px dashed #ddd;
                        border-radius: 8px;
                        background: #f8f9fa;
                        cursor: pointer;
                        transition: all 0.3s ease;
                    ">
            </div>


        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: "Simpan",
        cancelButtonText: "Batal",
        preConfirm: () => {
            return {
                name: document.getElementById("swal-name").value,
                description: document.getElementById("swal-description").value,
                price: document.getElementById("swal-price").value,
                stock: document.getElementById("swal-stock").value,
                image: document.getElementById("swal-image").files[0]
            };
        }
    });

    if (formValues) {
        let formData = new FormData();
        formData.append('nama', formValues.name);
        formData.append('desc', formValues.description);
        formData.append('harga', formValues.price);
        formData.append('jumlah', formValues.stock);
        if (formValues.image) {
            formData.append('image', formValues.image);
        }

        const response = await fetch(`/dashboard/seller/update-product/${id}`, {
            method: "POST", // atau PUT sesuai route
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            },
            body: formData
        });

        if (response.ok) {
            Swal.fire("Tersimpan!", "Produk berhasil diperbarui.", "success")
                .then(() => location.reload());
        } else {
            Swal.fire("Gagal!", "Produk gagal diperbarui.", "error");
        }
    }
};

window.SellerUpdateStatus = async function (id) {
    Swal.fire({
        title: "Apakah kamu yakin?",
        text: "Produk Ini Siap Dikirim?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#16e643ff",
        confirmButtonText: "Ya",
        cancelButtonText: "Batal"
    }).then(async (result) => {
        if (result.isConfirmed) {
            const response = await fetch(`/dashboard/seller/update-status/${id}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                }
            });
            if (response.ok) {
                Swal.fire("Barang sudah dikirm!", "Mohon tunggu konfirmasi", "success")
                    .then(() => {
                        location.reload();
                    });
            } else {
                Swal.fire("Gagal!", "Produk gagal dihapus.", "error");
            }
        }
    });
};

window.CustomerUpdateStatus = async function (id, total, barang_id) {
    console.log(barang_id);
    console.log(total);
    Swal.fire({
        title: "Apakah kamu yakin?",
        text: "Produk Ini Sudah Diterima?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#16e643ff",
        confirmButtonText: "Ya",
        cancelButtonText: "Batal"
    }).then(async (result) => {
        if (result.isConfirmed) {
            const response = await fetch(`/dashboard/customer/update-status/${id}/${total}/${barang_id}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                }
            });
            if (response.ok) {
                Swal.fire("Barang sudah diterima!", "Terima Kasih Sudah Berbelanja", "success")
                    .then(() => {
                        location.reload();
                    });
            } else {
                Swal.fire("Gagal!", "Produk gagal diupdate.", "error");
            }
        }
    });
};
