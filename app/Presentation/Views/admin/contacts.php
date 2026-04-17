<?php include __DIR__ . '/../shaders/header.php'; ?>

<style>
    body { background-color: #f7f5f2; }
    .ct-page { max-width: 1100px; margin: 2.5rem auto; padding: 0 1.5rem; }
    .page-header { margin-bottom: 2rem; }
    .page-title { font-family: 'Lora', serif; font-size: 2rem; font-weight: 600; color: #1a1a1a; }
    .page-subtitle { color: #9ca3af; font-size: 0.9rem; margin-top: 4px; }

    .ct-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem; }
    .ct-stat { background: #fff; border-radius: 16px; padding: 1.5rem; border: 1px solid #f0ebe3; text-align: center; transition: transform 0.3s; }
    .ct-stat:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
    .ct-stat .s-emoji { font-size: 1.8rem; margin-bottom: 0.5rem; }
    .ct-stat .s-num { font-family: 'Lora', serif; font-size: 2rem; font-weight: 700; color: #1a1a1a; }
    .ct-stat .s-txt { font-size: 0.78rem; color: #9ca3af; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; margin-top: 0.3rem; }

    .filter-bar { display: flex; gap: 10px; margin-bottom: 1.5rem; }
    .filter-btn { padding: 8px 20px; border-radius: 30px; border: 1px solid #e5e7eb; background: #fff; color: #6b7280; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.2s; }
    .filter-btn.active, .filter-btn:hover { background: #1a1a1a; color: #fff; border-color: #1a1a1a; }

    .ct-list { display: flex; flex-direction: column; gap: 1rem; }

    .ct-card {
        background: #fff; border-radius: 16px; border: 1px solid #f0ebe3;
        overflow: hidden; transition: all 0.3s; box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }
    .ct-card:hover { box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
    .ct-card-header {
        display: flex; justify-content: space-between; align-items: center;
        padding: 1.2rem 1.5rem; cursor: pointer; user-select: none;
    }
    .ct-card-header:hover { background: #faf9f8; }
    .ct-sender { display: flex; align-items: center; gap: 12px; }
    .ct-avatar { width: 42px; height: 42px; border-radius: 12px; background: #f0ebe3; display: flex; align-items: center; justify-content: center; color: #8c7b6c; font-size: 1.1rem; }
    .ct-name { font-weight: 600; color: #1a1a1a; }
    .ct-subject { font-size: 0.85rem; color: #9ca3af; }
    .ct-meta { display: flex; align-items: center; gap: 12px; }
    .ct-date { font-size: 0.8rem; color: #9ca3af; }
    .ct-status-badge { padding: 4px 12px; border-radius: 30px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .status-pending { background: #fef3c7; color: #d97706; }
    .status-replied { background: #d1fae5; color: #059669; }

    .ct-card-body { display: none; padding: 0 1.5rem 1.5rem; border-top: 1px solid #f0ebe3; }
    .ct-card.open .ct-card-body { display: block; }

    .ct-info-row { display: flex; gap: 2rem; margin-top: 1rem; margin-bottom: 1rem; flex-wrap: wrap; }
    .ct-info-item { font-size: 0.85rem; color: #6b7280; }
    .ct-info-item i { color: #8c7b6c; margin-right: 6px; width: 16px; text-align: center; }
    .ct-info-item strong { color: #1a1a1a; }

    .ct-message { background: #faf9f8; padding: 1.2rem; border-radius: 12px; font-size: 0.95rem; color: #1a1a1a; line-height: 1.7; margin-bottom: 1rem; border-left: 4px solid #8c7b6c; }

    .ct-reply-box { background: #f0fdf4; padding: 1.2rem; border-radius: 12px; margin-bottom: 1rem; border-left: 4px solid #10b981; }
    .ct-reply-box .reply-by { font-size: 0.8rem; color: #059669; font-weight: 600; margin-bottom: 0.3rem; }
    .ct-reply-box .reply-text { font-size: 0.95rem; color: #1a1a1a; line-height: 1.7; }
    .ct-reply-box .reply-time { font-size: 0.75rem; color: #9ca3af; margin-top: 0.5rem; }

    .reply-form { display: flex; gap: 10px; margin-top: 1rem; }
    .reply-input { flex: 1; padding: 0.8rem 1rem; border: 1px solid #e5e7eb; border-radius: 12px; font-size: 0.9rem; outline: none; resize: none; min-height: 44px; transition: border 0.3s; }
    .reply-input:focus { border-color: #8c7b6c; }
    .btn-reply { padding: 0.8rem 1.5rem; background: #1a1a1a; color: #fff; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s; white-space: nowrap; }
    .btn-reply:hover { background: #059669; }
    .btn-delete-ct { background: none; border: none; color: #d1d5db; cursor: pointer; font-size: 0.9rem; transition: 0.2s; padding: 6px; border-radius: 8px; }
    .btn-delete-ct:hover { color: #ef4444; background: #fef2f2; }

    .ct-empty { text-align: center; padding: 4rem; color: #d1d5db; background: #fff; border-radius: 16px; border: 1px solid #f0ebe3; }
    .ct-empty i { font-size: 3rem; margin-bottom: 1rem; }

    @media (max-width: 768px) { .ct-stats { grid-template-columns: 1fr; } .ct-info-row { flex-direction: column; gap: 0.5rem; } }
</style>

<div class="ct-page">
    <div class="page-header">
        <h1 class="page-title"><i class="fas fa-headset" style="color: #8c7b6c; margin-right: 10px;"></i>Trả Lời Liên Hệ</h1>
        <p class="page-subtitle">Quản lý và phản hồi liên hệ từ khách hàng</p>
    </div>

    <div class="ct-stats">
        <div class="ct-stat"><div class="s-emoji">📩</div><div class="s-num" id="st-all">-</div><div class="s-txt">Tổng liên hệ</div></div>
        <div class="ct-stat"><div class="s-emoji">⏳</div><div class="s-num" id="st-pending">-</div><div class="s-txt">Chờ trả lời</div></div>
        <div class="ct-stat"><div class="s-emoji">✅</div><div class="s-num" id="st-replied">-</div><div class="s-txt">Đã trả lời</div></div>
    </div>

    <div class="filter-bar">
        <button class="filter-btn active" data-filter="all">Tất cả</button>
        <button class="filter-btn" data-filter="pending">Chờ trả lời</button>
        <button class="filter-btn" data-filter="replied">Đã trả lời</button>
    </div>

    <div class="ct-list" id="ctList">
        <div class="ct-empty"><i class="fas fa-spinner fa-spin"></i><p>Đang tải...</p></div>
    </div>
</div>

<script>
let allContacts = [];
let currentFilter = 'all';

function loadContacts() {
    $.getJSON('/webbanhang/index.php?url=admin/listContacts', function(data) {
        allContacts = data;
        renderContacts();
        updateStats();
    });
}

function updateStats() {
    $('#st-all').text(allContacts.length);
    $('#st-pending').text(allContacts.filter(c => c.status === 'pending').length);
    $('#st-replied').text(allContacts.filter(c => c.status === 'replied').length);
}

function renderContacts() {
    const list = $('#ctList');
    let data = allContacts;
    if (currentFilter !== 'all') data = data.filter(c => c.status === currentFilter);

    if (!data.length) {
        list.html('<div class="ct-empty"><i class="fas fa-inbox"></i><p>Không có liên hệ nào.</p></div>');
        return;
    }

    let html = '';
    data.forEach(c => {
        const date = new Date(c.created_at).toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        const statusClass = c.status === 'replied' ? 'status-replied' : 'status-pending';
        const statusText = c.status === 'replied' ? 'Đã trả lời' : 'Chờ trả lời';

        let replySection = '';
        if (c.reply) {
            const replyDate = c.replied_at ? new Date(c.replied_at).toLocaleDateString('vi-VN', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' }) : '';
            replySection = `
                <div class="ct-reply-box">
                    <div class="reply-by"><i class="fas fa-reply me-1"></i>Trả lời bởi @${c.replied_by || 'staff'}</div>
                    <div class="reply-text">${c.reply}</div>
                    <div class="reply-time">${replyDate}</div>
                </div>
            `;
        }

        html += `
            <div class="ct-card" data-id="${c.id}">
                <div class="ct-card-header" onclick="$(this).parent().toggleClass('open')">
                    <div class="ct-sender">
                        <div class="ct-avatar"><i class="fas fa-user"></i></div>
                        <div>
                            <div class="ct-name">${c.name}</div>
                            <div class="ct-subject">${c.subject || 'Không có chủ đề'}</div>
                        </div>
                    </div>
                    <div class="ct-meta">
                        <span class="ct-date">${date}</span>
                        <span class="ct-status-badge ${statusClass}">${statusText}</span>
                        <button class="btn-delete-ct" onclick="event.stopPropagation(); deleteCt(${c.id})" title="Xóa"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <div class="ct-card-body">
                    <div class="ct-info-row">
                        <div class="ct-info-item"><i class="fas fa-envelope"></i><strong>${c.email || '—'}</strong></div>
                        <div class="ct-info-item"><i class="fas fa-phone"></i><strong>${c.phone || '—'}</strong></div>
                    </div>
                    <div class="ct-message">${c.message}</div>
                    ${replySection}
                    <form class="reply-form" onsubmit="replyCt(event, ${c.id})">
                        <textarea class="reply-input" id="reply-${c.id}" placeholder="${c.reply ? 'Cập nhật trả lời...' : 'Nhập nội dung trả lời...'}" required></textarea>
                        <button type="submit" class="btn-reply"><i class="fas fa-paper-plane me-1"></i>${c.reply ? 'Cập nhật' : 'Trả lời'}</button>
                    </form>
                </div>
            </div>
        `;
    });
    list.html(html);
}

function replyCt(e, id) {
    e.preventDefault();
    const reply = $(`#reply-${id}`).val().trim();
    if (!reply) return;

    const btn = $(`#reply-${id}`).closest('form').find('.btn-reply');
    btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

    $.ajax({
        url: '/webbanhang/index.php?url=admin/replyContact',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ id, reply }),
        success: function(res) {
            if (res.success) loadContacts();
            else alert(res.message || 'Lỗi!');
        }
    });
}

function deleteCt(id) {
    if (!confirm('Xóa liên hệ này?')) return;
    $.ajax({
        url: '/webbanhang/index.php?url=admin/deleteContact',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ id }),
        success: function(res) {
            if (res.success) loadContacts();
            else alert(res.message || 'Lỗi!');
        }
    });
}

$('.filter-btn').on('click', function() {
    $('.filter-btn').removeClass('active');
    $(this).addClass('active');
    currentFilter = $(this).data('filter');
    renderContacts();
});

$(document).ready(loadContacts);
</script>

<?php include __DIR__ . '/../shaders/footer.php'; ?>
