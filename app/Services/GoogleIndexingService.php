<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleIndexingService
{
    protected $clientEmail;
    protected $privateKey;

    public function __construct()
    {
        $path = public_path('google-service-account.json');
        if (file_exists($path)) {
            $creds = json_decode(file_get_contents($path), true);
            $this->clientEmail = $creds['client_email'] ?? null;
            $this->privateKey = $creds['private_key'] ?? null;
        }
    }

    /**
     * Kiểm tra xem cấu hình Service Account đã đầy đủ chưa
     */
    public function isConfigured(): bool
    {
        return !empty($this->clientEmail) && !empty($this->privateKey);
    }

    /**
     * Tạo signed JWT và đổi lấy OAuth 2.0 Access Token từ Google
     */
    protected function getAccessToken(): string
    {
        if (!$this->isConfigured()) {
            throw new \Exception("Google Service Account JSON key chưa được cấu hình hoặc bị thiếu.");
        }

        $now = time();
        $header = $this->base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $payload = $this->base64UrlEncode(json_encode([
            'iss' => $this->clientEmail,
            'scope' => 'https://www.googleapis.com/auth/indexing',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now
        ]));

        $signature = '';
        $success = openssl_sign(
            "$header.$payload",
            $signature,
            $this->privateKey,
            OPENSSL_ALGO_SHA256
        );

        if (!$success) {
            throw new \Exception("Không thể ký mã JWT bằng khóa riêng tư Private Key. Vui lòng kiểm tra lại định dạng khóa.");
        }

        $signedJwt = "$header.$payload." . $this->base64UrlEncode($signature);

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $signedJwt,
        ]);

        if ($response->failed()) {
            throw new \Exception("Yêu cầu mã Access Token thất bại từ Google: " . $response->body());
        }

        return $response->json('access_token');
    }

    /**
     * Gửi yêu cầu lập chỉ mục (Publish URL) sang Google Indexing API
     *
     * @param string $url URL cần lập chỉ mục
     * @param string $type Loại tác vụ: 'URL_UPDATED' (thêm/sửa) hoặc 'URL_DELETED' (xóa)
     * @return array
     */
    public function indexUrl(string $url, string $type = 'URL_UPDATED'): array
    {
        try {
            $accessToken = $this->getAccessToken();

            $response = Http::withToken($accessToken)
                ->post('https://indexing.googleapis.com/v3/urlNotifications:publish', [
                    'url' => $url,
                    'type' => $type
                ]);

            if ($response->failed()) {
                $errorMsg = $response->json('error.message') ?? $response->body();
                Log::error("Google Indexing failed for $url: $errorMsg");
                return [
                    'success' => false,
                    'message' => "Google API lỗi: " . $errorMsg
                ];
            }

            return [
                'success' => true,
                'message' => 'Lập chỉ mục thành công'
            ];
        } catch (\Exception $e) {
            Log::error("Google Indexing exception: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Hàm phụ trợ Base64 URL Safe Encoding
     */
    protected function base64UrlEncode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}
