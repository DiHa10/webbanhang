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
        flex-direction: row-reverse; /* Đảo ảnh sang phải */
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        max-width: 1050px;
        width: 100%;
    }
    .auth-left {
        flex: 1;
        background: linear-gradient(135deg, rgba(140, 123, 108, 0.7), rgba(26, 26, 26, 0.9)), url('https://images.unsplash.com/photo-1540932239986-30128078f3d5?q=80&w=1000&auto=format&fit=crop') center/cover;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 4rem;
        color: #fff;
        position: relative;
    }
    @media (max-width: 768px) { .auth-left { display: none; } }
    .auth-left h2 { font-family: 'Lora', serif; font-size: 2.2rem; margin-bottom: 0.5rem; text-shadow: 0 4px 10px rgba(0,0,0,0.5); }
    .auth-left p { font-size: 1rem; font-weight: 300; opacity: 0.9; text-shadow: 0 2px 4px rgba(0,0,0,0.5); }
    
    .auth-right {
        flex: 1.2;
        padding: 3rem 4rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    @media (max-width: 576px) { .auth-right { padding: 2rem; } }
    .auth-title { font-family: 'Lora', serif; font-size: 1.8rem; color: #1a1a1a; font-weight: 600; margin-bottom: 0.5rem; }
    .auth-subtitle { color: #6b7280; font-size: 0.95rem; margin-bottom: 2rem; }
    
    .form-floating { position: relative; margin-bottom: 1.25rem; }
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
        background: #8c7b6c;
        color: #fff;
        border: none;
        padding: 0.95rem;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(140, 123, 108, 0.3), 0 2px 4px -1px rgba(140, 123, 108, 0.2);
        margin-top: 1rem;
    }
    .btn-auth:hover { background: #6b5c50; transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(140, 123, 108, 0.4); }
    
    .auth-footer { margin-top: 1.5rem; text-align: center; font-size: 0.95rem; color: #4b5563; }
    .auth-footer a { color: #1a1a1a; font-weight: 600; text-decoration: none; transition: color 0.2s; border-bottom: 1px solid #1a1a1a; padding-bottom: 2px;}
    .auth-footer a:hover { color: #8c7b6c; border-bottom-color: #8c7b6c;}
    
    .error-msg { background: #fee2e2; color: #b91c1c; padding: 12px; border-radius: 8px; font-size: 0.85rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px;}
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-left">
            <h2>Gia nhập cùng chúng tôi</h2>
            <p>Hàng ngàn sản phẩm nội thất đẳng cấp đang chờ đón bạn.</p>
        </div>
        
        <div class="auth-right">
            <h1 class="auth-title">Đăng Ký Tài Khoản</h1>
            <p class="auth-subtitle">Tạo tài khoản mới hoàn toàn miễn phí</p>

            <?php if (!empty($errors)): ?>
                <div class="error-msg">
                    <i class="fas fa-exclamation-circle text-danger"></i>
                    <div>
                        <?php foreach($errors as $err): ?>
                            <div><?php echo $err; ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <form action="/webbanhang/index.php?url=account/save" method="POST">
                <div class="form-floating">
                    <input type="text" name="fullname" id="fullname" placeholder=" " value="<?php echo htmlspecialchars($_POST['fullname'] ?? ''); ?>" required />
                    <label for="fullname">Họ và tên</label>
                </div>

                <div class="form-floating">
                    <input type="text" name="username" id="username" placeholder=" " value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required />
                    <label for="username">Tên đăng nhập</label>
                </div>
                
                <div class="row">
                    <div class="col-md-6 form-floating pr-md-2" style="margin-bottom: 1.25rem;">
                        <input type="password" name="password" id="password" placeholder=" " required minlength="4"/>
                        <label for="password" style="left: 1.8rem;">Mật khẩu</label>
                    </div>
                    <div class="col-md-6 form-floating pl-md-2" style="margin-bottom: 1.25rem;">
                        <input type="password" name="confirmpassword" id="confirmpassword" placeholder=" " required />
                        <label for="confirmpassword" style="left: 1.2rem;">Xác nhận Mật khẩu</label>
                    </div>
                </div>
                
                <div style="font-size: 0.85rem; color: #6b7280; margin-top: 0.5rem; line-height:1.4;">
                    Bằng việc đăng ký, bạn đồng ý với <a href="javascript:alert('Tính năng đang phát triển');" style="color:#8c7b6c; font-weight: 500;">Điều khoản dịch vụ</a> và <a href="javascript:alert('Tính năng đang phát triển');" style="color:#8c7b6c; font-weight: 500;">Chính sách bảo mật</a> của chúng tôi.
                </div>
                
                <button type="submit" class="btn-auth">Tạo Tài Khoản</button>
                
                <div class="auth-footer">
                    Đã có tài khoản? <a href="/webbanhang/index.php?url=account/login">Đăng nhập</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../shaders/footer.php'; ?>
