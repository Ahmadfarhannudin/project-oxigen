// validation.js
document.addEventListener('DOMContentLoaded', function(){
  const regForm = document.getElementById('registerForm');
  if (regForm) {
    regForm.addEventListener('submit', function(e){
      const p = regForm.password.value;
      const cp = regForm.confirm_password.value;
      if (p.length < 6) {
        alert('Password minimal 6 karakter');
        e.preventDefault();
        return;
      }
      if (p !== cp) {
        alert('Password dan konfirmasi tidak cocok');
        e.preventDefault();
      }
    });
  }

  const forgotForm = document.getElementById('forgotForm');
  if (forgotForm) {
    forgotForm.addEventListener('submit', function(e){
      const em = forgotForm.email.value;
      if (!em.includes('@')) { alert('Masukkan email valid'); e.preventDefault(); }
    });
  }

  const otpForm = document.getElementById('otpForm');
  if (otpForm) {
    otpForm.addEventListener('submit', function(e){
      const otp = otpForm.otp.value.trim();
      if (!/^[0-9]{6}$/.test(otp)) { alert('OTP harus 6 digit angka'); e.preventDefault(); }
    });
  }
});
