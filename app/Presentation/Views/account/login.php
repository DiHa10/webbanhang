<?php include_once __DIR__ . '/../shaders/header.php'; ?>
<style>
    body { background-color: #f7f5f2; }
    .auth-wrapper {
        min-height: calc(100vh - 56px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }
    .auth-card {
        display: flex;
        flex-direction: row;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        max-width: 1000px;
        width: 100%;
    }
    .auth-left {
        flex: 1;
        background: linear-gradient(135deg, rgba(140, 123, 108, 0.9), rgba(26, 26, 26, 0.8)), url('https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=1000&auto=format&fit=crop') center/cover;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 4rem;
        color: #fff;
        position: relative;
    }
    @media (max-width: 768px) { .auth-left { display: none; } }
    .auth-left h2 { font-family: 'Lora', serif; font-size: 2.5rem; margin-bottom: 1rem; text-shadow: 0 4px 10px rgba(0,0,0,0.3); }
    .auth-left p { font-size: 1.05rem; font-weight: 300; opacity: 0.9; line-height: 1.6; }
    
    .auth-right {
        flex: 1;
        padding: 4rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    @media (max-width: 576px) { .auth-right { padding: 2rem; } }
    .auth-title { font-family: 'Lora', serif; font-size: 2rem; color: #1a1a1a; font-weight: 600; margin-bottom: 0.5rem; }
    .auth-subtitle { color: #6b7280; font-size: 0.95rem; margin-bottom: 2.5rem; }
    
    .form-floating { position: relative; margin-bottom: 1.5rem; }
    .form-floating input {
        width: 100%; height: 58px;
        padding: 1.625rem 1rem 0.625rem;
        border: 1px solid #e5e7eb; border-radius: 12px;
        font-size: 1rem; background: #fff; color: #111827;
        transition: all 0.2s ease-in-out; outline: none;
        box-sizing: border-box; line-height: 1.25;
    }
    .form-floating input:focus { border-color: #8c7b6c; box-shadow: 0 0 0 4px rgba(140, 123, 108, 0.1); }
    .form-floating label {
        position: absolute; top: 0; left: 0;
        width: 100%; height: 100%;
        padding: 1rem;
        color: #9ca3af; pointer-events: none;
        transition: transform .2s ease-out, color .2s ease-out;
        transform-origin: 0 0; box-sizing: border-box;
    }
    .form-floating input:focus + label, .form-floating input:not(:placeholder-shown) + label {
        transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
        color: #8c7b6c;
    }
    
    .btn-auth {
        width: 100%;
        background: #1a1a1a;
        color: #fff;
        border: none;
        padding: 1rem;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.3s easel
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .btn-auth:hover { background: #333; transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
    
    .auth-footer { margin-top: 2rem; text-align: center; font-size: 0.95rem; color: #4b5563; }
    .auth-footer a { color: #8c7b6c; font-weight: 600; text-decoration: none; transition: color 0.2s; }
    .auth-footer a:hover { color: #6b5c50; text-decoration: underline; }
    
    /* Social Login */
    .social-divider { display: flex; align-items: center; text-align: center; margin: 2rem 0; color: #9ca3af; font-size: 0.85rem; }
    .social-divider::before, .social-divider::after { content: ''; flex: 1; border-bottom: 1px solid #e5e7eb; }
    .social-divider:not(:empty)::before { margin-right: .5em; }
    .social-divider:not(:empty)::after { margin-left: .5em; }
    
    .social-btn {
        display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%;
        padding: 0.8rem; background: #fff; border: 1px solid #e5e7eb; border-radius: 12px;
        color: #374151; font-weight: 500; cursor: pointer; transition: background 0.2s, border-color 0.2s;
    }
    .social-btn:hover { background: #f9fafb; border-color: #d1d5db; }
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-left">
            <h2>Đánh thức không gian sống</h2>
            <p>Trải nghiệm mua sắm nội thất trực tuyến tiện lợi, an toàn và đầy cảm hứng với Nội Thất Hiện Đại.</p>
        </div>
        
        <div class="auth-right">
            <h1 class="auth-title">Chào mừng trở lại!</h1>
            <p class="auth-subtitle">Vui lòng đăng nhập để tiếp tục khám phá</p>

            <form id="login-form">
                <div class="form-floating">
                    <input type="text" name="username" id="username" placeholder=" " required />
                    <label for="username">Tên đăng nhập</label>
                </div>
                
                <div class="form-floating">
                    <input type="password" name="password" id="password" placeholder=" " required />
                    <label for="password">Mật khẩu</label>
                </div>
                
                <div class="d-flex justify-content-end mb-4">
                    <a href="javascript:alert('Tính năng quên mật khẩu đang phát triển!');" style="font-size: 0.85rem; color: #8c7b6c; font-weight: 500; text-decoration: none;">Quên mật khẩu?</a>
                </div>
                
                <button type="submit" class="btn-auth" id="loginBtn">Đăng Nhập <i class="fas fa-arrow-right ml-2"></i></button>
                
                <div class="social-divider">HOẶC</div>
                <button type="button" class="social-btn" onclick="alert('Đăng nhập bằng Google đang phát triển')">
                    <i class="fab fa-google text-danger"></i> Tiếp tục với Google
                </button>
                
                <div class="auth-footer">
                    Chưa có tài khoản? <a href="/webbanhang/index.php?url=account/register">Đăng ký ngay</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../shaders/footer.php'; ?>

<script>
document.getElementById('login-form').addEventListener('submit', function(event) {
    event.preventDefault();
    const btn = document.getElementById('loginBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    btn.style.pointerEvents = 'none';

    const formData = new FormData(this);
    const jsonData = {};
    formData.forEach((value, key) => jsonData[key] = value);

    fetch('/webbanhang/index.php?url=account/checkLogin', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(jsonData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            btn.innerHTML = '<i class="fas fa-check"></i> Thành công';
            btn.style.background = '#10b981';
            setTimeout(() => { location.href = '/webbanhang/index.php?url=product'; }, 500);
        } else {
            alert('Tên đăng nhập hoặc mật khẩu không chính xác!');
            btn.innerHTML = originalText;
            btn.style.pointerEvents = 'auto';
        }
    })
    .catch(() => {
        alert('Lỗi kết nối máy chủ!');
        btn.innerHTML = originalText;
        btn.style.pointerEvents = 'auto';
    });
});
</script>