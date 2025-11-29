<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Ensure a CSRF token exists for these forms
if (!isset($_SESSION['csrf_token'])) {
	try {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	} catch (Exception $e) {
		$_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    
    <div id="container" class="container">
		<!-- FORM SECTION -->
		<div class="row">
			<!-- SIGN UP -->
			<div class="col align-items-center flex-col sign-up">
				<div class="form-wrapper align-items-center">
					<form class="form sign-up" method="POST" action="../database/register_process.php">
                        <div class="input-group">
                            <i class='bx bxs-user'></i>
                            <input type="text" name="username" placeholder="Username" required>
                        </div>
                        <div class="input-group">
                            <i class='bx bx-mail-send'></i>
                            <input type="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="input-group">
                            <i class='bx bx-phone'></i>
                            <input type="text" name="phone" placeholder="Phone" required>
                        </div>
                        <div class="input-group">
                            <i class='bx bxs-lock-alt'></i>
                            <input type="password" name="password" placeholder="Password" required>
                        </div>
                        <div class="input-group">
                            <i class='bx bxs-lock-alt'></i>
                            <input type="password" name="confirm_password" placeholder="Confirm password" required>
                        </div>
						<input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
						<button type="submit" name="signup">Sign up</button>
                        <p>
                            <span>Already have an account?</span>
                            <b onclick="toggle()" class="pointer">Sign in here</b>
                        </p>
                    </form>

				</div>
			
			</div>
			<!-- END SIGN UP -->
			<!-- SIGN IN -->
			<div class="col align-items-center flex-col sign-in">
				<div class="form-wrapper align-items-center">
					<form class="form sign-in" action="../database/login_process.php" method="POST">
                         <div class="input-group">
                             <i class='bx bxs-user'></i>
                             <input type="text" name="username" placeholder="Username or Email" required>
                         </div>

                         <div class="input-group">
                             <i class='bx bxs-lock-alt'></i>
                             <input type="password" name="password" placeholder="Password" required>
                         </div>

						 <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
						 <button type="submit" class="primary-btn">
                             Sign in
                         </button>

                         <p>
                             <b><a href="#" style="text-decoration:none;">Forgot password?</a></b>
                         </p>

                         <p>
                             <span>Don't have an account?</span>
                             <b onclick="toggle()" class="pointer">Sign up here</b>
                         </p>
                    </form>

				</div>
				<div class="form-wrapper">
		
				</div>
			</div>
			<!-- END SIGN IN -->
		</div>
		<!-- END FORM SECTION -->
		<!-- CONTENT SECTION -->
		<div class="row content-row">
			<!-- SIGN IN CONTENT -->
			<div class="col align-items-center flex-col">
				<div class="text sign-in">
					<h2>
						Welcome
					</h2>
	
				</div>
				<div class="img sign-in">
		
				</div>
			</div>
			<!-- END SIGN IN CONTENT -->
			<!-- SIGN UP CONTENT -->
			<div class="col align-items-center flex-col">
				<div class="img sign-up">
				
				</div>
				<div class="text sign-up">
					<h2>
						Join with us
					</h2>
	
				</div>
			</div>
			<!-- END SIGN UP CONTENT -->
		</div>
		<!-- END CONTENT SECTION -->
	</div>

	<!-- Admin Login Link -->
	<div style="text-align: center; margin-top: 30px; padding: 20px;">
		<p style="color: #7fad39; font-size: 0.9rem;">
			Are you an admin? 
			<a href="admin/login.php" style="color: #7fad39; text-decoration: underline; font-weight: 600;">
				Access Admin Console
			</a>
		</p>
	</div>

	<!-- Flash Message Modal -->
	<div id="flashModal" class="flash-modal" style="display: none;">
		<div class="flash-modal__overlay"></div>
		<div class="flash-modal__content">
			<div class="flash-modal__icon" id="flashIcon">
				<i class='bx bx-info-circle'></i>
			</div>
			<h3 id="flashTitle">Notification</h3>
			<p id="flashMessage"></p>
			<button onclick="closeFlashModal()" class="flash-modal__btn">Got it</button>
		</div>
	</div>

	<style>
	.flash-modal {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 10000;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.flash-modal__overlay {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(0, 0, 0, 0.6);
		backdrop-filter: blur(5px);
		animation: fadeIn 0.3s ease;
	}

	.flash-modal__content {
		position: relative;
		background: white;
		border-radius: 20px;
		padding: 40px 32px;
		max-width: 420px;
		width: 90%;
		text-align: center;
		box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
		animation: slideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
		z-index: 10001;
	}

	.flash-modal__icon {
		width: 80px;
		height: 80px;
		margin: 0 auto 24px;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		position: relative;
	}

	.flash-modal__icon.success {
		background: linear-gradient(135deg, #7fad39, #5d8c2f);
		box-shadow: 0 8px 20px rgba(127, 173, 57, 0.4);
	}

	.flash-modal__icon.danger {
		background: linear-gradient(135deg, #e74c3c, #c0392b);
		box-shadow: 0 8px 20px rgba(231, 76, 60, 0.4);
	}

	.flash-modal__icon.warning {
		background: linear-gradient(135deg, #f39c12, #e67e22);
		box-shadow: 0 8px 20px rgba(243, 156, 18, 0.4);
	}

	.flash-modal__icon i {
		font-size: 2.5rem;
		color: white;
	}

	.flash-modal__content h3 {
		margin: 0 0 16px 0;
		font-size: 1.6rem;
		font-weight: 700;
		color: #2c3e50;
	}

	.flash-modal__content p {
		margin: 0 0 28px 0;
		font-size: 1rem;
		color: #7f8c8d;
		line-height: 1.6;
	}

	.flash-modal__btn {
		width: 100%;
		padding: 14px 28px;
		background: linear-gradient(135deg, #7fad39, #5d8c2f);
		border: none;
		border-radius: 12px;
		color: white;
		font-size: 1rem;
		font-weight: 600;
		cursor: pointer;
		transition: all 0.3s ease;
		box-shadow: 0 4px 12px rgba(127, 173, 57, 0.3);
	}

	.flash-modal__btn:hover {
		transform: translateY(-2px);
		box-shadow: 0 6px 20px rgba(127, 173, 57, 0.4);
	}

	@keyframes fadeIn {
		from { opacity: 0; }
		to { opacity: 1; }
	}

	@keyframes slideUp {
		from {
			opacity: 0;
			transform: translateY(30px) scale(0.95);
		}
		to {
			opacity: 1;
			transform: translateY(0) scale(1);
		}
	}
	</style>
</body>

<script>
let container = document.getElementById('container')

toggle = () => {
	container.classList.toggle('sign-in')
	container.classList.toggle('sign-up')
}

setTimeout(() => {
	container.classList.add('sign-in')
}, 200)

// Flash message modal
let modalIsOpen = false;

function closeFlashModal() {
	document.getElementById('flashModal').style.display = 'none';
	document.body.style.overflow = '';
	document.documentElement.style.overflow = '';
	modalIsOpen = false;
	console.log('DEBUG: Modal closed');
	
	// Clean URL after closing modal
	if (window.location.search) {
		window.history.replaceState({}, document.title, window.location.pathname);
	}
}

function showModal(type, message, title) {
	console.log('DEBUG: showModal called with', type, message, title);
	
	const modal = document.getElementById('flashModal');
	const icon = document.getElementById('flashIcon');
	const titleEl = document.getElementById('flashTitle');
	const messageEl = document.getElementById('flashMessage');
	
	if (!modal) {
		console.error('ERROR: Modal not found!');
		return;
	}
	
	// Set content
	messageEl.textContent = message;
	titleEl.textContent = title;
	icon.className = 'flash-modal__icon ' + type;
	
	// Set icon based on type
	if (type === 'success') {
		icon.innerHTML = '<i class="bx bx-check-circle"></i>';
	} else if (type === 'danger') {
		icon.innerHTML = '<i class="bx bx-x-circle"></i>';
	} else if (type === 'warning') {
		icon.innerHTML = '<i class="bx bx-error"></i>';
	}
	
	// Show modal
	modal.style.display = 'flex';
	document.body.style.overflow = 'hidden';
	document.documentElement.style.overflow = 'hidden';
	modalIsOpen = true;
	
	// Visual debug
	modal.style.border = '5px solid red';
	console.log('DEBUG: Modal displayed');
}

// Check URL parameters for flash messages
window.addEventListener('DOMContentLoaded', function() {
	console.log('DEBUG: DOMContentLoaded fired');
	
	const urlParams = new URLSearchParams(window.location.search);
	console.log('DEBUG: URL params:', window.location.search);
	
	// Check for URL parameter messages
	if (urlParams.has('warning')) {
		const warning = urlParams.get('warning');
		console.log('DEBUG: Warning parameter found:', warning);
		
		if (warning === 'admin_only') {
			setTimeout(function() {
				showModal('warning', 'Admin accounts must login via Admin Console.', 'Warning!');
			}, 100);
		}
	} else if (urlParams.has('error')) {
		const error = urlParams.get('error');
		console.log('DEBUG: Error parameter found:', error);
		
		if (error === 'customer_blocked') {
			setTimeout(function() {
				showModal('danger', 'Customer accounts cannot access Admin Console.', 'Error!');
			}, 100);
		}
	}
	
	// Also check session-based flash messages
	<?php 
	require_once "../database/flash_message.php";
	
	if (isset($_SESSION['flash_message'])): 
		$flash = $_SESSION['flash_message'];
		unset($_SESSION['flash_message']);
	?>
		console.log('DEBUG: Session flash message found');
		setTimeout(function() {
			showModal(
				<?= json_encode($flash['type']) ?>,
				<?= json_encode($flash['message']) ?>,
				<?= json_encode($flash['type']) ?> === 'success' ? 'Success!' : 
				<?= json_encode($flash['type']) ?> === 'danger' ? 'Error!' : 'Warning!'
			);
		}, 100);
	<?php endif; ?>
});

// Close on overlay click
document.addEventListener('click', function(e) {
	if (e.target.classList.contains('flash-modal__overlay')) {
		console.log('DEBUG: Overlay clicked');
		closeFlashModal();
	}
});

// Close on Escape key
document.addEventListener('keydown', function(e) {
	if (e.key === 'Escape' && modalIsOpen) {
		console.log('DEBUG: Escape pressed');
		closeFlashModal();
	}
});
</script>
</html>