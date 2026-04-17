<?php include_once __DIR__ . '/../shaders/header.php'; ?>

<style>
    body { background-color: #f7f5f2; }

    /* ===== HERO ===== */
    .contact-hero {
        position: relative;
        height: 50vh;
        min-height: 350px;
        background: linear-gradient(rgba(26,26,26,0.55), rgba(26,26,26,0.75)),
                    url('https://images.unsplash.com/photo-1497366811353-6870744d04b2?q=80&w=1600&auto=format&fit=crop') center/cover no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: #fff;
        margin-top: -1.5rem;
    }
    .contact-hero h1 {
        font-family: 'Lora', serif;
        font-size: 3.5rem;
        font-weight: 500;
        margin-bottom: 0.75rem;
        animation: fadeInUp 1s ease;
    }
    .contact-hero p {
        font-size: 1.15rem;
        font-weight: 300;
        letter-spacing: 2px;
        text-transform: uppercase;
        opacity: 0.9;
        animation: fadeInUp 1.3s ease;
    }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

    /* ===== MAIN CONTENT ===== */
    .contact-section { padding: 5rem 0; }

    .contact-layout {
        display: flex;
        gap: 3rem;
        flex-wrap: wrap;
    }

    /* LEFT: Info Cards */
    .contact-info-side { flex: 1; min-width: 320px; display: flex; flex-direction: column; gap: 1.5rem; }
    .info-card {
        background: #fff;
        border-radius: 16px;
        padding: 2rem;
        display: flex;
        align-items: flex-start;
        gap: 1.5rem;
        border: 1px solid #f0ebe3;
        transition: all 0.4s ease;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.03);
    }
    .info-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.06); }
    .info-icon {
        width: 55px; height: 55px; flex-shrink: 0;
        background: #faf9f8; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        color: #8c7b6c; font-size: 1.25rem;
        transition: all 0.4s;
    }
    .info-card:hover .info-icon { background: #8c7b6c; color: #fff; transform: scale(1.1); }
    .info-details h4 { font-family: 'Lora', serif; font-size: 1.15rem; font-weight: 600; color: #1a1a1a; margin-bottom: 0.4rem; }
    .info-details p { color: #6b7280; font-size: 0.95rem; line-height: 1.7; margin: 0; }
    .info-details a { color: #8c7b6c; text-decoration: none; font-weight: 500; }
    .info-details a:hover { color: #6b5c50; text-decoration: underline; }

    /* RIGHT: Contact Form */
    .contact-form-side {
        flex: 1.4;
        min-width: 380px;
        background: #fff;
        border-radius: 20px;
        padding: 3.5rem;
        border: 1px solid #f0ebe3;
        box-shadow: 0 20px 40px -10px rgba(0,0,0,0.04);
    }
    .form-title {
        font-family: 'Lora', serif;
        font-size: 1.8rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
    }
    .form-subtitle { color: #6b7280; font-size: 0.95rem; margin-bottom: 2.5rem; line-height: 1.6; }

    .form-floating { position: relative; margin-bottom: 1.5rem; }
    .form-floating input, .form-floating textarea {
        width: 100%; height: 58px;
        padding: 1.625rem 1rem 0.625rem;
        border: 1px solid #e5e7eb; border-radius: 12px;
        font-size: 1rem; background: #fff; color: #111827;
        transition: all 0.2s ease-in-out; outline: none;
        box-sizing: border-box; line-height: 1.25;
    }
    .form-floating textarea { min-height: 140px; padding-top: 1.625rem; resize: vertical; }
    .form-floating input:focus, .form-floating textarea:focus {
        border-color: #8c7b6c; background: #fff; box-shadow: 0 0 0 4px rgba(140, 123, 108, 0.08);
    }
    .form-floating label {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        padding: 1rem; color: #9ca3af; pointer-events: none;
        transition: transform .2s ease-out, color .2s ease-out;
        transform-origin: 0 0; box-sizing: border-box;
    }
    .form-floating textarea + label { height: auto; }
    .form-floating input:focus + label, .form-floating input:not(:placeholder-shown) + label,
    .form-floating textarea:focus + label, .form-floating textarea:not(:placeholder-shown) + label {
        transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem); color: #8c7b6c;
    }

    .btn-send {
        width: 100%;
        background: #8c7b6c;
        color: #fff;
        border: none;
        padding: 1.15rem;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 10px rgba(140, 123, 108, 0.3);
        display: flex; align-items: center; justify-content: center; gap: 10px;
    }
    .btn-send:hover { background: #6b5c50; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(140, 123, 108, 0.4); }

    /* ===== MAP ===== */
    .map-section {
        padding: 0 0 5rem;
    }
    .map-wrapper {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        border: 1px solid #f0ebe3;
    }
    .map-wrapper iframe { width: 100%; height: 400px; border: 0; display: block; }

    @media (max-width: 768px) {
        .contact-hero h1 { font-size: 2.5rem; }
        .contact-form-side { padding: 2.5rem; }
    }
</style>

<!-- HERO -->
<div class="contact-hero">
    <div>
        <h1>Liên Hệ Với Chúng Tôi</h1>
        <p>Chúng tôi luôn sẵn lòng lắng nghe bạn</p>
    </div>
</div>

<!-- MAIN -->
<div class="container contact-section">
    <div class="contact-layout">
        <!-- LEFT: Info Cards -->
        <div class="contact-info-side">
            <div class="info-card">
                <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div class="info-details">
                    <h4>Showroom Chính</h4>
                    <p>123 Nguyễn Huệ, Quận 1<br>TP. Hồ Chí Minh, Việt Nam</p>
                </div>
            </div>
            <div class="info-card">
                <div class="info-icon"><i class="fas fa-phone-alt"></i></div>
                <div class="info-details">
                    <h4>Hotline Tư Vấn</h4>
                    <p><a href="tel:+84987654321">0987 654 321</a><br>
                    Thứ 2 — Thứ 7 | 8:00 — 21:00</p>
                </div>
            </div>
            <div class="info-card">
                <div class="info-icon"><i class="fas fa-envelope"></i></div>
                <div class="info-details">
                    <h4>Email</h4>
                    <p><a href="mailto:contact@noithatviet.vn">contact@noithatviet.vn</a><br>
                    Phản hồi trong 24 giờ làm việc</p>
                </div>
            </div>
            <div class="info-card">
                <div class="info-icon"><i class="fas fa-clock"></i></div>
                <div class="info-details">
                    <h4>Giờ Mở Cửa</h4>
                    <p>Thứ 2 — Thứ 6: 8:00 — 21:00<br>
                    Thứ 7 — CN: 9:00 — 18:00</p>
                </div>
            </div>
        </div>

        <!-- RIGHT: Form -->
        <div class="contact-form-side">
            <h2 class="form-title">Gửi Lời Nhắn</h2>
            <p class="form-subtitle">Hãy để lại thông tin, đội ngũ tư vấn sẽ liên hệ với bạn trong thời gian sớm nhất.</p>

            <form id="contact-form">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" id="ct_name" placeholder=" " required>
                            <label for="ct_name">Họ và tên</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="tel" id="ct_phone" placeholder=" " required>
                            <label for="ct_phone">Số điện thoại</label>
                        </div>
                    </div>
                </div>

                <div class="form-floating">
                    <input type="email" id="ct_email" placeholder=" " required>
                    <label for="ct_email">Địa chỉ email</label>
                </div>

                <div class="form-floating">
                    <input type="text" id="ct_subject" placeholder=" ">
                    <label for="ct_subject">Chủ đề (Tùy chọn)</label>
                </div>

                <div class="form-floating">
                    <textarea id="ct_message" placeholder=" " required></textarea>
                    <label for="ct_message">Nội dung lời nhắn</label>
                </div>

                <button type="submit" class="btn-send" id="sendBtn">
                    <span>Gửi Tin Nhắn</span> <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- MAP -->
<div class="container map-section">
    <div class="map-wrapper">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.447!2d106.700!3d10.773!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f38e!2sNguy%E1%BB%85n%20Hu%E1%BB%87!5e0!3m2!1svi!2svn!4v1" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>

<?php include_once __DIR__ . '/../shaders/footer.php'; ?>

<script>
$('#contact-form').on('submit', function(e) {
    e.preventDefault();
    const btn = $('#sendBtn');
    const origHtml = btn.html();
    btn.html('<i class="fas fa-spinner fa-spin"></i> Đang gửi...').css('pointer-events', 'none');

    $.post('/webbanhang/index.php?url=home/saveContact', {
        name: $('#ct_name').val(),
        phone: $('#ct_phone').val(),
        email: $('#ct_email').val(),
        subject: $('#ct_subject').val(),
        message: $('#ct_message').val()
    }, function(res) {
        if (res.success) {
            btn.html('<i class="fas fa-check"></i> Đã gửi thành công!').css('background', '#10b981');
            $('#contact-form')[0].reset();
            setTimeout(function() {
                btn.html(origHtml).css({'background': '#8c7b6c', 'pointer-events': 'auto'});
            }, 3000);
        } else {
            alert(res.message || 'Lỗi gửi!');
            btn.html(origHtml).css('pointer-events', 'auto');
        }
    }, 'json').fail(function() {
        alert('Lỗi kết nối!');
        btn.html(origHtml).css('pointer-events', 'auto');
    });
});
</script>
