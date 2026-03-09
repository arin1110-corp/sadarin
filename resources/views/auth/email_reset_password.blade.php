<h2>Reset Password</h2>

<p>Halo {{ $user->user_nama }}</p>

<p>
Klik tombol berikut untuk reset password:
</p>

<a href="{{ $link }}" 
style="padding:10px 20px;background:#0d6efd;color:white;text-decoration:none;">
Reset Password
</a>

<p>Link berlaku 30 menit.</p>