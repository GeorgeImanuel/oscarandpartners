<script>
	// Traditional session flash messages (for redirects and regular page loads)
	@if(session('success'))
		Swal.fire({
			icon: "success",
			title: "Berhasil!",
			text: "{{ session('success') }}",
			timer: 3000,
		});
	@endif

	@if(session('error'))
		Swal.fire({
			icon: "error",
			title: "Terjadi Kesalahan!",
			text: "{{ session('error') }}",
			confirmButtonText: "OK"
		});
	@endif

	@if(session('error-log'))
		console.error("Error Log:", "{{ session('error-log') }}");
	@endif

	@if(session('toast-success'))
		const Toast = Swal.mixin({
			toast: true,
			position: "top-end",
			showConfirmButton: false,
			timer: 3000,
			timerProgressBar: true,
		});
		Toast.fire({
			icon: "success",
			title: "{{ session('toast-success') }}"
		});
	@endif

	@if(session('toast-error'))
		const Toast = Swal.mixin({
			toast: true,
			position: "top-end", 
			showConfirmButton: false,
			timer: 3000,
			timerProgressBar: true,
		});
		Toast.fire({
			icon: "error",
			title: "{{ session('toast-error') }}"
		});
	@endif

	@if(session('toast-info'))
		const Toast = Swal.mixin({
			toast: true,
			position: "top-end",
			showConfirmButton: false,
			timer: 3000,
			timerProgressBar: true,
		});
		Toast.fire({
			icon: "info",
			title: "{{ session('toast-info') }}"
		});
	@endif

	@if(session('warning'))
		Swal.fire({
			icon: "warning",
			title: "Peringatan!",
			text: "{{ session('warning') }}",
			confirmButtonText: "OK"
		});
	@endif

	@if(session('info'))
		Swal.fire({
			icon: "info",
			title: "Informasi",
			text: "{{ session('info') }}",
			confirmButtonText: "OK"
		});
	@endif
</script>