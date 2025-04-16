<?php
@ob_start();
session_start();
if (isset($_POST['proses'])) {
	require 'config.php';

	$user = strip_tags($_POST['user']);
	$pass = strip_tags($_POST['pass']);

	$sql = 'SELECT member.*, login.user, login.pass
			FROM member 
			INNER JOIN login ON member.id_member = login.id_member
			WHERE user = ? AND pass = md5(?)';
	$row = $config->prepare($sql);
	$row->execute(array($user, $pass));
	$jum = $row->rowCount();

	if ($jum > 0) {
		$hasil = $row->fetch();
		$_SESSION['admin'] = $hasil;
		echo '<script>alert("Login Sukses");window.location="index.php"</script>';
	} else {
		echo '<script>alert("Login Gagal");history.go(-1);</script>';
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login - POS Codekop</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Bootstrap 5 -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
	<!-- Custom CSS -->
	<style>
		body {
			background: linear-gradient(to right, #74ebd5, #acb6e5);
			font-family: 'Poppins', sans-serif;
		}
		.card {
			border: none;
			border-radius: 20px;
			box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
		}
		.btn-custom {
			background-color: #4e73df;
			color: white;
			border-radius: 50px;
			font-weight: 600;
			transition: all 0.3s ease;
		}
		.btn-custom:hover {
			background-color: #3752c9;
		}
		.form-control {
			border-radius: 10px;
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="row justify-content-center align-items-center min-vh-100">
			<div class="col-md-5">
				<div class="card p-4">
					<div class="card-body">
						<div class="text-center mb-4">
							<h3 class="fw-bold text-primary">POS Codekop</h3>
							<p class="text-muted">Silakan login ke akun Anda</p>
						</div>
						<form method="POST">
							<div class="mb-3">
								<label class="form-label">User ID</label>
								<input type="text" name="user" class="form-control" placeholder="Masukkan user ID" required autofocus>
							</div>
							<div class="mb-4">
								<label class="form-label">Password</label>
								<input type="password" name="pass" class="form-control" placeholder="Masukkan password" required>
							</div>
							<div class="d-grid">
								<button type="submit" name="proses" class="btn btn-custom">
									<i class="fas fa-sign-in-alt me-1"></i> Sign In
								</button>
							</div>
						</form>
						<!-- Optional:
						<div class="text-center mt-3">
							<a href="#" class="text-decoration-none small text-primary">Lupa password?</a>
						</div> -->
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- FontAwesome -->
	<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
