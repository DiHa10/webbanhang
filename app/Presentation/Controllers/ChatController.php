<?php
namespace App\Presentation\Controllers;

use App\DAL\Database;
use PDO;

class ChatController {

    private $hfApiKey = 'YOUR_HUGGING_FACE_API_KEY';
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function send() {
        header('Content-Type: application/json; charset=utf-8');
        
        $input = json_decode(file_get_contents('php://input'), true);
        $message = trim($input['message'] ?? '');
        
        if (empty($message)) {
            echo json_encode(['reply' => 'Vui lòng nhập câu hỏi!']);
            return;
        }

        // 1. Extract keywords and query products from DB
        $products = $this->searchProducts($message);
        $productContext = $this->formatProductContext($products);

        // 2. Build system prompt with product data
        $systemPrompt = $this->getSystemPrompt($productContext);

        // 3. Call LLM API
        $reply = $this->callLLM($systemPrompt, $message);

        echo json_encode(['reply' => $reply]);
    }

    private function searchProducts($message) {
        $message = mb_strtolower($message);
        
        $categoryMap = [
            'giường' => 'Giường', 'giuong' => 'Giường', 'bed' => 'Giường',
            'đèn' => 'Đèn', 'den' => 'Đèn', 'lamp' => 'Đèn', 'light' => 'Đèn',
            'ghế' => 'Ghế', 'ghe' => 'Ghế', 'chair' => 'Ghế',
            'tủ' => 'Tủ', 'tu' => 'Tủ', 'cabinet' => 'Tủ', 'tủ quần áo' => 'Tủ',
            'bàn' => 'Bàn', 'ban' => 'Bàn', 'table' => 'Bàn', 'desk' => 'Bàn',
        ];

        $matchedCategory = null;
        foreach ($categoryMap as $keyword => $catName) {
            if (mb_strpos($message, $keyword) !== false) {
                $matchedCategory = $catName;
                break;
            }
        }

        $maxPrice = null;
        if (preg_match('/dưới\s*([\d,.]+)\s*(triệu|tr)/iu', $message, $m)) {
            $maxPrice = floatval(str_replace([',', '.'], '', $m[1])) * 1000000;
        } elseif (preg_match('/([\d,.]+)\s*(triệu|tr)/iu', $message, $m)) {
            $maxPrice = floatval(str_replace([',', '.'], '', $m[1])) * 1000000;
        }

        $sql = "SELECT p.id, p.name, p.price, p.stock, p.description, c.name as category_name 
                FROM product p 
                LEFT JOIN category c ON p.category_id = c.id 
                WHERE p.stock > 0";
        $params = [];

        if ($matchedCategory) {
            $sql .= " AND c.name = ?";
            $params[] = $matchedCategory;
        }

        if ($maxPrice) {
            $sql .= " AND p.price <= ?";
            $params[] = $maxPrice;
        }

        $sql .= " ORDER BY p.price ASC LIMIT 10";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function formatProductContext($products) {
        if (empty($products)) {
            $stmt = $this->db->prepare("
                SELECT p.id, p.name, p.price, p.stock, c.name as category_name 
                FROM product p LEFT JOIN category c ON p.category_id = c.id 
                WHERE p.stock > 0 
                ORDER BY RAND() LIMIT 15
            ");
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        if (empty($products)) return "Hiện tại không có sản phẩm nào trong kho.";

        $lines = [];
        foreach ($products as $p) {
            $price = number_format($p['price'], 0, ',', '.');
            $url = "/webbanhang/index.php?url=product/show/{$p['id']}";
            $lines[] = "- [{$p['name']}]({$url}) | Loại: {$p['category_name']} | Giá: {$price}₫ | Còn: {$p['stock']} sản phẩm";
        }
        return implode("\n", $lines);
    }

    private function getSystemPrompt($productContext) {
        return "Bạn là \"Nội Thất AI\" - chuyên gia tư vấn thiết kế nội thất của website Nội Thất Hiện Đại.

QUY TẮC BẮT BUỘC:
1. CHỈ trả lời câu hỏi liên quan đến nội thất, thiết kế phòng, đồ nội thất, sản phẩm của cửa hàng.
2. Nếu câu hỏi KHÔNG liên quan đến nội thất → từ chối lịch sự: \"Xin lỗi, tôi chỉ hỗ trợ tư vấn về nội thất và sản phẩm của cửa hàng thôi ạ! 😊\"
3. Khi gợi ý sản phẩm, LUÔN dùng format markdown link: [Tên sản phẩm](link) - Giá tiền
4. Trả lời ngắn gọn (tối đa 200 từ), thân thiện, chuyên nghiệp, dùng emoji phù hợp.
5. Ưu tiên gợi ý sản phẩm có trong DANH SÁCH bên dưới.
6. Nếu khách hỏi chung chung, hãy hỏi thêm về phong cách, ngân sách, kích thước phòng.
7. Xưng hô: \"em\" (chatbot) và \"anh/chị\" (khách hàng).

DANH SÁCH SẢN PHẨM HIỆN CÓ:
{$productContext}

Website: Nội Thất Hiện Đại - chuyên giường, đèn, ghế, tủ, bàn cao cấp.";
    }

    private function callLLM($systemPrompt, $userMessage) {
        $url = "https://router.huggingface.co/v1/chat/completions";
        $models = ['Qwen/Qwen2.5-72B-Instruct', 'meta-llama/Llama-3.1-8B-Instruct'];

        foreach ($models as $model) {
            $payload = [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage]
                ],
                'max_tokens' => 500,
                'temperature' => 0.7,
            ];

            for ($retry = 0; $retry < 2; $retry++) {
                $ch = curl_init($url);
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_HTTPHEADER => [
                        'Content-Type: application/json',
                        "Authorization: Bearer {$this->hfApiKey}"
                    ],
                    CURLOPT_POSTFIELDS => json_encode($payload),
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_SSL_VERIFYPEER => false,
                ]);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode === 200) {
                    $data = json_decode($response, true);
                    $text = $data['choices'][0]['message']['content'] ?? null;
                    if ($text) return $text;
                }

                if ($httpCode === 429) {
                    sleep(2);
                    continue;
                }

                break;
            }
        }

        error_log("LLM API failed for all models");
        return "Xin lỗi, hệ thống đang bận. Vui lòng thử lại sau vài giây ạ! 🙏";
    }

    public function index() {
        echo json_encode(['status' => 'Chatbot API ready']);
    }
}
