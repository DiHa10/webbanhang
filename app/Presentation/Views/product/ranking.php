<?php include_once __DIR__ . '/../shaders/header.php'; ?>

<style>
    body { background-color: #f7f5f2; }
    .ranking-page { max-width: 1300px; margin: 3rem auto; padding: 0 1.5rem; }
    
    .ranking-header { text-align: center; margin-bottom: 4rem; }
    .ranking-header h1 { font-family: 'Lora', serif; font-size: 3rem; font-weight: 600; color: #1a1a1a; margin-bottom: 1rem; }
    .ranking-header p { font-size: 1.1rem; color: #6b7280; max-width: 600px; margin: 0 auto; line-height: 1.6; }

    /* Category Columns Layout */
    .cat-columns-wrapper {
        display: flex; flex-wrap: wrap; gap: 2rem; justify-content: center;
    }
    
    .cat-column {
        flex: 1; min-width: 300px; max-width: 400px;
        background: #fff; border-radius: 20px; padding: 1.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid #f0ebe3;
        display: flex; flex-direction: column;
    }
    
    .cat-title-wrap {
        text-align: center; margin-bottom: 1.5rem; padding-bottom: 1rem;
        border-bottom: 2px dashed #f0ebe3;
    }
    .cat-title-badge {
        display: inline-block; background: #8c7b6c; color: #fff; text-transform: uppercase;
        font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; padding: 5px 15px; border-radius: 30px; margin-bottom: 10px;
    }
    .cat-title {
        font-family: 'Lora', serif; font-size: 1.5rem; font-weight: 600; color: #1a1a1a; margin: 0;
    }

    /* List Item within column */
    .rank-list-item {
        display: flex; align-items: center; padding: 1rem 0; border-bottom: 1px solid #f9fafb; transition: transform 0.2s; position: relative;
    }
    .rank-list-item:hover { transform: translateX(5px); }
    .rank-list-item:last-child { border-bottom: none; }
    
    .item-rank-num {
        width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-family: 'Lora', serif; font-size: 1.1rem; font-weight: 700; color: #fff; margin-right: 1rem; flex-shrink: 0;
    }
    .rank-1-color { background: linear-gradient(135deg, #fcd34d, #f59e0b); box-shadow: 0 4px 10px rgba(245, 158, 11, 0.3); width: 45px; height: 45px; font-size: 1.4rem;}
    .rank-2-color { background: linear-gradient(135deg, #e2e8f0, #94a3b8); box-shadow: 0 4px 10px rgba(148, 163, 184, 0.3); width: 40px; height: 40px; font-size: 1.25rem;}
    .rank-3-color { background: linear-gradient(135deg, #fdba74, #ea580c); box-shadow: 0 4px 10px rgba(234, 88, 12, 0.3); width: 40px; height: 40px; font-size: 1.25rem;}
    .rank-other-color { background: #f3f4f6; color: #9ca3af; }

    .item-img-wrap { width: 60px; height: 60px; border-radius: 12px; overflow: hidden; margin-right: 1rem; flex-shrink: 0; background: #faf9f8;}
    .item-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
    
    .item-info { flex: 1; overflow: hidden; }
    .item-info a { text-decoration: none; color: inherit; }
    .item-name { font-family: 'Lora', serif; font-size: 1rem; font-weight: 600; color: #1a1a1a; margin-bottom: 0.2rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; transition: color 0.2s;}
    .item-info a:hover .item-name { color: #8c7b6c; }
    
    .item-sold { font-size: 0.85rem; color: #d97706; font-weight: 600; }
    .item-sold i { margin-right: 4px; }
</style>

<div class="ranking-page">
    <div class="ranking-header">
        <h1>Bảng Xếp Hạng Theo Danh Mục</h1>
        <div style="margin: 1rem 0; display:inline-flex; align-items:center; gap:8px; background:#fef3c7; color:#92400e; padding:8px 20px; border-radius:30px; font-weight:600; font-size:0.95rem;">
            <i class="fas fa-calendar-alt"></i>
            Tháng <?php echo date('m'); ?> / <?php echo date('Y'); ?>
        </div>
        <p>Bảng xếp hạng các sản phẩm nội thất bán chạy nhất trong tháng đối với từng hạng mục, từ cao tới thấp.</p>
    </div>

    <?php if (empty($categorizedRankings)): ?>
        <div style="text-align:center; padding: 5rem; background: #fff; border-radius: 20px;">
            <i class="fas fa-box-open fa-4x" style="color: #d1d5db; margin-bottom: 1rem;"></i>
            <h3 style="color: #6b7280; font-family: 'Lora', serif;">Chưa có dữ liệu bán hàng tháng này.</h3>
        </div>
    <?php else: ?>
        <div class="cat-columns-wrapper">
            <?php foreach ($categorizedRankings as $catName => $products): ?>
            <div class="cat-column">
                <div class="cat-title-wrap">
                    <div class="cat-title-badge">Top Sản Phẩm</div>
                    <h2 class="cat-title"><?php echo htmlspecialchars($catName); ?></h2>
                </div>
                
                <div class="cat-list-wrapper">
                    <?php 
                    // Render top 5 products per category for the column view
                    $topProductsInCat = array_slice($products, 0, 5);
                    foreach ($topProductsInCat as $index => $p): 
                        $rank = $index + 1;
                        if ($rank == 1) $rClass = 'rank-1-color';
                        else if ($rank == 2) $rClass = 'rank-2-color';
                        else if ($rank == 3) $rClass = 'rank-3-color';
                        else $rClass = 'rank-other-color';

                        $imgSrc = $p['image'] ? (strpos($p['image'], 'http') === 0 ? $p['image'] : '/webbanhang/' . $p['image']) : 'https://via.placeholder.com/100?text=None';
                    ?>
                    <div class="rank-list-item">
                        <div class="item-rank-num <?php echo $rClass; ?>"><?php echo $rank; ?></div>
                        <div class="item-img-wrap">
                            <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                        </div>
                        <div class="item-info">
                            <a href="/webbanhang/index.php?url=product/show/<?php echo $p['id']; ?>" title="<?php echo htmlspecialchars($p['name']); ?>">
                                <div class="item-name"><?php echo htmlspecialchars($p['name']); ?></div>
                            </a>
                            <div class="item-sold"><i class="fas fa-fire"></i>Đã bán: <?php echo $p['total_sold']; ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include_once __DIR__ . '/../shaders/footer.php'; ?>
