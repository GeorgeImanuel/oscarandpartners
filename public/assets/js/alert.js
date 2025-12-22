function alertShow(payload) {
    const data = payload && payload[0] ? payload[0] : payload;
    const icon = data.icon || "info";
    const title =
        data.title ||
        (icon == "error"
            ? "Terjadi Kesalahan!"
            : icon == "success"
            ? "Sukses!"
            : "Notifikasi");
    const text = data.text || data.message || "";
    const confirmButtonText = data.confirmButtonText || "OK";
    const redirect = data.redirect || data.url || null;
    const redirectLabel = data.redirectLabel || data.linkText || "OK";

    if (redirect) {
        Swal.fire({
            icon: icon,
            title: title,
            html: `
                    <p>${text}</p>
                    <div style="margin-top: 20px;">
                    <a href="${redirect}" class="btn btn-primary" style="text-decoration: none;">
                        ${redirectLabel}
                    </a>
                    </div>
                `,
            showConfirmButton: false,
            showCloseButton: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
        });
    } else {
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            confirmButtonText: confirmButtonText,
            allowOutsideClick: false,
            allowEscapeKey: false,
        });
    }
}
// Livewire listener for alertShow event (must be outside document.ready)
document.addEventListener("livewire:init", () => {
    Livewire.on("alertShow", (event) => {
        console.log("Livewire show-alert event received:", event);
        alertShow(event);
    });

    Livewire.on("alertLog", (payload) => {
        const data = payload && payload[0] ? payload[0] : payload;
        const message =
            data.message ||
            data.text ||
            data.error ||
            data.msg ||
            JSON.stringify(data);
        console.error("Livewire alertLog received:", message);
        if (data.stack) {
            console.error("Stack trace:", data.stack);
        }
    });
});

$(document).ready(function () {
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        },
    });

    if (sessionStorage.getItem("toast-error")) {
        Toast.fire({
            icon: "error",
            text: sessionStorage.getItem("toast-error"),
        });
        sessionStorage.removeItem("toast-error");
    }

    if (sessionStorage.getItem("toast-warning")) {
        Toast.fire({
            icon: "warning",
            text: sessionStorage.getItem("toast-warning"),
        });
        sessionStorage.removeItem("toast-error");
    }

    if (sessionStorage.getItem("toast-success")) {
        Toast.fire({
            icon: "success",
            title: sessionStorage.getItem("toast-success"),
        });
        sessionStorage.removeItem("toast-success");
    }
});

function alertSuccess(title = "Sukses!", message = null, redirect = null) {
    alertShow({
        icon: "success",
        title: title,
        text: message,
        redirect: redirect,
    });
}

function alertWarning(title = "Perhatian!", message = null, redirect = null) {
    alertShow({
        icon: "warning",
        title: title,
        text: message,
        redirect: redirect,
    });
}

function alertError(
    title = "Terjadi kesalahan!",
    message = "Silahkan Coba Kembali!",
    redirect = null
) {
    alertShow({
        icon: "error",
        title: title,
        text: message,
        redirect: redirect,
    });
}

function showLoading(message = "Memproses...") {
    Swal.fire({
        title: message,
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
}

function hideLoading() {
    Swal.close();
}

function confirmDelete(that) {
    var title = that.getAttribute("data-title");
    if (title == undefined) {
        title = "Yakin ingin menghapus?";
    }

    Swal.fire({
        icon: "question",
        title: title,
        text: $(that).attr("data-message"),
        showCancelButton: true,
        confirmButtonText: "Yakin",
        denyButtonText: "Batal",
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading("Menghapus...");
            that.closest("form").submit(); // Post the surrounding form
        }
    });
}

function alertConfirm(options = {}) {
    const title = options.title || "Apakah Anda yakin?";
    const text = options.text || options.message || "";
    const icon = options.icon || "question";
    const confirmButtonText = options.confirmButtonText || "Ya";
    const cancelButtonText = options.cancelButtonText || "Batal";
    const onConfirm = options.onConfirm || null;
    const onCancel = options.onCancel || null;

    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        showCancelButton: true,
        confirmButtonText: confirmButtonText,
        cancelButtonText: cancelButtonText,
        allowOutsideClick: false,
        allowEscapeKey: false,
    }).then((result) => {
        if (result.isConfirmed) {
            if (onConfirm && typeof onConfirm === "function") {
                onConfirm();
            }
        } else if (result.isDismissed && onCancel && typeof onCancel === "function") {
            onCancel();
        }
    });
}

// Usage examples:

// Basic usage
// alertConfirm({
//     title: "Konfirmasi Hapus",
//     text: "Data yang dihapus tidak dapat dikembalikan",
//     onConfirm: () => {
//         console.log("User confirmed");
//         // Your delete logic here
//     }
// });

// With custom button text and cancel callback
// alertConfirm({
//     title: "Simpan Perubahan?",
//     text: "Perubahan akan disimpan secara permanen",
//     icon: "warning",
//     confirmButtonText: "Simpan",
//     cancelButtonText: "Tidak",
//     onConfirm: () => {
//         showLoading("Menyimpan...");
//         // Save logic here
//     },
//     onCancel: () => {
//         console.log("User cancelled");
//     }
// });

// Simple confirmation without callbacks
// alertConfirm({
//     title: "Lanjutkan?",
//     text: "Proses ini akan memakan waktu beberapa menit"
// });