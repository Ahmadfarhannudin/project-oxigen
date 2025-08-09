<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Registrasi</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="../assets/vendor/animate/animate.css">	
	<link rel="stylesheet" type="text/css" href="../assets/vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="../assets/vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/util.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/main.css">
	<!-- SweetAlert2 -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="../assets/gambar/gym.png" alt="IMG">
				</div>

				<form id="registerForm" action="../../process/register.php" method="post">
					<span class="login100-form-title">
						Registrasi
					</span>

					<div class="wrap-input100 validate-input">
						<input class="input100" name="username" placeholder="Username">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input">
						<input class="input100" name="full_name" placeholder="Nama lengkap">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-id-card" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input">
						<input class="input100" name="email" type="email" placeholder="Email">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input">
						<input class="input100" name="password" type="password" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input">
						<input class="input100" name="confirm_password" type="password" placeholder="Konfirmasi Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					
					<div class="container-login100-form-btn">
						<button class="login100-form-btn" type="submit">
							Registrasi
						</button>
					</div>

					<div class="text-center p-t-12">
						<span class="txt1">Sudah punya akun?</span>
						<a class="txt2" href="login.php">
							Login
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<script src="../assets/vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="../assets/vendor/bootstrap/js/popper.js"></script>
	<script src="../assets/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="../assets/vendor/select2/select2.min.js"></script>
	<script src="../assets/vendor/tilt/tilt.jquery.min.js"></script>
	<script>
		$('.js-tilt').tilt({ scale: 1.1 });

		document.getElementById('registerForm').addEventListener('submit', function(e) {
			let username = this.username.value.trim();
			let fullName = this.full_name.value.trim();
			let email = this.email.value.trim();
			let password = this.password.value.trim();
			let confirmPassword = this.confirm_password.value.trim();

			if (!username || !fullName || !email || !password || !confirmPassword) {
				e.preventDefault();
				Swal.fire({
					icon: 'error',
					title: 'Gagal!',
					text: 'Semua field wajib diisi.'
				});
				return;
			}

			let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			if (!emailPattern.test(email)) {
				e.preventDefault();
				Swal.fire({
					icon: 'error',
					title: 'Gagal!',
					text: 'Format email tidak valid.'
				});
				return;
			}

			if (password !== confirmPassword) {
				e.preventDefault();
				Swal.fire({
					icon: 'error',
					title: 'Gagal!',
					text: 'Password dan konfirmasi password tidak sama.'
				});
				return;
			}
		});
	</script>

	<?php if ($msg = get_flash('success')): ?>
	<script>
		Swal.fire({
			icon: 'success',
			title: 'Berhasil!',
			text: '<?= addslashes($msg) ?>',
			timer: 2000,
			showConfirmButton: false
		});
	</script>
	<?php endif; ?>

	<?php if ($msg = get_flash('error')): ?>
	<script>
		Swal.fire({
			icon: 'error',
			title: 'Gagal!',
			text: '<?= addslashes($msg) ?>',
			timer: 2500,
			showConfirmButton: false
		});
	</script>
	<?php endif; ?>
</body>
</html>
